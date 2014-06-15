<?php
/**
 * This file contains the implementation for the ScheduleModel Model
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
 * This model is used for doing all kinds of operations on schedules. It
 * supports the following operations :
 * get a string the represents a schedule, get an array that represents all
 * the schedules, delete a schedule, insert a schedule, insert both the
 * schedule and the group of targets of the schedule
 *
 * LICENSE : none
 *
 * @category Model
 * @package  Wisecamera
 * @author   Kai Yuen <keeperkai@msn.com>
 */
class Wisecamera_ScheduleModel extends CI_Model
{
    /**
     * Constructor
     * 
     * Initializes this model
     *
     * @return This ScheduleModel Model.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * ScheduleModel intToWeekDay
     * 
     * This private function maps a string containing an int to the 
     * Chinese weekday string. For '0', it means the schedule does not use
     * the week period so it returns an empty string ''. For '1'~'7' the
     * function returns a weekday in Chinese.
     *
     * @param string $val The value string to be translated to weekday.
     *
     * @return string Chinese weekday string.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    private function intToWeekDay($val)
    {
        $weekday = '';
        switch ($val) {
            case 0:
                $weekday = '0';
                break;
            case 1:
                $weekday = '週一';
                break;
            case 2:
                $weekday = '週二';
                break;
            case 3:
                $weekday = '週三';
                break;
            case 4:
                $weekday = '週四';
                break;
            case 5:
                $weekday = '週五';
                break;
            case 6:
                $weekday = '週六';
                break;
            case 7:
                $weekday = '週日';
                break;

        }

        return $weekday;
    }
    /**
     * ScheduleModel stripDate
     * 
     * This private function takes a string representing a date and time and
     * returns only the hour and minutes string.
     * 
     * @param string $datetime string representing a date and time
     *
     * @return string Only contains the hour and minute.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    private function stripDate($datetime)
    {
        $tmp = new DateTime($datetime);
        $time = $tmp->format('H:i');

        return $time;
    }
    /**
     * ScheduleModel stripSecond
     * 
     * This private function takes a string representing a date and time and
     * returns the same datetime string with it's second stripped.
     * 
     * @param string $datetime string representing a date and time
     *
     * @return string Removes seconds from the string.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    private function stripSecond($datetime)
    {
        $tmp = new DateTime($datetime);
        $time = $tmp->format('Y-m-d H:i');

        return $time;
    }
    /**
     * ScheduleModel translateAll
     * 
     * This private function takes a string argument, if the string is 'all',
     * it will return the Chinese translation of it, otherwise it will return
     * the original string.
     * 
     * @param string $str The string representing the year/type of the schedule.
     *
     * @return string if $str = 'all' returns the Chinese of 'all'; Else
     * return the original $str.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    private function translateAll($str)
    {
        if ($str=='all') {
            return '全部';
        }

        return $str;
    }
    /**
     * ScheduleModel getScheduleDataString
     * 
     * This function takes a string argument $schedule_id, it will
     * use that id to get the schedule data from the database, and then
     * generate a string representing the data of the schedule.
     *
     * @param string $schedule_id The schedule id of the schedule in database.
     *
     * @return string A string of data representing the data of the schedule,
     * both the period, datetime and target projects will be recorded in this
     * string.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function getScheduleDataString($schedule_id)
    {
        $output = array();
        $query = $this->db->get_where('schedule', array('schedule_id'=>$schedule_id));
        $result = $query->result_array();
        $group_text = '';
        $time_text = '';
        if (sizeof($result)>0) {
            $schedule = $result[0];
            $query2 = $this->db->get_where('schedule_group', array('schedule_id'=>$schedule_id));
            $result2 = $query2->result_array();

            if ($result2[0]['type']=='project') {
                foreach ($result2 as $member) {
                    $group_text .= $member['member'].',';
                }
                $group_text = rtrim($group_text, ',');
            } else {
                $data = array('year'=>'','type'=>'');
                foreach ($result2 as $member) {
                    if ($member['type']=='year') {
                        $data['year'] = $member['member'];
                    } else {
                        $data['type'] = $member['member'];
                    }
                }
                $group_text = $this->translateAll($data['year'])
                    .'年度/類別為'.$this->translateAll($data['type']).'的專案';
            }
            switch ($result[0]['sch_type']) {
                case 'weekly':
                    $output['period'] = '每周';
                    $output['time'] = $this->intToWeekDay($schedule['schedule'])
                        .' '.$this->stripDate($schedule['time']);
                    $time_text = '每周 '.$output['time'];
                    break;
                case 'daily':
                    $output['period'] = '每天';
                    $output['time'] = $this->stripDate($schedule['time']);
                    $time_text = '每天 '.$output['time'];
                    break;
                case 'one_time':
                    $output['period'] = '單次';
                    $output['time'] = $this->stripSecond($schedule['time']);
                    $time_text = '單次 '.$output['time'];
                    break;
            }
            $output['schedule_id'] = $schedule_id;
            $output['group'] = $group_text;

            return $time_text.' '.$group_text;
        } else {
            return 'error';
        }

    }
    /**
     * ScheduleModel getSchedules
     * 
     * This function will get all the schedules in the `schedule` table and
     * all the group data in `schedule_group` table, and then generate a
     * string representing each of the schedules and put them into two
     * seperate array fields : active and inactive according to the datetime
     * to execute the schedule. If the schedule will still be executed in
     * the future then it will be included in the active array field ;
     * otherwise, it will be included in the inactive array field.
     *
     * @return array A associative array with two keys :
     * active => A array of strings, each representing a schedule(which
     * contains the period, datetime and target projects of the schedule)
     * that will be executed in the future.
     * inactive => A array of strings, each representing a schedule(which
     * contains the period, datetime and target projects of the schedule)
     * that will not be executed in the future.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function getSchedules()
    {
        $active = array();
        $inactive = array();
        $query = $this->db->get('schedule');
        $result = $query->result_array();
        foreach ($result as $schedule) {
            $element = array('period'=>'','time'=>'','group'=>'', 'schedule_id'=>$schedule['schedule_id']);
            $query2 = $this->db->get_where('schedule_group', array('schedule_id'=>$schedule['schedule_id']));
            $result2 = $query2->result_array();
            $group_text = '';
            if (sizeof($result2)==0) {
                //error, a schedule with no group attached to it
            } else {
                //see which type
                if ($result2[0]['type']=='project') {

                    foreach ($result2 as $member) {
                        $group_text .= $member['member'].',';
                    }
                    $group_text = rtrim($group_text, ',');
                } else {
                    $data = array('year'=>'', 'type'=>'');
                    foreach ($result2 as $member) {
                        if ($member['type']=='year') {
                            $data['year'] = $member['member'];
                        } else {
                            $data['type'] = $member['member'];
                        }
                    }
                    $group_text = $this->translateAll($data['year']).
                        '年度/類別為'.$this->translateAll($data['type']).'的專案';
                }
            }
            $element['group'] = $group_text;
            switch ($schedule['sch_type']) {
                case 'weekly':
                    $element['period'] = '每周';
                    $element['time'] = $this->intToWeekDay(
                        $schedule['schedule']
                    ).' '.$this->stripDate($schedule['time']);
                    break;
                case 'daily':
                    $element['period'] = '每天';
                    $element['time'] = $this->stripDate($schedule['time']);
                    break;
                case 'one_time':
                    $element['period'] = '單次';
                    $element['time'] = $this->stripSecond($schedule['time']);
                    break;
            }
            if ($schedule['sch_type']=='one_time') {
                $scheduledate = new DateTime($schedule['time']);
                $currentdate = new DateTime();
                if ($currentdate>$scheduledate) {
                    $inactive[] = $element;
                } else {
                    $active[] = $element;
                }
            } else {
                $active[] = $element;
            }
        }
        $output = array('active'=>$active, 'inactive'=>$inactive);

        return $output;
    }
    /**
     * ScheduleModel deleteSchedule
     * 
     * This function will delete a schedule with id = $schedule_id
     * from the database.
     *
     * @return array A associative array with two keys :
     * status : A string, either 'success' or 'error' depending on the result
     * of the delete operation.
     * errorMessage : A string that describes what error has occurred.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function deleteSchedule($schedule_id)
    {
        $result = $this->db->delete('schedule_group', array('schedule_id'=>$schedule_id));
        $this->db->delete('schedule', array('schedule_id'=>$schedule_id));
        if ($result) {
            return array('status'=>'success', 'errorMessage'=>'');
        } else {
            return array('status'=>'error', 'errorMessage'=>'could not find the schedule in database');

        }
    }
    /**
     * ScheduleModel isScheduleAndGroupInputLegal
     * 
     * This function checks if the input arguments are valid to create a
     * schedule in the system.
     *
     * @param string $period Either 'weekly' | 'daily' | "one_time' is legal.
     * it represents the period for the schedule to execute.
     * @param string $type Either 'yearclass' | 'id' is legal, it represents
     * the targets of the schedule.
     * @param int $schedule Depending on the period, this argument should
     * either be 0 or 1~7, it represents the weekday that the schedule
     * should be executed on if the 'weekly' period is picked.
     * @param string $time A string representing the exact time to execute
     * the schedule. Depending on the period $time will have different
     * formats. When the period is weekly, $time will only contain the time
     * without date, the weekday is described in $schedule as mentioned above
     * ; When the period is one_time, it will contain the date; When the
     * period is daily it will also only contain the time without date.
     * @param string $target Depending on the $type, $target is an
     * associative array with the following keys :
     * year : Can be 'all' or any valid year in the system.
     * type : Can be 'all' or any valid project type.
     * project_ids : A string of project ids separated by comma.
     *
     * @return string A string that describes the error in the input arguments.
     * if all the arguments are valid the function returns ''.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function isScheduleAndGroupInputLegal($period, $type, $schedule, $time, $target)
    {
        $err = '';
        if (!($period==='weekly'||$period==='daily'||$period==='one_time')) {
            $err .= 'invalid period'.PHP_EOL;
        }
        if (!($type==='yearclass'||$type==='id')) {
            $err .= 'invalid type'.PHP_EOL;
        }
        if ($period==='weekly') {
            if ($schedule<1||$schedule>7) {
                $err .= 'invalid schedule'.PHP_EOL;
            }
        }
        if ($type==='yearclass') {
            if (!$target['year']||!$target['type']) {
                $err .= 'invalid year/type'.PHP_EOL;
            }
        } elseif ($type==='id') {
            $project_ids = array_map('trim', explode(",", $target['project_ids']));
            $this->load->model('wisecamera_projectmodel', 'projectModel');
            if (!($this->projectModel->checkMultipleProjectExists($project_ids))) {
                $err .= 'some projects do not exist'.PHP_EOL;
            }

        }
        //time checking
        if ($period === 'one_time') {
            if (DateTime::createFromFormat('m/d/Y G:i:s', $time) === false) {
                $err .= 'not a valid time'.PHP_EOL;
            }
        } elseif ($period==='daily'||$period==='weekly') {
            if (DateTime::createFromFormat('G:i:s', $time) === false) {
                $err .= 'not a valid time'.PHP_EOL;
            }
        }

        return $err;

    }
    /**
     * ScheduleModel insertScheduleAndGroup
     * 
     * This function inserts a new schedule into the system, this operation
     * consists of two parts:
     * 1. inserting the schedule data into the `schedule` table.
     * 2. inserting the target data into the `schedule_group` table.
     *
     * @param string $period Either 'weekly' | 'daily' | "one_time' is legal.
     * it represents the period for the schedule to execute.
     * @param string $type Either 'yearclass' | 'id' is legal, it represents
     * the targets of the schedule.
     * @param int $schedule Depending on the period, this argument should
     * either be 0 or 1~7, it represents the weekday that the schedule
     * should be executed on if the 'weekly' period is picked.
     * @param string $time A string representing the exact time to execute
     * the schedule. Depending on the period $time will have different
     * formats. When the period is weekly, $time will only contain the time
     * without date, the weekday is described in $schedule as mentioned above
     * ; When the period is one_time, it will contain the date; When the
     * period is daily it will also only contain the time without date.
     * @param string $target Depending on the $type, $target is an
     * associative array with the following keys :
     * year : Can be 'all' or any valid year in the system.
     * type : Can be 'all' or any valid project type.
     * project_ids : A string of project ids separated by comma.
     *
     * @return array A associative array containing the following keys :
     * errorMessage string If the input data is not valid, then it will
     * describe the error in this string message.
     * schedule_id string The schedule id of the successfully inserted
     * schedule.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function insertScheduleAndGroup($period, $type, $schedule, $time, $target)
    {
        $output = array('errorMessage'=>'','schedule_id'=>'');
        $err = $this->isScheduleAndGroupInputLegal($period, $type, $schedule, $time, $target);
        if ($err !== '') {
            $output['errorMessage'] = $err;

            return $output;
        }
        if ($period === 'one_time') {
            $tmp = explode(" ", $time);
            list($m,$d,$y) = explode('/', $tmp[0]);
            $time = $y.'-'.$m.'-'.$d.' '.$tmp[1];
        } else {
            $time = '2012-1-1 '.$time;
        }
        $schedule_id = $this->insertSchedule($period, $schedule, $time);
        if ($type == 'yearclass') {
            $this->insertScheduleGroupYearType($schedule_id, $target['year'], $target['type']);
        } else {
            $project_ids = array_map('trim', explode(",", $target['project_ids']));
            $this->insertScheduleGroupProjectIds($schedule_id, $project_ids);

        }
        $output['errorMessage'] = $err;
        $output['schedule_id'] = $schedule_id;

        return $output;
    }
    /**
     * ScheduleModel insertScheduleAndGroup
     * 
     * This function inserts a new schedule group in the
     * `schedule_group` table with year/type combination as the target. It
     * associates the group elements with the schedule with id = $schedule_id
     * . When the schedule is executed any project that fits the year/type
     * combination is executed.
     *
     * @param string $schedule_id The schedule id to be associated with.
     * @param string $year Can be 'all' or any valid year in the system.
     * @param string $type Can be 'all' or any valid project type in
     * the system.
     *
     * @return none
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function insertScheduleGroupYearType($schedule_id, $year, $type)
    {
        $this->db->insert('schedule_group', array('schedule_id'=>$schedule_id, 'member'=>$year, 'type'=>'year'));
        $this->db->insert('schedule_group', array('schedule_id'=>$schedule_id, 'member'=>$type, 'type'=>'group'));
    }
    /**
     * ScheduleModel insertScheduleGroupProjectIds
     * 
     * This function inserts a new schedule group in the
     * `schedule_group` table with a list of project ids as target. It
     * associates the group elements with the schedule with id = $schedule_id
     * . When the schedule is executed any project with an id that's contained
     * in this list will be crawled.
     *
     * @param string $schedule_id The schedule id to be associated with.
     * @param string $project_ids The list of project ids to crawl when the
     * schedule is carried out.
     *
     * @return none
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function insertScheduleGroupProjectIds($schedule_id, $project_ids)
    {
        foreach ($project_ids as $project_id) {
            $this->db->insert(
                'schedule_group',
                array('schedule_id'=>$schedule_id, 'member'=>$project_id, 'type'=>'project')
            );
        }
    }
    /**
     * ScheduleModel insertScheduleGroupProjectIds
     * 
     * This function inserts a new schedule in the `schedule` table with all
     * the timing information.
     *
     * @param string $period How often will the schedule be executed.
     * @param int $schedule The weekday if period is set to 'weekly'.
     * @parram string $time The exact time to carry out the schedule.
     * schedule is carried out.
     *
     * @return string The schedule id of the inserted schedule.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function insertSchedule($period, $schedule, $time)
    {
        $this->db->insert('schedule', array('sch_type'=>$period,'schedule'=>$schedule,'time'=>$time));

        return $this->db->insert_id();
    }
}
