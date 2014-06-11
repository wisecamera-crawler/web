<?php
/**
 * This file contains the implementation for the CheckUser controller
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
 * This controller is used as a base class for all the controllers that need
 * to check the users session to determine if the user is legit.
 *
 * LICENSE : none
 *
 * @category Controller
 * @package  Wisecamera
 * @author   Kai Yuen <keeperkai@msn.com>
 */
class Wisecamera_CheckUser extends CI_Controller
{
    /**
     * Constructor
     * 
     * This contructor will initialize the required model <EmailModel>.
     *
     * @return This Email controller.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function __construct()
    {
        parent::__construct();
        //echo '111111';
        $userid = $this->sessionValid();
        if ($userid) {
            $this->load->model('wisecamera_logmodel', 'logModel');
            $this->logModel->extendUserLogin($userid);
            //echo '22222222';
        } else {
            $response = array
            (
                'status'=>'fail',
                'errorMessage'=>'Please login.',
                'data'=>'Please login.'
            );
            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        }
    }
    /**
     * CheckUser sessionValid
     * 
     * This function checks the session of the user, if the session
     * is expired or doesn't exist, then it will throw an error.
     *
     * @return false if the user is not logged in; the user id if the user
     * is logged in
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    private function sessionValid()
    {
        $user_id = $this->session->userdata('ACCOUNT');
        if ($user_id===0||(!$user_id)) {
            return false;
        } else {
            return $user_id;
        }
    }
}
