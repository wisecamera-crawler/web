<?php
/**
 * This file contains the implementation for the Email controller
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
 * This controller is used for adding/deleting emergency notification email
 * accounts. If an important error has occured, the email accounts registered
 * with this controller will be notified.
 *
 * LICENSE : none
 *
 * @category Controller
 * @package  Wisecamera
 * @author   Kai Yuen <keeperkai@msn.com>
 */
class Wisecamera_Email extends Wisecamera_CheckUser
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
        $this->load->model('wisecamera_emailmodel', 'emailModel');
    }
    /**
     * Email insertEmail
     *
     * This function will insert the email account in the HTTP POST body into
     * the database for future use.
     * As a CI controller, the access path is :
     *      <baseurl>/index.php/email/insertemail
     * If register success, it will output a JSON object with `status` field
     * `success`; otherwise it will output the same object with `error`.
     *
     * @return none
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function insertEmail()
    {
        $email = $this->input->post('email');
        header("Content-type: application/json");
        $output = array('status' => 'success');
        if (!$this->emailModel->insertEmail($email)) {
            $output['status'] = 'error';
        }
        echo json_encode($output);
    }
    /**
     * Email deleteEmail
     *
     * This function will delete the email account in the HTTP POST body from
     * the database. This will prevent this email account from getting
     * notifications in the future.
     * As a CI controller, the access path is :
     *      <baseurl>/index.php/email/deleteemail
     * If delete success, it will output a JSON object with `status` field
     * `success`; otherwise it will output the same object with `error`.
     *
     * @return none
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function deleteEmail()
    {
        $email = $this->input->post('email');
        header("Content-type: application/json");
        $output = array('status' => 'success');
        if (!$this->emailModel->deleteEmail($email)) {
            $output['status'] = 'error';
        }
        echo json_encode($output);
    }
}
