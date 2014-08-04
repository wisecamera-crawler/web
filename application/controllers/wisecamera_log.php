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


        $this->db->select("user_id");
        $query = $this->db->get("user");
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

        $this->db->select("timestamp, user_id, ip, action");
        $this->db->where("type = 'user' AND timestamp < now()");
        $this->db->order_by("timestamp", "desc");
        $query = $this->db->get("log");
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

        $this->db->select("timestamp, user_id, ip, action");
        $this->db->where("type = 'project'");
        $this->db->order_by("timestamp", "desc");
        $query = $this->db->get("log");
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

        $this->db->select("timestamp, user_id, ip, action");
        $this->db->where("type = 'schedule'");
        $this->db->order_by("timestamp", "desc");
        $query = $this->db->get("log");
        $result = $query->result_array();
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

        $this->db->select(
            "crawl_status.project_id, project.name, crawl_status.starttime, crawl_status.status,
        crawl_status.wiki, crawl_status.vcs, crawl_status.issue,crawl_status.download, crawl_status.endtime"
        );
        $this->db->order_by("crawl_status.starttime", "desc");
        $this->db->from("crawl_status");
        $this->db->join("project", "crawl_status.project_id = project.project_id", "left");
        $query = $this->db->get();
        $result = $query->result_array();

//	var_dump($result);

        for ($a=0; $a < sizeof($result); $a++) {
            $arrangedData[$a]["prjID"]=$result[$a]["project_id"];
            $arrangedData[$a]["prjName"]=$result[$a]["name"];
            $arrangedData[$a]["prjExeST"]=$result[$a]["starttime"];
		$arrangedData[$a]["prjExeResultA"]="";
		if($result[$a]["status"] == "fail"){
	            $arrangedData[$a]["prjExeResult"]="失敗";
			if($result[$a]["wiki"] != "no_change" && $result[$a]["wiki"] != "success_update"){
				if($result[$a]["wiki"] == "cannot_get_data"){
					$arrangedData[$a]["prjExeResultA"] .="無法取得Wiki資料頁面, ";
				}
				else if($result[$a]["wiki"] == "proxy_error"){
					$arrangedData[$a]["prjExeResultA"] ="no_proxy  ";
				}
				else if($result[$a]["wiki"] == "can_not_resolve"){
					$arrangedData[$a]["prjExeResultA"] .="解析不到Wiki內容, ";
				}
				else if($result[$a]["wiki"] == "time_out"){
					$arrangedData[$a]["prjExeResultA"] ="Time_Out  ";
				}
				else{
					 $arrangedData[$a]["prjExeResultA"] .="程式錯誤";
				}
			}
		  	if($result[$a]["vcs"] != "no_change" && $result[$a]["vcs"] != "success_update"){
                                if($result[$a]["vcs"] == "cannot_get_data"){
                                        $arrangedData[$a]["prjExeResultA"] .="無法取得VCS資料頁面, ";
                                }
                                else if($result[$a]["vcs"] == "proxy_error"){
                                        $arrangedData[$a]["prjExeResultA"] .="";
                                }
                                else if($result[$a]["vcs"] == "can_not_resolve"){
                                        $arrangedData[$a]["prjExeResultA"] .="解析不到VCS內容, ";
                                }
                                else{
                                         $arrangedData[$a]["prjExeResultA"] .="程式錯誤";
                                }
			}	
			if($result[$a]["issue"] != "no_change" && $result[$a]["issue"] != "success_update"){
                                if($result[$a]["issue"] == "cannot_get_data"){
                                        $arrangedData[$a]["prjExeResultA"] .="無法取得issue tracker資料頁面, ";
                                }
                                else if($result[$a]["issue"] == "proxy_error"){
                                        $arrangedData[$a]["prjExeResultA"] .="";
                                }
                                else if($result[$a]["issue"] == "can_not_resolve"){
                                        $arrangedData[$a]["prjExeResultA"] .="解析不到issue tracker內容, ";
                                }
                                else{
                                         $arrangedData[$a]["prjExeResultA"] .="程式錯誤";
                                }
			}	
			if($result[$a]["download"] != "no_change" && $result[$a]["download"] != "success_update"){
                                if($result[$a]["download"] == "cannot_get_data"){
                                        $arrangedData[$a]["prjExeResultA"] .="無法取得Downloads資料頁面, ";
                                }
                                else if($result[$a]["download"] == "proxy_error"){
                                        $arrangedData[$a]["prjExeResultA"] .="";
                                }
                                else if($result[$a]["download"] == "can_not_resolve"){
                                        $arrangedData[$a]["prjExeResultA"] .="解析不到Downloads內容, ";
                                }
                                else{
                                         $arrangedData[$a]["prjExeResultA"] .="程式錯誤";
                                }
			}
				
                                         $arrangedData[$a]["prjExeResultA"] = substr( $arrangedData[$a]["prjExeResultA"],0, strlen ($arrangedData[$a]["prjExeResultA"])-2);
		}else if($result[$a]["status"] == "no_change"){
		    $arrangedData[$a]["prjExeResult"]="成功";
		    $arrangedData[$a]["prjExeResultA"]="資料無異動";
		}else if($result[$a]["status"] == "success_update"){
		    $arrangedData[$a]["prjExeResult"]="成功";
		    $arrangedData[$a]["prjExeResultA"]="資料異動";
		}
            $arrangedData[$a]["prjExeET"]=$result[$a]["endtime"];
        }
        $data=$arrangedData;
//	var_dump($data);
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

        $this->db->select("timestamp, user_id, ip, action");
        $this->db->where("type = 'query'");
        $this->db->order_by("timestamp", "desc");
        $query = $this->db->get("log");
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

        $this->db->select("timestamp, user_id, ip, action");
        $this->db->where("type = 'server'");
        $this->db->order_by("timestamp", "desc");
        $query = $this->db->get("log");
        $result = $query->result_array();
        $data=$result;
        echo json_encode($data);
    }
}
