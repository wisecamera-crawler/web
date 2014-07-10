<?php
/**
 * A controller for communication interface between proxy and main server
 *
 * PHP version 5
 *
 * LICENSE : none
 *
 * @category Controller
 * @package  Wisecamera
 * @author   Poyu Chen <poyu677@gmail.com>
 * @license  none <none>
 * @version  GIT: <git_id>
 * @link     none
 */

/**
 * A controller for communication interface between proxy and main server
 *
 * LICENSE : none
 *
 * @category Controller
 * @package  Wisecamera
 * @author   Poyu Chen <poyu677@gmail.com>
 * @license  none <none>
 * @version  Release: <package_version>
 * @link     none
 */
class Wisecamera_Proxy extends CI_Controller
{
    /**
     * Proxy register
     *
     * This function accpet the proxy's connection and record in DB
     * As a CI controller, the access path is :
     *      <baseurl>/index.php/proxy/register/<ip>/<port>
     * If register success, it will return `success` message,
     * else it will report error, and show the error type
     *
     * @param string $ip   Proxy's IP address
     * @param string $port Proxy's port to access
     *
     * @return none, show the result through echo
     *
     * @author Poyu Chen <poyu677@gmail.com>
     * @version 1.0
     */
    public function register($ip, $port)
    {
        //$ip = $this->input->post("ip");
        //$port = $this->input->post("port");
        //check ip format first
        $checkIP = preg_match(
            '/^((?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}'
            . '(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?))*$/',
            $ip,
            $m
        );
        $checkPort = preg_match("/^[0-9]*$/", $port, $m);

        if ($checkIP == false or $checkPort == false) {
            echo "fail : format invalid";

            return;
        }

        $query = $this->db->get_where("proxy", array("proxy_ip"=>$ip));
        $result = $query->result_array();

        //nothing, dirctly insert
        if (sizeof($result) == 0) {
            $q1 = $this->db->insert(
                "proxy",
                array
                (
                    "proxy_ip"=>$ip,
                    "proxy_port"=>$port,
                    "status"=>"on-line"
                )
            );
            $q2 = $this->db->insert(
                "log",
                array
                (
                    "user_id"=>"",
                    "ip"=>$ip,
                    "type"=>"server",
                    "action"=>"Proxy Server 完成佈屬"
                )
            );

            if ($q1 == false  or $q2 == false) {
                echo "fail : DB error";
            } else {
                echo "success";
            }
        } else {
            $q1 = $this->db->update(
                "proxy",
                array
                (
                    "proxy_port"=>$port,
                    "status"=>"on-line"
                ),
                array("proxy_ip"=>$ip)
            );
            $q2 = $this->db->insert(
                "log",
                array
                (
                    "user_id"=>"",
                    "ip"=>$ip,
                    "type"=>"server",
                    "action"=>"Proxy Server 完成重啟"
                )
            );
            if ($q1 == false or $q2 == false) {
                echo "fail : DB error";
            } else {
                echo "success";
            }
        }

        return;
    }
    /**
     * Obtain proxy servers list
     *
     * This function is to get proxy servers list in DB
     * As a CI controller, the access path is :
     *      <baseurl>/index.php/proxy/getProxyList
     * If it success, it will return list of proxy servers in DB
     *
     *
     * @author Charlie Huang <huangckqq22@gmail.com>
     * @version 1.0
     */
    public function getProxyList()
    {
        header("Content-type: application/json");
        $this->db->select('proxy_ip, status');
        $query = $this->db->get('proxy');
        $result = $query->result_array();
        if (sizeof($result) == 0) {
            $msg['status'] = 'empty';
            echo '0';
        } else {
            $data=$result;
            echo json_encode($data);
        }
    }
    /**
     * changing the proxy servers status in DB
     *
     * This function is to change the proxy servers status in DB
     * and write the log to the DB
     * As a CI controller, the access path is :
     *      <baseurl>/index.php/proxy/work
     * @return none
     *
     * @author Charlie Huang <huangckqq22@gmail.com>
     * @version 1.0
     */
    public function work()
    {
        $account = $this->input->post('account');
//	    var_dump($account);

        $this->db->select('proxy_ip, status');
        $query = $this->db->get('proxy');
        $result = $query->result_array();
        if (sizeof($result) == 0) {
            $msg['status'] = 'empty';
            echo '0';
        } else {
            for ($a=0; $a<sizeof($result); $a++) {
                if ($account[$a]=="0") {
                    if ($result[$a]["status"] != "disable") {
                        $result[$a]["status"]= "disable";
                        //SQL update
                        $this->db->update(
                            'proxy',
                            array('status'=>"disable"),
                            array('proxy_ip'=> $result[$a]["proxy_ip"])
                        );

                        //SQL insert record
                        $proxyIp = $result[$a]['proxy_ip'];
                        $user_id = $this->session->userdata('ACCOUNT');
                        $user_ip = $_SERVER['REMOTE_ADDR'];
                        $action ="使用者$user_id 將server($proxyIp) 停止啟用";
                        $currentTime = new DateTime(null, new DateTimeZone($this->config->item('time_zone')));
                        $this->db->insert(
                            'log',
                            array(
                                'user_id'=>"$user_id",
                                'ip'=>"$user_ip",
                                'type'=>'server',
                                'action'=>$action,
                                'timestamp'=>$currentTime->format('Y-m-d H:i:s')
                            )
                        );
                    }
                } else {
                    if ($result[$a]["status"] == "disable") {
                        $result[$a]["status"] = "off-line";
                        //SQL update
                        $this->db->update(
                            'proxy',
                            array('status'=>"off-line"),
                            array('proxy_ip'=> $result[$a]["proxy_ip"])
                        );

                        //SQL insert record
                        $proxyIp = $result[$a]['proxy_ip'];
                        $user_id = $this->session->userdata('ACCOUNT');
                        $user_ip = $_SERVER['REMOTE_ADDR'];
                        $action ="使用者$user_id 將server($proxyIp) 啟用";
                        $currentTime = new DateTime(null, new DateTimeZone($this->config->item('time_zone')));
                        $this->db->insert(
                            'log',
                            array(
                                'user_id'=>"$user_id",
                                'ip'=>"$user_ip",
                                'type'=>'server',
                                'action'=>$action,
                                'timestamp'=>$currentTime->format('Y-m-d H:i:s')
                            )
                        );
                    }
                }
            }
            $data=$result;
//		var_dump($this->userIP);
//		echo json_encode($data);
        }

    }
}
