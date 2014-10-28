<?php

class Wisecamera_Export extends Wisecamera_CheckUser
{

    public function setData()
    {
               // error_reporting(E_ALL);
               // ini_set('display_errors', 1);
        //error_reporting(0);
                header("Content-type: application/json");
                $json = $this->input->post('content');

        $this->load->library('phpexcel/Phpexcel');
        $this->load->library('phpexcel/PHPExcel/IOFactory');
        $filterArray = $json["filter"];
        $data = $json["data"];

        $this->phpexcel->setActiveSheetIndex(0);

        $this->phpexcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "Filter:");
        $this->phpexcel->getActiveSheet()->setCellValueByColumnAndRow(0, 2, "年度");
        $this->phpexcel->getActiveSheet()->setCellValueByColumnAndRow(1, 2, $filterArray["year"]);
        $this->phpexcel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, "類別");
        $this->phpexcel->getActiveSheet()->setCellValueByColumnAndRow(1, 3, $filterArray["type"]);
        $this->phpexcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, "代碼");
        $this->phpexcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, $filterArray["codeName"]);
        $this->phpexcel->getActiveSheet()->setCellValueByColumnAndRow(0, 5, "專案");
        $this->phpexcel->getActiveSheet()->setCellValueByColumnAndRow(1, 5, $filterArray["projectName"]);
        $this->phpexcel->getActiveSheet()->setCellValueByColumnAndRow(0, 6, "平台");
        $this->phpexcel->getActiveSheet()->setCellValueByColumnAndRow(1, 6, $filterArray["platform"]);

        for ($i=0; $i<sizeof($data); $i++) {
            for ($j=0; $j<sizeof($data[0]); $j++) {
                $this->phpexcel->getActiveSheet()->setCellValueByColumnAndRow($j, $i+8, $data[$i][$j]);

            }
        }

        $objWriter = $this->iofactory->createWriter($this->phpexcel, 'Excel2007');

        $time = time();
        $filename = "export_".$this->session->userdata('ACCOUNT')."_".$time.".xlsx";
        $filename = str_replace('@', '_', $filename);
        $objWriter->save($filename);

        echo json_encode($arr = array('filename' => $filename));

    }

    public function getFile($filename)
    {
        $file_url = base_url().$filename;
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\"");
        readfile($file_url); // do the double-download-dance (dirty but worky)
        unlink($filename);

    }
}
