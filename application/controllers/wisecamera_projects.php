<?php
/**
 * This file contains the implementation for the Projects controller
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
 * This controller is used for doing all sorts of operations on projects.
 * When the users do any operations on the projects, their action is
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
class Wisecamera_Projects extends Wisecamera_CheckUser
{
    /**
     * Constructor
     *
     * This contructor will initialize the required model <LogModel>
     * and <ProjectModel>.
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
        $this->load->model('wisecamera_projectmodel', 'projectModel');
    }
    /**
     * Projects stripTime
     *
     * This private function will strip away the time from a date string.
     *
     * @param string $date a string that represents date and time.
     *
     * @return string with the time part stripped from $date.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    private function stripTime($date)
    {
        $createDate = new DateTime($date, new DateTimeZone($this->config->item('time_zone')));
        $strip = $createDate->format('Y-m-d');
        return $strip;
    }
    /**
     * Projects replyWithJSON
     *
     * This private function will send the proper header and json encode an
     * php object/array then send it to the user agent.
     *
     * @param object $result The result object/array you want to send to the.
     * user agent.
     *
     * @return none.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    private function replyWithJSON($result)
    {
        header("Content-type: application/json");
        echo json_encode($result);
    }
    private function getPlatformFromURL($url)
    {
        if (strpos($url, 'code.google.com')!==false) {
            return 'googlecode';
        } elseif (strpos($url, 'github.com')!==false) {
            return 'github';
        } elseif (strpos($url, 'www.openfoundry.org')!==false) {
            return 'openfoundry';
        } elseif (strpos($url, 'sourceforge.net')!==false) {
            return 'sourceforge';
        }
        return '';
    }
    /**
     * Projects verifyProjectParameters
     *
     * This private function will verify whether the arguments are legal to
     * construct a project in the system with.
     * @param string $project_id The project's identifier.
     * @param string $name The project's name.
     * @param string $year The year of the project.
     * @param string $type The class/type of the project.
     * @param string $url The url link to the project home.
     * @param string $leader The name of the leader.
     *
     * @return boolean If the input is not legal, returns false; Else return
     * true.
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    private function verifyProjectParameters($project_id, $name, $year, $type, $url, $leader)
    {
        $errMsg ='';
        if ($project_id=='') {
            $errMsg .='專案代碼不可為空'.PHP_EOL;
        }
        if ($name=='') {
            $errMsg .='專案名稱不可為空'.PHP_EOL;
        }
        if ($year=='') {
            $errMsg .='專案年度不可為空'.PHP_EOL;
        }
        if ($leader=='') {
            $errMsg .='專案主持人不可為空'.PHP_EOL;
        }
        if ($type=='') {
            $errMsg .='專案類型不可為空'.PHP_EOL;
        }
        if ($url=='') {
            $errMsg .='專案網址不可為空'.PHP_EOL;
        }
        $platform = $this->getPlatformFromURL($url);
        if ($platform=='') {
            $errMsg = '無法辨認專案平台'.PHP_EOL;
        }
        if ($errMsg=='') {
            return true;
        } else {
            return false;
        }
    }
    /**
     * Projects verifyProjectParameters
     *
     * This private function will contruct a php array that contains
     * all the data of each project.
     * @param xmlDOM $document The uploaded xml document for batch
     * import/update projects. The format of the document should be like:
     * <?xml version="1.0" encoding="utf-8"?>
     *    <importdesc>
     *        <project>
     *            <project_id>
     *                fake_project_001
     *            </project_id>
     *            <name>
     *                Relativity
     *            </name>
     *            <year>
     *                2012
     *            </year>
     *            <type>
     *                physics
     *            </type>
     *            <url>
     *                code.google.com/p/relativity
     *            </url>
     *            <leader>
     *                愛因斯坦
     *            </leader>
     *        </project>
     *        <project>
     *            <project_id>
     *                fake_project_002
     *            </project_id>
     *            <name>
     *                Quantum Mechanics
     *            </name>
     *            <year>
     *                2012
     *            </year>
     *            <type>
     *                physics
     *            </type>
     *            <url>
     *                code.google.com/p/qm
     *            </url>
     *            <leader>
     *                Feynman
     *            </leader>
     *        </project>
     *    </importdesc>
     *
     * @return array If any of the input is not legal, returns false; Else
     * returns the array of input put into an array.
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    private function constructXMLImport($document)
    {
        $output = array();
        $projects = $document->getElementsByTagName('project');
        foreach ($projects as $project) {
            $id = trim($project->getElementsByTagName('project_id')->item(0)->nodeValue);
            $name = trim($project->getElementsByTagName('name')->item(0)->nodeValue);
            $year = trim($project->getElementsByTagName('year')->item(0)->nodeValue);
            $type = trim($project->getElementsByTagName('type')->item(0)->nodeValue);
            $url = trim($project->getElementsByTagName('url')->item(0)->nodeValue);
            $platform = $this->getPlatformFromURL($url);
            $leader = trim($project->getElementsByTagName('leader')->item(0)->nodeValue);
            if ($this->verifyProjectParameters($id, $name, $year, $type, $url, $leader)) {
                $output[]=array(
                    'project_id'=>$id, 'name'=>$name, 'year'=>$year,
                    'type'=>$type, 'url'=>$url,
                    'platform'=>$platform,
                    'leader'=>$leader
                );
            } else {
                return false;
            }
        }
        return $output;
    }
    /**
     * Projects updateProject
     *
     * This private function will update a project in the database and do
     * logging on success.
     * @param array $project The associative array containing the attributes
     * of the project, the keys are described below:
     * project_id : The id of the project.
     * year : The year of the project.
     * type : The type of the project.
     * url : The link to the project home.
     * leader : The leader of this project.
     *
     * @return boolean If it fails to insert to database, returns false; Else return
     * true.
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    private function updateProject($project)
    {
         $project_id = $project['project_id'];
                $year = $project['year'];
                $type = $project['type'];
                $name = $project['name'];
        $url = $project['url'];
                $platform = $this->getPlatformFromURL($project['url']);
                $leader = $project['leader'];
        $result =  $this->db->update(
            'project',
            array(
                'type'=>$type,
                'name'=>$name, 'url'=>$url, 'platform'=>$platform,
                'year'=>$year, 'leader'=>$leader
            ),
            array('project_id'=>$project_id)
        );
        $this->logModel->logUpdateProject($project);
        return $result;
    }
    /**
     * Projects insertProject
     *
     * This private function will insert a project into the database and do
     * logging on success.
     * @param array $project The associative array containing the attributes
     * of the project, the keys are described below:
     * project_id : The id of the project.
     * year : The year of the project.
     * type : The type of the project.
     * url : The link to the project home.
     * leader : The leader of this project.
     *
     * @return boolean If it fails to insert to database, returns false; Else return
     * true.
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    private function insertProject($project)
    {
        $project_id = $project['project_id'];
        $year = $project['year'];
        $type = $project['type'];
        $name = $project['name'];
        $url = $project['url'];
        $platform = $this->getPlatformFromURL($project['url']);
        $leader = $project['leader'];
        $result =  $this->db->insert(
            'project',
            array(
                'project_id'=>$project_id,
                'type'=>$type,'name'=>$name, 'url'=>$url,
                'platform'=>$platform,
                'year'=>$year, 'leader'=>$leader
            )
        );
        $this->logModel->logInsertProject($project);
        return $result;
    }
    /**
     * Projects insertProject
     *
     * This private function will insert/update a project into the database
     * depending on the id of the project. If a project with the same id
     * already exists in the database it will use the arguments to update
     * the project's information in the database; If no project with the id
     * exists in the database it will insert a new project with the attributes
     * of the argument.
     *
     * @param array $project The associative array containing the attributes
     * of the project, the keys are described below:
     * project_id : The id of the project.
     * year : The year of the project.
     * type : The type of the project.
     * url : The link to the project home.
     * leader : The leader of this project.
     *
     * @return string If it fails to insert/update to database, returns string
     * saying it was unable to update or insert the project with which id; Else
     * return an empty string.
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    private function importSingleProject($project)
    {
        $errMsg = '';
        $project_id = $project['project_id'];
        $query = $this->db->get_where('project', array('project_id'=>$project_id));
        $result = $query->result_array();
        if (sizeof($result)>0) {
            //modify
            if ($result[0]["status"]=="working") {
                $errMsg = "正在執行id為 ".$result[0]["project_id"]." 之專案，無法修改.";
                return $errMsg;
            }
            $result = $this->updateProject($project);
            if (!$result) {
                $errMsg = 'unable to update the project with id = '.$project_id.PHP_EOL;
            }
        } else {
            //insert
            $result = $this->insertProject($project);
            if (!$result) {
                $errMsg = 'unable to insert the project with id = '.$project_id.PHP_EOL;
            }
        }
        return $errMsg;
    }
    /**
     * Projects batchImportProjects
     *
     * This private function will insert/update multiple projects into database
     * depending on the id of the projects. If a project with the same id
     * already exists in the database it will use the arguments to update
     * the project's information in the database; If no project with the id
     * exists in the database it will insert new a project with the attributes
     * of the argument.
     *
     * @param array $projects An array of all the projects that needs to be
     * inserted/updated to the database. Each element of the array contains
     * the attributes of the project, the keys are described below:
     * project_id : The id of the project.
     * year : The year of the project.
     * type : The type of the project.
     * url : The link to the project home.
     * leader : The leader of this project.
     *
     * @return string If it fails to insert/update to database, returns a
     * string saying it was unable to update or insert the project with which
     * id; Else return an empty string.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    private function batchImportProjects($projects)
    {
        $errMsg = '';
        foreach ($projects as $project) {
            $err = $this->importSingleProject($project);
            if ($err != '') {
                $errMsg .= $this->importSingleProject($project);
            }
        }
        return $errMsg;
    }
    /**
     * Projects uploadBatch
     *
     * This function will get the xml string from HTTP Post body, the xml
     * string contains the information to perform a batch insert/update
     * projects. The format of the xml string should be like :
     * <?xml version="1.0" encoding="utf-8"?>
     *    <importdesc>
     *        <project>
     *            <project_id>
     *                fake_project_001
     *            </project_id>
     *            <name>
     *                Relativity
     *            </name>
     *            <year>
     *                2012
     *            </year>
     *            <type>
     *                physics
     *            </type>
     *            <url>
     *                code.google.com/p/relativity
     *            </url>
     *            <leader>
     *                愛因斯坦
     *            </leader>
     *        </project>
     *        <project>
     *            <project_id>
     *                fake_project_002
     *            </project_id>
     *            <name>
     *                Quantum Mechanics
     *            </name>
     *            <year>
     *                2012
     *            </year>
     *            <type>
     *                physics
     *            </type>
     *            <url>
     *                code.google.com/p/qm
     *            </url>
     *            <leader>
     *                Feynman
     *            </leader>
     *        </project>
     *    </importdesc>
     * This function will directly return the result to the user agent as a
     * JSON object containing the keys:
     * status = error | success.
     * errorMessage = '' | `The error that occurred`.
     *
     * @return none
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function uploadBatch()
    {
        $xmlString = $this->input->post('data');
        $doc = new DomDocument();
        $data = array(
            'status'=>'success', 'errorMessage' => '', 'confirmMessage' => ''
        );
        if ($doc->loadXML($xmlString)) {
            if ($projects = $this->constructXMLImport($doc)) {
                $lengthMsg = $this->checkAttributeLength($projects);
                if ($lengthMsg != '') {
                    $resp = array(
                        'status' => 'error',
                        'errorMessage' => $lengthMsg
                    );
                    $this->replyWithJSON($resp);
                    return;
                }
                $data['errorMessage'] = $this->batchImportProjects($projects);
                if ($data['errorMessage']=='') {
                    $this->replyWithJSON($data);
                    return;
                } else {
                    $data['status'] = 'error';
                    $this->replyWithJSON($data);
                    return;
                }
            } else {
                $data['status'] = 'error';
                $data['errorMessage'] = 'XML欄位資料錯誤';
                $this->replyWithJSON($data);
                return;
            }
        } else {
            $data['status'] = 'error';
            $data['errorMessage'] = 'XML文件格式錯誤';
            $this->replyWithJSON($data);
            return;
        }
    }
    /**
     * Projects findDuplicateProjects
     *
     * This private function will check if any of the projects in the argument
     * array already exist in the database.
     *
     * @param array $projects The array of projects that need to be checked.
     *
     * @return The project ids of the duplicate projects. If no duplicates are
     * found, returns an empty string ''.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    private function findDuplicateProjects($projects)
    {
        $msg = '系統中已經有代碼為： ';
        $dupString = '';
        foreach ($projects as $project) {
            $project_id = $project['project_id'];
            $query = $this->db->get_where('project', array('project_id'=>$project_id));
            $result = $query->result_array();
            if (sizeof($result)>0) {
                $dupString .= $project_id.', ';
            }
        }
        if ($dupString == '') {
            return '';
        } else {
            $dupString = rtrim($dupString, ', ');
            return $msg.$dupString;
        }
    }
    /**
     * Projects checkAttributeLength
     *
     * This private function will check if any of the attributes in the
     * projects are too long, if so it returns a human readable error
     * message. If no attributes were too long, it returns ''.
     *
     * @param array $projects The array of projects that need to be checked.
     *
     * @return A human readable error message. If no errors are
     * found, returns an empty string ''.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    private function checkAttributeLength($projects)
    {
        $err = '';
        foreach ($projects as $project) {
            $projectID = $project['project_id'];
            if (strlen($projectID)>32) {
                $err.="專案代碼為: ".$projectID."之專案代碼長度超過32位元".PHP_EOL;
            }
            if (strlen($project['type'])>16) {
                $err.="專案代碼為: ".$projectID."之專案類型長度超過16位元".PHP_EOL;
            }
            if (strlen($project['name'])>64) {
                $err.="專案代碼為: ".$projectID."之專案名稱長度超過64位元".PHP_EOL;
            }
            if (strlen($project['leader'])>32) {
                $err.="專案代碼為: ".$projectID."之專案主持人長度超過32位元".PHP_EOL;
            }
            if (strlen($project['url'])>128) {
                $err.="專案代碼為: ".$projectID."之專案網址長度超過128位元".PHP_EOL;
            }
        }
        return $err;
    }
    /**
     * Projects checkUploadBatch
     *
     * This function will check if any of the projects in the argument
     * array already exist in the database. If so, the function will reply to
     * the user agent with a JSON object saying that some projects already
     * exists in the database. The front end will then ask the user if they
     * want to overwrite(update) the projects. If no projects exist in the
     * database it will just insert all the projects contained in the xml
     * string of the HTTP Post body. The format of the xml string should be
     * like :
     * <?xml version="1.0" encoding="utf-8"?>
     *    <importdesc>
     *        <project>
     *            <project_id>
     *                fake_project_001
     *            </project_id>
     *            <name>
     *                Relativity
     *            </name>
     *            <year>
     *                2012
     *            </year>
     *            <type>
     *                physics
     *            </type>
     *            <url>
     *                code.google.com/p/relativity
     *            </url>
     *            <leader>
     *                愛因斯坦
     *            </leader>
     *        </project>
     *        <project>
     *            <project_id>
     *                fake_project_002
     *            </project_id>
     *            <name>
     *                Quantum Mechanics
     *            </name>
     *            <year>
     *                2012
     *            </year>
     *            <type>
     *                physics
     *            </type>
     *            <url>
     *                code.google.com/p/qm
     *            </url>
     *            <leader>
     *                Feynman
     *            </leader>
     *        </project>
     *    </importdesc>
     * This function will directly reply to the user agent, using a JSON object
     * with the following keys :
     * status = error | success
     * errorMessage = '' | `error that occurred`
     * confirmMessage = '' | `which projects will be overwritten`
     *
     * @return none.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function checkUploadBatch()
    {
        $xmlString = $this->input->post('data');
        $doc = new DomDocument();
        $data = array('status'=>'success','errorMessage' => '','confirmMessage' => '');
        if ($doc->loadXML($xmlString)) {
            if ($projects = $this->constructXMLImport($doc)) {
                $lengthMsg = $this->checkAttributeLength($projects);
                if ($lengthMsg != '') {
                    $resp = array(
                        'status' => 'error',
                        'errorMessage' => $lengthMsg
                    );
                    $this->replyWithJSON($resp);
                    return;
                }
                $dupMsg = $this->findDuplicateProjects($projects);

                if ($dupMsg == '') {
                    $data['errorMessage'] = $this->batchImportProjects($projects);
                    if ($data['errorMessage']=='') {
                        $this->replyWithJSON($data);
                        return;
                    } else {
                        $data['status']='error';
                        $this->replyWithJSON($data);
                        return;
                    }
                } else {
                    $data['confirmMessage'] = $dupMsg;
                    $data['status'] = 'confirm';
                    $this->replyWithJSON($data);
                    return;
                }
            } else {
                $data['errorMessage'] = 'XML欄位資料錯誤';
                $data['status'] = 'error';
                $this->replyWithJSON($data);
                return;
            }
        } else {
            $data['errorMessage'] = 'XML文件格式錯誤';
            $data['status'] = 'error';
            $this->replyWithJSON($data);
            return;
        }
    }
    /**
     * Projects setNewProject
     *
     * This function will check if the arguments in the Post body can be used
     * to construct a project in the database. If not, then reply to the user
     * agent with an error message; otherwise, insert the project into the
     * database and log the action.
     * This function will directly reply to the user agent, using a JSON object
     * with the following keys :
     * status = fail | success
     * errorMessage = '' | `error that occurred`
     *
     * @return none.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function setNewProject()
    {
        $project_id = $this->input->post('project_id');
        $name = $this->input->post('name');
        $year = $this->input->post('year');
        $leader = $this->input->post('leader');
        $url= $this->input->post('url');
        $platform = $this->getPlatformFromURL($url);
        $type = $this->input->post('type');
        $response = array("status" => "success", "errorMessage" => "");
        $errMsg = '';
        if ($project_id=='') {
            $errMsg .='專案代碼不可為空'.PHP_EOL;
        }
        if ($name=='') {
            $errMsg .='專案名稱不可為空'.PHP_EOL;
        }
        if ($year=='') {
            $errMsg .='專案年度不可為空'.PHP_EOL;
        }
        if ($leader=='') {
            $errMsg .='專案主持人不可為空'.PHP_EOL;
        }
        if ($type=='') {
            $errMsg .='專案類型不可為空'.PHP_EOL;
        }
        if ($url=='') {
            $errMsg .='專案網址不可為空'.PHP_EOL;
        }
        if ($platform=='') {
            $errMsg .='無法辨認專案網址平台'.PHP_EOL;
        }
        $result = $this->get_where('project', array('project_id'=>$project_id));
        if (sizeof($result)!=0) {
            $errMsg .='已有專案代碼： '.$project_id.' 在系統中';
        }
        if ($errMsg!='') {
            $response['status']='fail';
            $response['errorMessage']=$errMsg;
            $this->replyWithJSON($response);
            return;
        }
        $this->db->insert(
            'project',
            array(
                'project_id'=>$project_id,
                'type'=>$type,'name'=>$name, 'url'=>$url,
                'platform'=>$platform,
                'year'=>$year, 'leader'=>$leader
            )
        );
        $this->logModel->logInsertProject(
            array(
                'project_id'=>$project_id, 'type'=>$type, 'name'=>$name,
                'url'=>$url, 'platform'=>$platform, 'year'=>$year,
                'leader'=>$leader
            )
        );
        $query = $this->db->get_where('project', array('project_id'=>$project_id));
        $result = $query->result_array();
        if (sizeof($result)==0) {
            $response['status']='fail';
            $response['errorMessage']='無法寫入資料庫';
            $this->replyWithJSON($response);
            return;
        }
        $this->replyWithJSON($response);
    }
    /**
     * Projects deleteProject
     *
     * This function will check if the arguments in the Post body can be used
     * to delete a project in the database. If not, then reply to the user
     * agent with an error message; otherwise, delete the project from the
     * database and log the action.
     * This function will directly reply to the user agent, using a JSON object
     * with the following keys :
     * status = fail | success
     * errorMessage = '' | `error that occurred`
     *
     * @return none.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function deleteProject()
    {
        $project_id = $this->input->post('project_id');
        $response = array('status' => 'success', 'errorMessage' => '');
        $query = $this->db->get_where('project', array('project_id'=>$project_id));
        $result = $query->result_array();
        if (sizeof($result)==0) {
            $response["status"] = "fail";
            $response["errorMessage"] = "系統中專案代碼為 ".$project_id." 的專案已遭刪除.";
            $this->replyWithJSON($response);
            return;
        }
        if ($result[0]["status"]=="working") {
            $response["status"] = "fail";
            $response["errorMessage"] = "系統正在執行專案代碼為 ".$project_id." 的專案排程.";
            $response["errorMessage"] .= "請稍後再試.";
            $this->replyWithJSON($response);
            return;
        }
        $this->db->delete('project', array('project_id'=>$project_id));
        $this->db->delete('schedule_group', array('member'=>$project_id));
        $this->logModel->logDeleteProject($project_id);
        $this->replyWithJSON($response);
    }
    /**
     * Projects modifyProject
     *
     * This function will check if the arguments in the Post body can be used
     * to modify a project in the database. If not, then reply to the user
     * agent with an error message; otherwise, modify the project in the
     * database and log the action.
     * This function will directly reply to the user agent, using a JSON object
     * with the following keys :
     * status = fail | success
     * errorMessage = '' | `error that occurred`
     *
     * @return none.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function modifyProject()
    {
        $modData = $this->input->post('modifydata');
        $project_id = $modData['project_id'];
        unset($modData['project_id']);
        /*
        $type = $this->input->post('type');
        $name = $this->input->post('name');
        $url = $this->input->post('url');
        $platform = $this->getPlatformFromURL($url);
        $year = $this->input->post('year');
        $leader = $this->input->post('leader');
        */
        $response = array('status' => 'success', 'errorMessage' => '');
        $errMsg = '';
        foreach ($modData as $key => $val) {
            if ($val=='') {
                $attributeName = '';
                switch ($key) {
                    case 'name':
                        $attributeName = '專案名稱';
                        break;
                    case 'year':
                        $attributeName = '專案年度';
                        break;
                    case 'leader':
                        $attributeName = '專案主持人';
                        break;
                    case 'type':
                        $attributeName = '專案類型';
                        break;
                    case 'url':
                        $attributeName = '專案網址';
                        break;
                }
                $errMsg .= $attributeName.'不可為空'.PHP_EOL;
            }
        }
        //check if the modify data does not contain any modified keys, if so throw error
        if (count($modData) === 0) {
            $response["status"] = "fail";
            $response["errorMessage"] = "你沒有修改任何欄位，請至少修改一個欄位";
            $this->replyWithJSON($response);
            return;
        }
        if (array_key_exists('url', $modData)) {
            $platform = $this->getPlatformFromURL($modData['url']);
            if ($platform=='') {
                $errMsg .='無法辨認專案網址平台'.PHP_EOL;
            }
        }
        if ($errMsg!='') {
            $response["status"] = "fail";
            $response["errorMessage"] = $errMsg;
            $this->replyWithJSON($response);
            return;
        }
        $query = $this->db->get_where('project', array('project_id'=>$project_id));
        $result = $query->result_array();
        if (sizeof($result)==0) {
            $response["status"] = "fail";
            $response["errorMessage"] = "系統中專案代碼為 ".$project_id." 的專案已遭刪除.";
            $this->replyWithJSON($response);
            return;
        }
        if ($result[0]["status"]=="working") {
            $response["status"] = "fail";
            $response["errorMessage"] = "系統正在執行專案代碼為 ".$project_id." 的專案排程.";
            $response["errorMessage"] .= "請稍後再試.";
            $this->replyWithJSON($response);
            return;
        }
        $updateResult = $this->db->update(
            'project',
            $modData,
            array('project_id'=>$project_id)
        );
        if ($updateResult) {
            $q = $this->db->get_where('project', array('project_id' => $project_id));
            $r = $q->result_array();
            $proj = $r[0];
            $this->logModel->logUpdateProject(
                $proj
            );
            $this->replyWithJSON($response);
        } else {
            $response["status"] = "fail";
            $response["errorMessage"] = "資料庫錯誤，無法更新資料";
            $this->replyWithJSON($response);
        }
    }
    /**
     * Projects getGenericData
     *
     * This function will return all the projects in the database as an array
     * and send it to the user agent with JSON encoding.
     *
     * @return none.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function getGenericData()
    {
        $query = $this->db->get('project');
        $result = $query->result_array();
        $this->replyWithJSON($result);
    }
    public function getIds()
    {
        $output = array();
        $this->db->select('project_id');
        $query = $this->db->get('project');
        $result = $query->result_array();
        foreach ($result as $id) {
            $output[]=$id['project_id'];
        }
        $this->replyWithJSON($output);
    }
    /**
     * Projects getWikiGraphData
     *
     * This function will reply all the data needed to plot a wiki graph for
     * the project with the `project_id` contained in the post body as a JSON
     * array.
     *
     * @return none.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function getWikiGraphData()
    {
        $project_id = $this->input->post('project_id');
        $this->logModel->logQuery($project_id, 'wiki');
        $query = $this->db->get_where('wiki', array('project_id'=>$project_id));
        $result = $query->result_array();
        $this->replyWithJSON($result);
    }
    /**
     * Projects getWikiGraphSingleThreadData
     *
     * This function will reply all the data needed to plot wiki single thread
     * graph for the project with the `project_id` contained in the post body
     * as a JSON
     * array.
     *
     * @return none.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function getWikiGraphSingleThreadData()
    {
        $project_id = $this->input->post('project_id');
        $query = $this->db->get_where('wiki_page', array('project_id'=>$project_id));
        $result = $query->result_array();
        $this->replyWithJSON($result);
    }
    /**
     * Projects getVCSGraphData
     *
     * This function will reply all the data needed to plot a vcs graph for
     * the project with the `project_id` contained in the post body as a JSON
     * array.
     *
     * @return none.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function getVCSGraphData()
    {
        $project_id = $this->input->post('project_id');
        $this->logModel->logQuery($project_id, 'vcs');
        $query = $this->db->get_where('vcs', array('project_id'=>$project_id));
        $result = $query->result_array();
        $this->replyWithJSON($result);
    }
    /**
     * Projects getVCSCommiterGraphData
     *
     * This function will reply all the data needed to plot vcs commiter
     * graph for the project with the `project_id` contained in the post body
     * as a JSON array.
     *
     * @return none.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function getVCSCommiterGraphData()
    {
        $project_id = $this->input->post('project_id');
        $query = $this->db->get_where('vcs_commiter', array('project_id'=>$project_id));
        $result = $query->result_array();
        $this->replyWithJSON($result);
    }
    /**
     * Projects getIssueTrackerGraphData
     *
     * This function will reply all the data needed to plot issue tracker
     * graph for the project with the `project_id` contained in the post body
     * as a JSON array.
     *
     * @return none.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function getIssueTrackerGraphData()
    {
        $project_id = $this->input->post('project_id');
        $this->logModel->logQuery($project_id, 'issuetracker');
        $query = $this->db->get_where('issue', array('project_id'=>$project_id));
        $result = $query->result_array();
        $this->replyWithJSON($result);
    }
    /**
     * Projects getDownloadGraphData
     *
     * This function will reply all the data needed to plot download
     * graph for the project with the `project_id` contained in the post body
     * as a JSON array.
     *
     * @return none.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function getDownloadGraphData()
    {
        $project_id = $this->input->post('project_id');
        $this->logModel->logQuery($project_id, 'download');
        $query = $this->db->get_where('download', array('project_id'=>$project_id));
        $result = $query->result_array();
        $map = array();
        foreach ($result as $value) {
            $key = $value['timestamp'];
            if (array_key_exists($key, $map)) {
                $map[$key]['totaldownloads']+=intval($value['count']);
                $map[$key]['totalfiles']+=1;
            } else {
                $map[$key]= array(
                    'totaldownloads' => intval($value['count']),
                    'totalfiles' => 1
                );
            }
        }
        $response = array();
        foreach ($map as $key => $value) {
            $response[] = array(
                'timestamp' => $key,
                'totaldownloads' => $value['totaldownloads'],
                'totalfiles' => $value['totalfiles']
            );
        }
        $this->replyWithJSON($response);
    }
    /**
     * Projects getDownloadGraphData
     *
     * This function will reply all the data needed to plot download
     * graph of a single file for the project with the `project_id`
     * contained in the post body as a JSON array.
     *
     * @return none.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function getDownloadSingleFileGraphData()
    {
        $project_id = $this->input->post('project_id');
        $query = $this->db->get_where('download', array('project_id'=>$project_id));
        $result = $query->result_array();
        $this->replyWithJSON($result);
    }
    /**
     * Projects getProxyGraphData
     *
     * This function will reply all the data needed to plot proxy
     * graph for the project with the `project_id`
     * contained in the post body as a JSON array.
     *
     * @return none.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function getProxyGraphData()
    {
        $project_id = $this->input->post('project_id');
        $this->logModel->logQuery($project_id, 'proxy');
        $this->db->order_by('endtime', 'asc');
        $query = $this->db->get_where('crawl_status', array('project_id'=>$project_id));
        $result = $query->result_array();
        $map = array();
        foreach ($result as $value) {
            if (($value['status']=='')||($value['status']=='no_proxy')) {
                continue;
            }
            $ip = $value['proxy_ip'];
            $day = $this->stripTime($value['endtime']);
            if (!array_key_exists($ip, $map)) {
                $map[$ip] = array();
            }
            if (!array_key_exists($day, $map[$ip])) {
                $map[$ip][$day] = array(
                    'all_success' => 0,
                    'success_update' => 0, 'no_change' => 0, 'fail' => 0
                );
            }
            switch ($value['status']) {
                case 'success_update':
                    $map[$ip][$day]['success_update']+=1;
                    $map[$ip][$day]['all_success']+=1;
                    break;
                case 'no_change':
                    $map[$ip][$day]['no_change']+=1;
                    $map[$ip][$day]['all_success']+=1;
                    break;
                case 'fail':
                    $map[$ip][$day]['fail']+=1;
                    break;
            }
        }
        $response = array();
        foreach ($map as $ip => $value) {
            $response[$ip] = array();
            foreach ($value as $day => $counts) {
                $response[$ip][] = array(
                    'day' => $day, 'all_success' => $counts['all_success'],
                    'success_update' => $counts['success_update'],
                    'no_change' => $counts['no_change'],
                    'fail' => $counts['fail']
                );
            }
        }
        $this->replyWithJSON($response);
    }
    /**
     * Projects initProxyArray
     *
     * This private function will initialize an proxy graph data array element
     *
     * @return none.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    private function initProxyArray()
    {
        return array(
            'all_success' => 0, 'success_update' => 0, 'no_change' => 0,
            'all_fail' => 0, 'cannot_get_data' => 0, 'can_not_resolve' => 0,
            'no_proxy' => 0, 'proxy_error' => 0
        );
    }
    /**
     * Projects updateStatusMap
     *
     * This private function will calculate the all_fail and all_success
     * fields of a status map element.
     *
     * @return none.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    private function updateStatusMap($fieldName, &$row, &$mapDay)
    {
        if ($row['status'] == 'no_proxy') {
            $mapDay[$fieldName]['all_fail']+=1;
            $mapDay[$fieldName]['no_proxy']+=1;
            return;
        }
        switch ($row[$fieldName]) {
            case 'success_update':
                $mapDay[$fieldName]['success_update']+=1;
                $mapDay[$fieldName]['all_success']+=1;
                break;
            case 'no_change':
                $mapDay[$fieldName]['no_change']+=1;
                $mapDay[$fieldName]['all_success']+=1;
                break;
            case 'cannot_get_data':
                $mapDay[$fieldName]['all_fail']+=1;
                $mapDay[$fieldName]['cannot_get_data']+=1;
                break;
            case 'can_not_resolve':
                $mapDay[$fieldName]['all_fail']+=1;
                $mapDay[$fieldName]['can_not_resolve']+=1;
                break;
            case 'proxy_error':
                $mapDay[$fieldName]['all_fail']+=1;
                $mapDay[$fieldName]['proxy_error']+=1;
                break;
        }
    }
    /**
     * Projects getStatusGraphData
     *
     * This function will reply all the data needed to plot status
     * graph for the project with the `project_id`
     * contained in the post body as a JSON array.
     *
     * @return none.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function getStatusGraphData()
    {
        $project_id = $this->input->post('project_id');
        $this->logModel->logQuery($project_id, 'status');
        $this->db->order_by('endtime', 'asc');
        $query = $this->db->get_where('crawl_status', array('project_id'=>$project_id));
        $result = $query->result_array();
        $map = array();
        foreach ($result as $value) {
            $day = $this->stripTime($value['endtime']);
            if (!array_key_exists($day, $map)) {
                $map[$day] = array(
                    'vcs'=> $this->initProxyArray(),
                    'wiki' => $this->initProxyArray(),
                    'download' => $this->initProxyArray(),
                    'issue' => $this->initProxyArray()
                );
            }
            $this->updateStatusMap('vcs', $value, $map[$day]);
            $this->updateStatusMap('wiki', $value, $map[$day]);
            $this->updateStatusMap('download', $value, $map[$day]);
            $this->updateStatusMap('issue', $value, $map[$day]);
        }
        $this->replyWithJSON($map);
    }
    /**
     * Projects getProjectModificationHistory
     *
     * This function will reply all the modification history of the project
     * with the `project_id` contained in the post body as a JSON array.
     * The structure of the JSON object is :
     * status = error | fail
     * errorMessage = '' | `error that occurred`
     * history = `the modification history`
     *
     * @return none.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function getProjectModificationHistory()
    {
        $project_id = $this->input->post('project_id');
        $output = array(
            'status'=>'success',
            'history'=>
            $this->logModel->getProjectModificationHistory($project_id),
            'errorMessage'=>''
        );
        if (!$output['history']) {
            $output['status'] = 'fail';
            $output['errorMessage'] = '找不到此專案之歷史紀錄';
        }
        $this->replyWithJSON($output);
    }
    /**
     * Projects getValidProjectTypes
     *
     * This function will reply all the valid project types in the database
     * to the user agent.
     *
     * @return none.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function getValidProjectTypes()
    {
        $this->replyWithJSON($this->projectModel->getValidProjectTypes());
    }
    /**
     * Projects getValidProjectYears
     *
     * This function will reply all the valid project years in the database
     * to the user agent.
     *
     * @return none.
     *
     * @author Kai Yuen <keeperkai@msn.com>
     * @version 1.0
     */
    public function getValidProjectYears()
    {
        $this->replyWithJSON($this->projectModel->getValidProjectYears());
    }
}
