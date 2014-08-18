<?php


include "Classes/PHPExcel.php";
require_once "Classes/PHPExcel.php";
require_once "Classes/PHPExcel/IOFactory.php";


class Wisecamera_Export extends Wisecamera_CheckUser 
{


	public function setData()
	{

		header("Content-type: application/json");
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		$json = $this->input->post('content');
//		$json = '{"filter":{"platform":"github,openfoundry","projectName":"Android Sliding Up Panel Demo","codeName":"gh.umano.AndroidSlidingUpPanel","type":"VCS,network,...","year":"102,103"},"data":[[["zz","yy","xx"]],[["zz","yy","xx"]]]}';


		//$json = '{"filter":{"year":"102,103","platform":"github, openfoundry","projectName":"Android Sliding Up Panel Demo","codeName":"gh.umano.AndroSlidingUpPanel","type":"VCS, network"},"data":[["zz","yy","xx"],["qq","yy","xx"]]}';


//		var_dump($json);
//		var_dump(json_decode($json));
		//var_dump(json_decode($json, true));

//		$jsonArrayTemp = json_decode($json, true);
		$filterArray = $json["filter"];
		$data = $json["data"];


//		var_dump($data);
//		var_dump($filterArray);



		// phpinfo();
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,1,"Filter:");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,2,"年度");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,2,$filterArray["year"]);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,3,"類別");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,3,$filterArray["type"]);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,4,"代碼");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,4,$filterArray["codeName"]);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,5,"專案");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,5,$filterArray["projectName"]);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,6,"平台");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,6,$filterArray["platform"]);



			
		for($i=0; $i<sizeof($data);$i++){
			for($j=0;$j<sizeof($data[0]);$j++){
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($j,$i+8,$data[$i][$j]);

			}
		}


		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

		$time = time();
		$filename = "export_".$this->session->userdata('ACCOUNT')."_".$time.".xlsx";
		$objWriter->save($filename);
	
		echo json_encode($arr=array('filename'=>$filename));


	}


	public function getFile($filename){

		$file_url = base_url().$filename;
		header('Content-Type: application/octet-stream');
		header("Content-Transfer-Encoding: Binary"); 
		header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\""); 
		readfile($file_url); // do the double-download-dance (dirty but worky)
		unlink($filename);	



	}

}

