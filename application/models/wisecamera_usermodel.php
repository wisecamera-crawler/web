<?php
/**
 * This file contains the implementation for the UserModel Model
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
 * This model is used for doing all kinds of operations on accounts. It
 * supports the following operations :
 * Check if an account exists, register a new user to the `user` table.
 *
 * LICENSE : none
 *
 * @category Model
 * @package  Wisecamera
 * @author   Kai Yuen <keeperkai@msn.com>
 */
class Wisecamera_UserModel extends CI_Model
{
    /**
     * Constructor
     *
     * Initializes this model
     *
     * @return This UserModel Model.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * UserModel checkAccountExists
     *
     * This function checks if an account exists in the system.
     *
     * @param string $account The account name to be checked.
     *
     * @return boolean True, if there exists an account named $account;
     * Else return false.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function checkAccountExists($account)
    {
        $query = $this->db->get_where('user', array('user_id'=>$account));

        $result = $query->result_array();
        if (sizeof($result)>0) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * UserModel registerUser
     *
     * This function registers a new user into the system using the input
     * arguments.
     *
     * @param string $account The new account to be created.
     * @param string $password The password for the account.
     * @param string $email The email associated with the account.
     * @param bit $isgoogle If the user is logging in with google open id for
     * the first time, this field will be set to 1, and their account will be
     * their email account on google with no password ; If the user is
     * registering a local account then this field will be 0.
     *
     * @return none
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function registerUser($account, $password, $email, $isgoogle)
    {
        if (!$this->checkAccountExists($account)) {
            $this->db->insert(
                'user',
                array('user_id'=>$account,'password'=>$password,'email'=>$email,'isGoogleLogin'=>$isgoogle)
            );
        }
    }
}
