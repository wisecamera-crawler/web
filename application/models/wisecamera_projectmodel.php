<?php
/**
 * This file contains the implementation for the ProjectModel Model
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
 * This model is used for doing all kinds of checks on projects, for example:
 * it supports checking if a single project exists, checking if multiple
 * projects exists, get all the valid project types in the system, get all the
 * valid years in the system.
 *
 * LICENSE : none
 *
 * @category Model
 * @package  Wisecamera
 * @author   Kai Yuen <keeperkai@msn.com>
 */
class Wisecamera_ProjectModel extends CI_Model
{
    /**
     * Constructor
     * 
     * Initializes this model
     *
     * @return This ProjectModel Model.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * ProjectModel checkProjectExists
     * 
     * This function checks if a project with id = $project_id exists in the
     * system.
     *
     * @param string $project_id The project id to be checked.
     *
     * @return boolean True, if a project with id = $project_id exists in the
     * system. Else return false.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function checkProjectExists($project_id)
    {
        $query = $this->db->get_where('project', array('project_id'=>$project_id));
        $result = $query->result_array();
        if (sizeof($result)>0) {
            return true;
        }

        return false;
    }
    /**
     * ProjectModel checkMultipleProjectExists
     * 
     * This function checks if all the projects with ids contained in
     * $projectid_arr exists in the system.
     *
     * @param string $projectid_arr The project ids to be checked.
     *
     * @return boolean False, if any id in $projectid_arr does not exist in
     * the system. Else return true.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function checkMultipleProjectExists($projectid_arr)
    {
        if (sizeof($projectid_arr) <=0) {
            return false;
        }
        foreach ($projectid_arr as $project_id) {
            if (!$this->checkProjectExists($project_id)) {
                return false;
            }
        }

        return true;
    }
    /**
     * ProjectModel getValidProjectTypes
     * 
     * This function loops through all the projects and generates an array
     * that contains all the valid project types.
     *
     * @return array An array containing all the valid project types.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function getValidProjectTypes()
    {
        $output = array();
        $this->db->select('type');
        $this->db->order_by('type', 'ASC');
        $query = $this->db->get('project');

        $result = $query->result_array();
        foreach ($result as $type) {
            if (!in_array($type['type'], $output)) {
                $output[] = $type['type'];
            }
        }

        return $output;
    }
    /**
     * ProjectModel getValidProjectYears
     * 
     * This function loops through all the projects and generates an array
     * that contains all the valid project years.
     *
     * @return array An array containing all the valid project years.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function getValidProjectYears()
    {
        $output = array();
                $this->db->select('year');
        $this->db->order_by('year', 'ASC');
        $query = $this->db->get('project');
        $result = $query->result_array();
        foreach ($result as $year) {
            if (!in_array($year['year'], $output)) {
                    $output[] = $year['year'];
            }
        }

                return $output;
    }
}
