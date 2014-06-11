<?php
/**
 * This file contains the implementation for the LogModel Model
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
 * This model is used for doing all kinds of operations related to logging
 * in the system. These operations include logging query, login/logout,
 * insert/modify/delete project, get project modification history,
 * insert/delete schedules.
 *
 * LICENSE : none
 *
 * @category Model
 * @package  Wisecamera
 * @author   Kai Yuen <keeperkai@msn.com>
 * @param string $userIP The ip address of the current user
 * @param string $user_id The user_id of the current user.
 */
class Wisecamera_LogModel extends CI_Model
{
    public $userIP = '';
    public $user_id = '';
    /**
     * Constructor
     * 
     * Initializes this model, gets the id and ip of the user and sets them
     * in the corresponding variables.
     *
     * @return This LogModel Model.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function __construct()
    {
        parent::__construct();
        $this->user_id = $this->session->userdata('ACCOUNT');
        $this->userIP = $_SERVER['REMOTE_ADDR'];
    }
    /**
     * LogModel translateType
     * 
     * This function translates the graph type in English from the database to
     * Chinese.
     *
     * @param string $type The type of the graph data in English.
     *
     * @return string The graph data in Chinese. If the graph type does not
     * exist the function will return an empty string ''.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function translateType($type)
    {
        $output = '';
        switch($type)
        {
            case 'wiki':
                $output = 'Wiki資訊';
                break;
            case 'vcs':
                $output = 'VCS資訊';
                break;
            case 'download':
                $output = 'Download資訊';
                break;
            case 'issuetracker':
                $output = 'Issue Tracker資訊';
                break;
            case 'proxy':
                $output = 'Proxy資訊';
                break;
            case 'status':
                $output = '狀態資訊';
                break;
        }
        return $output;
    }
    /**
     * LogModel logQuery
     * 
     * This function logs that a user has queried for a certain graph type of
     * a project.
     *
     * @param string $project_id The id of the project.
     * @param string $type The type of the graph data in English.
     *
     * @return none
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function logQuery($project_id, $type)
    {
        $currentTime = new DateTime();
        $type_translate = $this->translateType($type);
        $action = '查詢了代號為'.$project_id.'的專案之'.$type_translate;
        $this->db->insert(
            'log',
            array(
                'user_id'=>$this->user_id,
                'ip'=>$this->userIP,
                'type'=>'query',
                'action'=>$action,
                'timestamp' =>$currentTime->format('Y-m-d H:i:s')
            )
        );
    }
    /**
     * LogModel loginUser
     * 
     * This function logs that a user has logged in from a certain ip address.
     * Note that when the user logs in the function automatically adds a
     * row in the database that is 30 mintues into the future, that way if
     * the user crashes, we can still have an estimate of when he crashed or
     * logged out. The login log viewer page will filter the rows in the future
     * so users won't see those entries until the user has actually logged out.
     *
     * @param string $userid The id of the user.
     *
     * @return none
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function loginUser($userid)
    {
        $currentTime = new DateTime();
        $currentTimePlus30 = new DateTime();
        $currentTimePlus30->add(new DateInterval('PT30M'));
        $this->db->insert(
            'log',
            array(
                'user_id'=>$userid,
                'ip'=>$this->userIP,
                'type'=>'user',
                'action'=>'自'.$this->userIP.'登入',
                'timestamp'=>$currentTime->format('Y-m-d H:i:s')
            )
        );
        $this->db->insert(
            'log',
            array(
                'user_id'=>$userid,
                'ip'=>$this->userIP,
                'type'=>'user',
                'action'=>'自'.$this->userIP.'登出',
                'timestamp'=>$currentTimePlus30->format('Y-m-d H:i:s')
            )
        );
    }
    /**
     * LogModel logoutUser
     * 
     * This function logs that a user has logged out from a certain ip address.
     *
     * @param string $userid The id of the user.
     *
     * @return none
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function logoutUser($userid)
    {
        $currentTime = new DateTime();
        $currentTimePlus30 = new DateTime();
        $currentTimePlus30->add(new DateInterval('PT30M'));
        $this->db->select('*');
        $this->db->from('log');
        $this->db->where(array('user_id'=>$userid, 'type'=>'user'));
        $this->db->order_by('timestamp', 'desc');
        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result)>0) {
            $this->db->update(
                'log',
                array(
                    'user_id'=>$userid,
                    'ip'=>$this->userIP,
                    'type'=>'user',
                    'action'=>'自'.$this->userIP.'登出',
                    'timestamp'=>$currentTime->format('Y-m-d H:i:s')
                ),
                array('log_id'=>$result[0]['log_id'])
            );
        }
    }
    /**
     * LogModel extendUserLogin
     * 
     * This function checks if the user is still within the time frame of
     * the 30 minute logout entry, if so this function will add 30 minutes
     * to the logout entry, this function is typically called whenever the
     * user visits another page, so if the user has any activity in 30 minutes
     * their logout entry will be moved further into the future by 30 minutes
     * starting from now. If the user is not within the time frame, then the
     * function just inserts one login entry with present as the time stamp,
     * and another log out entry with 30 minutes into the future as the time
     * stamp.
     *
     * @param string $userid The id of the user.
     *
     * @return none
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function extendUserLogin($userid)
    {
        $this->db->select('*');
        $this->db->from('log');
        $this->db->where(array('user_id'=>$userid,'type'=>'user'));
        $this->db->order_by('timestamp', 'desc');
        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result)>0) {
            $logoutTime = new DateTime($result[0]['timestamp']);
            $currentTime = new DateTime();
            $currentTimePlus30 = new DateTime();
            $currentTimePlus30->add(new DateInterval('PT30M'));
            if ($logoutTime>$currentTime) {
                $this->db->update(
                    'log',
                    array(
                        'timestamp'=> $currentTimePlus30->format(
                            'Y-m-d H:i:s'
                        )
                    ),
                    array('log_id'=>$result[0]['log_id'])
                );
            } else {
                $this->loginUser($userid);
            }
        } else {
            $this->loginUser($userid);
        }
    }
    /**
     * LogModel logUpdateProject
     * 
     * This function logs that the info of a project has been updated/modified.
     *
     * @param array $project An associative array representing the project's
     * data after the update, it has the following keys:
     * project_id : id of project.
     * year : year of the project.
     * type : type of the project.
     * name : name of the project.
     * url : url link to the project home.
     * platform : platform of the project.
     * leader : leader of the project.
     *
     * @return none
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function logUpdateProject($project)
    {
        $currentTime = new DateTime();
        $project_id = $project['project_id'];
        $year = $project['year'];
        $type = $project['type'];
        $name = $project['name'];
        $url = $project['url'];
        $platform = $project['platform'];
        $leader = $project['leader'];
        $action = 'modify,'.$project_id.','.$name.
            ','.$year.','.$type.','.$url.','.$platform.','.$leader;
        $this->db->insert(
            'log',
            array(
                'user_id'=>$this->user_id, 'ip'=>$this->userIP,
                'type'=>'project', 'action'=>$action,
                'timestamp'=>$currentTime->format('Y-m-d H:i:s')
            )
        );
    }
    /**
     * LogModel logInsertProject
     * 
     * This function logs that the info of a project has been inserted.
     *
     * @param array $project An associative array representing the inserted
     * project's data, it has the following keys:
     * project_id : id of project.
     * year : year of the project.
     * type : type of the project.
     * name : name of the project.
     * url : url link to the project home.
     * platform : platform of the project.
     * leader : leader of the project.
     *
     * @return none
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function logInsertProject($project)
    {
        $currentTime = new DateTime();
        $project_id = $project['project_id'];
        $year = $project['year'];
        $type = $project['type'];
        $name = $project['name'];
        $url = $project['url'];
        $leader = $project['leader'];
        $platform = $project['platform'];
        $action = 'new,'.$project_id.','.$name.','.$year.','.$type.','.$url.
            ','.$platform.','.$leader;
        $this->db->insert(
            'log',
            array(
                'user_id'=>$this->user_id, 'ip'=>$this->userIP,
                'type'=>'project', 'action'=>$action,
                'timestamp'=>$currentTime->format('Y-m-d H:i:s')
            )
        );
    }
    /**
     * LogModel logDeleteProject
     * 
     * This function logs that the info of a project has been inserted.
     *
     * @param string $project_id Log that a project with the id : project_id
     * has been deleted from the system.
     *
     * @return none
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function logDeleteProject($project_id)
    {
        $currentTime = new DateTime();
        $action = 'delete,'.$project_id;
        $this->db->insert(
            'log',
            array(
                'user_id'=>$this->user_id,
                'ip'=>$this->userIP,
                'type'=>'project',
                'action'=>$action,
                'timestamp'=>$currentTime->format('Y-m-d H:i:s')
            )
        );
    }
    /**
     * LogModel getProjectModificationHistory
     * 
     * This function gets the modification history of a project with the id :
     * $project_id. The result is returned as an array.
     *
     * @param string $project_id The id of the project in question.
     *
     * @return array An array of modification entries, each containing the
     * following keys :
     * project_id : project's id
     * timestamp : The date/time the modification occurred
     * user_id : The user that modified the project
     * name : project's name
     * year : project's year
     * type : project's type
     * url : project's url
     * platform : project's platform
     * leader : project's leader
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function getProjectModificationHistory($project_id)
    {
        $output = array();
        $query = $this->db->get_where('log', array('type' => 'project'));
        $result = $query->result_array();
        foreach ($result as $log) {
            $actionArray = explode(",", $log['action']);
            if (sizeof($actionArray)>=8) {
                list(
                    $actionType,
                    $p_id,
                    $name,
                    $year,
                    $type,
                    $url,
                    $platform,
                    $leader
                ) = $actionArray;
                if (($actionType=='new'||$actionType=='modify')
                    &&($p_id==$project_id)) {
                    $output[] = array(
                        'user_id'=>$log['user_id'],
                        'timestamp'=>$log['timestamp'],
                        'project_id'=>$p_id,
                        'name'=>$name,
                        'year'=>$year,
                        'type'=>$type,
                        'url'=>$url,
                        'platform'=>$platform,
                        'leader'=>$leader
                    );
                }
            }
        }
        if (sizeof($output)>0) {
            return $output;
        } else {
            return false;
        }
    }
    /**
     * LogModel logInsertSchedule
     * 
     * This function logs that a new schedule has been arranged in the system.
     *
     * @param string $schedule_id The id of the schedule that's been arranged.
     *
     * @return boolean returns whether the log was successful.
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function logInsertSchedule($schedule_id)
    {
        $currenttime = new DateTime();
        $this->load->model('schedulemodel', 'scheduleModel');
        $result = $this->scheduleModel->getScheduleDataString($schedule_id);
        if ($result!=='error') {
            $this->db->insert(
                'log',
                array(
                    'user_id'=>$this->user_id,
                    'ip'=>$this->userIP,
                    'action'=>'新增了 '.$result.'排程',
                    'type'=>'schedule',
                    'timestamp'=>$currenttime->format('Y-m-d H:i:s')
                )
            );
            return true;
        } else {
            return false;
        }
    }
    /**
     * LogModel logInsertSchedule
     * 
     * This function logs that a schedule has been deleted in the system.
     *
     * @param string $schedule_id The id of the schedule that's been deleted.
     * @param string A string that represents the schedule before it was
     * deleted.
     * @return boolean returns whether the log was successful.
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function logDeleteSchedule($schedule_id, $schedule_data)
    {
        $currenttime = new DateTime();
        $this->load->model('schedulemodel', 'scheduleModel');
        $this->db->insert(
            'log',
            array(
                'user_id'=>$this->user_id,
                'ip'=>$this->userIP,
                'action'=>'刪除了 '.$schedule_data.'排程',
                'type'=>'schedule',
                'timestamp'=>$currenttime->format('Y-m-d H:i:s')
            )
        );
    }
}
