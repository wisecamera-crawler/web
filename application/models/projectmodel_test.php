<?php
class ProjectModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function checkProjectExists($project_id)
    {
        $query = $this->db->get_where('project', array('project_id'=>$project_id));
        $result = $query->result_array();
        if (sizeof($result)>0) {
            return true;
        }

        return false;
    }
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
