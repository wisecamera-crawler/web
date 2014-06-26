<?php
/**
 * A controller for log related page
 *
 * PHP version 5
 *
 * LICENSE : none
 *
 * @category Controller
 * @package  Wisecamera
 * @author   Charlie Huang <huangckqq22@gmail.com>
 * @license  none <none>
 * @version  GIT: <git_id>
 * @link     none
 */
class Wisecamera_Log extends Wisecamera_CheckUser
{
	/**
     * Obtain user list
     * 
     * This function is to get user list in DB
     * As a CI controller, the access path is : 
     *      <baseurl>/index.php/log/getUsers
     * If it success, it will return list of user's id in DB
     *
     *
     * @author Charlie Huang <huangckqq22@gmail.com>
     * @version 1.0
     */   
    public function getUsers()
    {
        //echo 'In getUsers';
        
            
        header("Content-type: application/json");
       
    
        $query = $this->db->query("SELECT `user_id` FROM `user`");
        $result = $query->result_array();
        if (sizeof($result) == 0) {
            $msg['status'] = 'empty';
            echo '0';
        } else {
            $data=$result;
            //var_dump($data);
            echo json_encode($data);
        }
    }	
    /**
     * get logs related user login
     * 
     * This function is to logs related user login in DB
     * As a CI controller, the access path is : 
     *      <baseurl>/index.php/log/userLogin
     * If it success, it will return list of logs related user login in DB
     *
     *
     * @author Charlie Huang <huangckqq22@gmail.com>
     * @version 1.0
     */
    public function userLogin()
    {
        //echo 'In userLogin';

        header("Content-type: application/json");

        $query = $this->db->query(
            "SELECT `timestamp`, 
            `user_id`,`ip`,`action`  FROM `log` WHERE 
            `type`='user' and  `timestamp` < now() 
            ORDER BY `timestamp` DESC"
        );
        $result = $query->result_array();
        $data=$result;
        //var_dump($data);
        echo json_encode($data);
    }
	/**
     * get logs related project editing
     * 
     * This function is to logs related project editing in DB
     * As a CI controller, the access path is : 
     *      <baseurl>/index.php/log/projectEdit
     * If it success, it will return list of logs related project editing in DB
     *
     *
     * @author Charlie Huang <huangckqq22@gmail.com>
     * @version 1.0
     */
    public function projectEdit()
    {

        header("Content-type: application/json");
        $query = $this->db->query(
            "SELECT `timestamp`, `user_id`,`ip`,`action` 
            FROM `log` WHERE `type`='project' ORDER BY 
            `timestamp` DESC"
        );
        $result = $query->result_array();
        for ($a=0; $a < sizeof($result); $a++) {
            $_array = explode(",", $result[$a]["action"]);
            if ($_array[0] == "new") {
                $result[$a]["action"]= "新增了 代號為$_array[1]的專案";
            } elseif ($_array[0]=="modify") {
                $result[$a]["action"]= "修改了 代號為$_array[1]的專案";
            } elseif ($_array[0]=="delete") {
                $result[$a]["action"]= "刪除了 代號為$_array[1]的專案";
            }
        }
        $data=$result;
//		var_dump($data);
        echo json_encode($data);
    }
	/**
     * get logs related schedule editing
     * 
     * This function is to logs related schedule editing in DB
     * As a CI controller, the access path is : 
     *      <baseurl>/index.php/log/scheduleEdit
     * If it success, it will return list of logs related schedule editing in DB
     *
     *
     * @author Charlie Huang <huangckqq22@gmail.com>
     * @version 1.0
     */
    public function scheduleEdit()
    {
        header("Content-type: application/json");
        $query = $this->db->query(
            "SELECT `timestamp`, `user_id`,`ip`,
            `action`  FROM `log` WHERE `type`='schedule' 
            ORDER BY `timestamp` DESC"
        );
        $result = $query->result_array();
        $dataCount =0;
        for ($a=0; $a < sizeof($result); $a++) {
            $_array = explode(",", $result[$a]["action"]);
            if ($_array[0] != "scheEx") {
                $tempResult[$dataCount++] = $result[$a];
            }
        }
        $data=$tempResult;
        echo json_encode($data);
    }
	/**
     * get logs related schedule executing
     * 
     * This function is to logs related schedule executing in DB
     * As a CI controller, the access path is : 
     *      <baseurl>/index.php/log/scheduleExe
     * If it success, it will return list of logs related schedule executing in DB
     *
     *
     * @author Charlie Huang <huangckqq22@gmail.com>
     * @version 1.0
     */
    public function scheduleExe()
    {
        header("Content-type: application/json");

        $query = $this->db->query(
		"SELECT c.project_id, p.name, c.starttime, c.status, c.wiki,
                 c.vcs, c.issue,c.download,  c.endtime FROM crawl_status c,
                 project p WHERE c.project_id = p.project_id GROUP BY
                 c.starttime DESC"
        );
        $result = $query->result_array();

        for ($a=0; $a < sizeof($result); $a++) {
            $arrangedData[$a]["prjID"]=$result[$a]["project_id"];
            $arrangedData[$a]["prjName"]=$result[$a]["name"];
            $arrangedData[$a]["prjExeST"]=$result[$a]["starttime"];
            $arrangedData[$a]["prjExeResult"]=$result[$a]["status"];
            $arrangedData[$a]["prjExeResultA"]=$result[$a]["wiki"];
            $arrangedData[$a]["prjExeET"]=$result[$a]["endtime"];
        }
        $data=$arrangedData;
        echo json_encode($data);
/*
        $query = $this->db->query(
            "SELECT `timestamp`, `user_id`,`ip`,`action` 
            FROM `log` WHERE `type`='schedule' ORDER BY `timestamp` ASC"
        );
        $result = $query->result_array();
        $dataCount =0;
        for ($a=0; $a < sizeof($result); $a++) {
            $_array = explode(",", $result[$a]["action"]);
            if ($_array[0] == "scheEx") {
                $tempResult[$dataCount++] = $result[$a];
            }
        }
        for ($a=0; $a < sizeof($tempResult); $a++) {
            $_array = explode(",", $tempResult[$a]["action"]);
            $arrangedData[$a]["prjID"]=$_array[1];
            $arrangedData[$a]["prjName"]=$_array[2];
            $arrangedData[$a]["prjExeST"]=$_array[3];
            $arrangedData[$a]["prjExeResult"]=$_array[4];
            $arrangedData[$a]["prjExeResultA"]=$_array[5];
            $arrangedData[$a]["prjExeET"]=$_array[6];
        }
        $data=$arrangedData;
        echo json_encode($data);
*/
    }
	/**
     * get logs related searching
     * 
     * This function is to logs related searching in DB
     * As a CI controller, the access path is : 
     *      <baseurl>/index.php/log/query
     * If it success, it will return list of logs related searching in DB
     *
     *
     * @author Charlie Huang <huangckqq22@gmail.com>
     * @version 1.0
     */
    public function query()
    {
        header("Content-type: application/json");
        $query = $this->db->query(
            "SELECT `timestamp`, `user_id`,`ip`,`action` 
            FROM `log` WHERE `type`='query' ORDER BY `timestamp` DESC"
        );
        $result = $query->result_array();
        $data=$result;
        echo json_encode($data);
    }
	/**
     * get logs related servers deploying
     * 
     * This function is to logs related servers deploying in DB
     * As a CI controller, the access path is : 
     *      <baseurl>/index.php/log/deploy
     * If it success, it will return list of logs related servers deploying in DB
     *
     *
     * @author Charlie Huang <huangckqq22@gmail.com>
     * @version 1.0
     */
    public function deploy()
    {
        header("Content-type: application/json");
        $query = $this->db->query(
            "SELECT `timestamp`, `user_id`,`ip`,`action`  FROM `log` WHERE 
            `type`='server' ORDER BY `timestamp`DESC"
        );
        $result = $query->result_array();
        $data=$result;
        echo json_encode($data);
    }
}
