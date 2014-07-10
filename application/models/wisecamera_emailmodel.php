<?php
/**
 * This file contains the implementation for the EmailModel Model
 *
 * PHP version 5
 *
 * LICENSE : none
 *
 * @category Model
 * @package  Wisecamera
 * @author   Kai Yuen <keeperkai@msn.com>
 * @license  none <none>
 */


/**
 * This model is used for doing all kinds of operations related to the error
 * notification accounts in the database. It supports insert, delete and
 * getting all the notification email accounts as an array.
 *
 * LICENSE : none
 *
 * @category Model
 * @package  Wisecamera
 * @author   Kai Yuen <keeperkai@msn.com>
 */
class Wisecamera_EmailModel extends CI_Model
{
    /**
     * Constructor
     * 
     * Initializes this model
     *
     * @return This EmailModel Model.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * EmailModel insertEmail
     *
     * This function inserts an email into the notification email list. Any
     * email in the list will be notified by the system if some huge error
     * occurs.
     *
     * @param string $email The email address to be notified.
     *
     * @return boolean true, if insert operation is successful; else false.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function insertEmail($email)
    {
        return $this->db->insert('email', array('email'=>$email));
    }
    /**
     * EmailModel deleteEmail
     *
     * This function deletes a email from the notification email list. After
     * this operation the email will no longer be notified of errors.
     *
     * @param string $email The email address to be taken off the notification
     * list.
     *
     * @return boolean true, if delete operation is successful; else false.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function deleteEmail($email)
    {
        return $this->db->delete('email', array('email'=>$email));
    }
    /**
     * EmailModel getEmail
     *
     * This function retrieves the notification email list from database.
     * Then it returns it as an string array.
     *
     * @return array A string array containing all the email accounts to be
     * notified.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function getEmail()
    {
        $query = $this->db->get('email');
        $result = $query->result_array();
        $output = array();
        foreach ($result as $email) {
            $output[] = $email['email'];
        }
        return $output;
    }
}
