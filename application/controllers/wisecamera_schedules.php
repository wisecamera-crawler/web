<?php
/**
 * This file contains the implementation for the Schedules controller
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
 * This controller is used for scheduling projects.
 * When the users do any operations on the schedules, their action is
 * logged by the <LogModel>. Usually, the controller responds with JSON objects
 * that will tell the front end whether the status of the action and error
 * messages if an error has occured.
 *
 * LICENSE : none
 *
 * @category Controller
 * @package  Wisecamera
 * @author   Kai Yuen <keeperkai@msn.com>
 */
class Wisecamera_Schedules extends Wisecamera_CheckUser
{
    /**
     * Constructor
     * 
     * This contructor will initialize the required model <LogModel>
     * and <ScheduleModel>.
     *
     * @return This Projects controller.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('wisecamera_logmodel', 'logModel');
        $this->load->model('wisecamera_schedulemodel', 'scheduleModel');
    }
    /**
     * Schedules getSchedules
     * 
     * This function replies directly to the user agent, it will reply with a
     * json encoded array of schedules in the database.
     *
     * @return none
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function getSchedules()
    {
        $schedules = $this->scheduleModel->getSchedules();
        header("Content-type: application/json");
        echo json_encode($schedules);
    }
    /**
     * Schedules deleteSchedule
     * 
     * This function will get a schedule id from the HTTP post body.
     * This schedule id will then be used to delete a schedule in the database
     * Depending on the result of the delete, this function will reply with a
     * json object with keys below :
     * status = 'error' | 'success'
     * errorMessage = '' | `the error that has occurred`
     *
     * @return none
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function deleteSchedule()
    {
        $id = $this->input->post('schedule_id');
        $content = $this->scheduleModel->getScheduleDataString($id);
        $result = $this->scheduleModel->deleteSchedule($id);
        header("Content-type: application/json");
        if ($result['errorMessage']==='') {
            $this->logModel->logDeleteSchedule($id, $content);
            echo json_encode(array('status'=>'success', 'errorMessage'=>''));
        } else {
            echo json_encode(
                array(
                    'status'=>'error',
                    'errorMessage'=>$result['errorMessage']
                )
            );
        }
    }
    /**
     * Schedules insertSchedule
     * 
     * This function will get the attributes of a schedule from the post
     * body. This schedule id will then be used to insert a schedule in
     * the database.
     * Depending on the result of the insert, this function will reply with a
     * json object with keys below :
     * status = 'error' | 'success'
     * errorMessage = '' | `the error that has occurred`
     *
     * @return none
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function insertSchedule()
    {
        $period = $this->input->post('period');
        $type = $this->input->post('type');
        $schedule = $this->input->post('schedule');
        $time = $this->input->post('time');
        $target = $this->input->post('target');
        $result = $this->scheduleModel->insertScheduleAndGroup(
            $period,
            $type,
            $schedule,
            $time,
            $target
        );
        $schedule_id = $result['schedule_id'];
        header("Content-type: application/json");
        if ($result['errorMessage']==='') {
            $this->logModel->logInsertSchedule($schedule_id);
            echo json_encode(array('status'=>'success'));
        } else {
            echo json_encode(
                array(
                    'status'=>'error',
                    'errorMessage'=>$result['errorMessage']
                )
            );
        }
    }
}
