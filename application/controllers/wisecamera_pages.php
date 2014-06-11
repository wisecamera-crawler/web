<?php
/**
 * This file contains the implementation for the Pages controller
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
 * This controller is used for rendering pages to the user-agent, the pages are
 * mainly composed of three parts: header, body, footer.
 * The header contains links to the needed extension libraries and also a 
 * visual header in the web page that will contain the Chinese title of
 * the page, user information, navigation bar and a log out button.
 * The body will be rendered using different views in the project, depending
 * on which page the user is visiting this controller will load the 
 * corresponding view.
 *
 * The footer just contains html tags to finish up the html document.
 * When the user visits any of the pages, the their session will extended,
 * if they don't have their account in their session they will be redirected
 * to the login page.
 *
 * When the user logs in to the system the information of the user will be
 * logged to the system database with the <LogModel>. Whenever they visit
 * another page or have any sort of activity, their login span will be
 * extended by 30 minutes.
 *
 * LICENSE : none
 *
 * @category Controller
 * @package  Wisecamera
 * @author   Kai Yuen <keeperkai@msn.com>
 */
class Wisecamera_Pages extends CI_Controller
{
    /**
     * Constructor
     * 
     * This contructor will initialize the required model <LogModel>.
     *
     * @return This Pages controller.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('wisecamera_logmodel', 'logModel');
    }
    /**
     * Pages view
     * 
     * This function will render the view according to the url of the user
     * agent.
     * As a CI controller, the access path is : 
     *      <baseurl>/index.php/pages/view/<page_name>
     * If the view exists under <baseurl>/codeigniter/application/views/pages/
     * then that view file will be used to render the body of the view.
     * Otherwise it will show a HTTP 404 message.
     * If the page is rendered successfully it will log the user's information
     * as described above.
     *
     * @return none
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function view($page = 'login')
    {
        if (!file_exists('application/views/pages/'.$page.'.php')) {
            // Whoops, we don't have a page for that!
            show_404();
        }
        if ($page === 'login') {
            $this->load->view('pages/login');
        } else {
            if (!$this->session->userdata('ACCOUNT')) {
                header('Location: '.base_url().'index.php/pages/view/login');
                return;
            }
            switch ($page) {
                case 'searchtable':
                    $data['title'] = '專案資料查詢';
                    break;
                case 'import':
                    $data['title'] = '專案匯入';
                    break;
                case 'schedule':
                    $data['title'] = '資料收集排程';
                    break;
                case 'setemail':
                    $data['title'] = '信箱設定';
                    $this->load->model('wisecamera_emailmodel', 'emailModel');
                    $emails = $this->emailModel->getEmail();
                    $data['emails'] = $emails;
                    break;
                case 'userLoginLog':
                    $data['title'] = '使用者登入記錄';
                    break;
                case 'prjEditLog':
                    $data['title'] = '專案編修記錄';
                    break;
                case 'scheduleEditLog':
                    $data['title'] = '排程設定記錄';
                    break;
                case 'scheduleExeLog':
                    $data['title'] = '排程執行記錄';
                    break;
                case 'queryLog':
                    $data['title'] = '資料查詢記錄';
                    break;
                case 'deployLog':
                    $data['title'] = '伺服器佈署記錄';
                    break;
            }
            $data['username'] = $this->session->userdata('ACCOUNT');
            $this->load->view('templates/header', $data);
            $this->load->view('pages/'.$page, $data);
            $this->load->view('templates/footer', $data);
            $this->logModel->extendUserLogin($data['username']);
        }
    }
}
