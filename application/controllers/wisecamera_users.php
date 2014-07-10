<?php
/**
 * This file contains the implementation for the Users controller
 *
 * PHP version 5
 *
 * LICENSE : none
 *
 * @category Controller
 * @package  Wisecamera
 * @author   Kai Yuen <keeperkai@msn.com>
 * @license  none <none>
 */


/**
 * This controller is used for doing all sorts of operations on user accounts.
 * Such as login, logout, using google open id to login, register a new
 * account and retrieve a password.
 *
 * LICENSE : none
 *
 * @category Controller
 * @package  Wisecamera
 * @author   Kai Yuen <keeperkai@msn.com>
 */
class Wisecamera_Users extends CI_Controller
{
    /**
     * Constructor
     *
     * This contructor will initialize the required model <LogModel>
     * and <UserModel>.
     *
     * @return This Projects controller.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('wisecamera_logmodel', 'logModel');
        $this->load->model('wisecamera_usermodel', 'userModel');
    }
    /**
     * Users logout
     *
     * This function will logout a user and destroy their session. The
     * information is logged using the <LogModel>. It will then redirect
     * the user agent to the login page.
     *
     * @return none
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function logout()
    {
        $userid = $this->session->userdata('ACCOUNT');
        $this->logModel->logoutUser($userid);
        $this->session->sess_destroy();
        header('Location: '.base_url().'index.php/pages/view/login');
    }
    /**
     * Users resetWithHash
     *
     * This function will check if the user entered hash matches the one
     * in the database. If so it will replace the password for the user.
     *
     * @return none
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function resetWithHash()
    {

        $hash = $this->input->post('hash');
        $account = $this->input->post('account');
        $password = $this->input->post('password');
        $query = $this->db->get_where('user', array('user_id'=>$account));
        $result = $query->result_array();
        $status = 'success';
        $data = '';
        if (sizeof($result) === 0) {
            $status = 'fail';
            $data = '帳號或者hash錯誤';
        } elseif ($password === '') {
            $status = 'fail';
            $data = '密碼不可為空';
        } elseif ((!$password) || (!$account) || (!$hash)) {
            $status = 'fail';
            $data = '資料不可為空';
        } else {
            $toks = explode(":", $result[0]["password"]);
            $h = $toks[2];
            if ($h === $hash) {
                $this->load->helper('hashsalt');
                $dbresult = $this->db->update(
                    'user',
                    array('password'=>create_hash($password)),
                    array('user_id'=>$account)
                );
                if (!$dbresult) {
                    $status = 'fail';
                    $data = '資料庫錯誤';
                }
            } else {
                $status = 'fail';
                $data = '帳號或者hash錯誤';
            }
        }
        header("Content-type: application/json");
        echo json_encode(array('status'=>$status, 'data'=>$data));
    }
    /**
     * Users login
     *
     * This function will login a user and put their account name into the session
     * information is logged using the <LogModel>. It will then redirect
     * the user agent to the searchtable page.
     *
     * @return none
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function login()
    {
        $this->load->helper('hashsalt');
        $msg;
        $userid;
        $hashinfo;
        $this->session->unset_userdata('ACCOUNT');
        $account = $this->input->post("account");
        $password = $this->input->post("password");
        $query = $this->db->get_where(
            'user',
            array(
                'user_id'=>$account,
                'isGoogleLogin'=>0
            )
        );
        $result = $query->result_array();
        if (sizeof($result) == 0) {
            $msg['status'] = 'error';
            $msg['type'] = 'Account or password error';
        } else {
            $userid = $result[0]["user_id"];
            $hashinfo = $result[0]["password"];
        }
        if (isset($userid)&&(validate_password($password, $hashinfo))) {
            $session_data = array('ACCOUNT' => "$userid");
            $this->session->set_userdata($session_data);
        }
        if ($this->session->userdata('ACCOUNT')) {
            $this->logModel->extendUserLogin(
                $this->session->userdata('ACCOUNT')
            );
            header(
                'Location: '. base_url(). 'index.php/pages/view/searchtable'
            );
        } else {
            header('Location: ' . base_url(). 'index.php/pages/view/login');
        }
    }
    /**
     * Users login
     *
     * This function will login a user with their google open id and put
     * their account name(their google email) into the session.
     * If it is their first time on the system, the system will register
     * their user info into the database.
     * information is logged using the <LogModel>. It will then redirect
     * the user agent to the searchtable page.
     *
     * @return none
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function googlelogin()
    {
        require 'application/third_party/openid.php';
        $openid = new LightOpenID(base_url());
        if (!$openid->mode) {
            $openid->identity = 'https://www.google.com/accounts/o8/id';
            $openid->required = array(
                'contact/email', 'namePerson/first', 'namePerson/last'
            );
            header('Location: ' . $openid->authUrl());
        } else {
            if ($openid->validate() == true) {
                $user_data = $openid->getAttributes();
                $mail = $user_data["contact/email"];
                $session_data = array('ACCOUNT' => "$mail");
                $this->session->set_userdata($session_data);
                $this->userModel->registerUser("$mail", '', "$mail", 1);
                $this->logModel->extendUserLogin("$mail");
                header(
                    'Location: ' . base_url(). 'index.php/pages/view/searchtable'
                );
            } else {
                header('Content-Type: text/html');
                echo '<html><head></head>
                    <body>Google Open ID 有些問題，請重試。
                    </body></html>';
            }
        }
    }
    /**
     * Users register
     *
     * This function will register a new user account in the system. The
     * account information is retrieved from the post body. It will then
     * check if the inputs are valid, depending on the result the output
     * is a json encoded object with the following keys :
     * status = 'error' | 'success'
     * data = 'which input is invalid' | ''
     *
     * @return none
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function register()
    {
        $this->load->helper('hashsalt');
        $account = $this->input->post('account');
        $password = $this->input->post('password');
        $confirm = $this->input->post('confirm');
        $email = $this->input->post('email');
        //check if the account is already in use
        $query = $this->db->query(
            "SELECT * FROM `user` WHERE  `user_id` = '$account'"
        );
        $result = $query->result_array();
        $response = array('data'=>'','status'=>'success');
        if (sizeof($result)!=0) {
            $response['data'] = '已經有人使用'.$account.'這個帳號名稱.';
            $response['status'] = 'error';
        }
        //check if email is already in use
        $query = $this->db->get_where(
            'user',
            array(
                'email' => $email,
                'isGoogleLogin'=>0
            )
        );
        $result = $query->result_array();
        if (sizeof($result)!=0) {
            $response['data'] = '已經有人使用'.$email.'這個信箱.';
            $response['status'] = 'error';
        }
        //check if the password matches confirm
        if ($password!=$confirm) {
            $response['data'] .= '密碼與確認密碼不符.';
            $response['status'] = 'error';
        }
        if ($password==''||$confirm=='') {
            $response['data'] .= '密碼/確認密碼不可為空.';
            $response['status'] = 'error';
        }
        if ($account=='') {
            $response['data'] .= '帳號不可為空.';
            $response['status'] = 'error';
        }
        if ($email=='') {
            $response['data'] .= '信箱不可為空.';
            $response['status'] = 'error';
        }
        if ($response['status']==='error') {
            header("Content-type: application/json");
            echo json_encode($response);
            return;
        }
        $password = create_hash($password);
        $this->db->insert('user', array('user_id'=>$account, 'password'=>$password, 'email'=>$email));
        $query = $this->db->query(
            "SELECT * FROM `user` WHERE  `user_id` = '$account'"
        );
        $result = $query->result_array();
        if (sizeof($result)==0) {
            $response['data'] .= '寫入資料庫失敗，請重試\n';
            $response['status'] = 'error';
        }
        if ($response['status']!='success') {
            $response['data'] .= '帳號創造成功，請輸入帳號密碼';
            $response['status'] = 'success';
        }
        header("Content-type: application/json");
        echo json_encode($response);
    }
    /**
     * Users forgotpw
     *
     * This function will retrieve the password of the account contained
     * in the HTTP post body and send it to the registered email account.
     * It'll check if the input is valid, depending on the result the output
     * is a json encoded object with the following keys :
     * status = 'error' | 'success'
     * data = `the account doesn't exist` | ''
     *
     * @return none
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function forgotpw()
    {
        $account = $this->input->post('account');
        $response;
        $query = $this->db->query(
            "SELECT * FROM `user` WHERE  `user_id` = '$account'"
        );
        $result = $query->result_array();
        if (sizeof($result)!=0) {
            $toks = explode(":", $result[0]['password']);
            $password = $toks[2];
            $email = $result[0]['email'];
            $msg = '你的NSC帳號為： '.$account.PHP_EOL
                .'你的NSC Hash為： '.$password;
            $this->load->library('email');
            $this->email->set_newline("\r\n");
            // Set to, from, message, etc.
            $this->email->from(
                'openfoundry.sendmail@gmail.com',
                'NSC system'
            );
            $this->email->to($email);
            $this->email->subject('NSC account info');
            $this->email->message($msg);
            $result = $this->email->send();
            $response['status'] = 'success';
            $response['data'] = '已寄信到您的信箱'.$email;
        } else {
            $response['status'] = 'error';
            $response['data'] = '查無此帳號';
        }
        header("Content-type: application/json");
        echo json_encode($response);
    }
}
