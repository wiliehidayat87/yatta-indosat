<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Export_file extends CI_Controller {
 
    function __construct()
    {
        parent::__construct();
        $this->load->helper('html');
        $this->load->model('bonus/bonus_model');
        $this->load->library('PHPExcel/IOFactory');
        $this->load->library('PHPExcel');
    }
 
    function index()
    {
        error_reporting(0);
        if($this->input->post("exportPDF")){
            $this->exportFile('pdf', 'PDF', $from, $until, $adnNumber, $operatorName, $reqType, $serviceName, $msisdnNumber, $msisdnCheckbox, $smsRequest);
            
        }
        elseif($this->input->post("exportXLS")){
            $this->exportFile('xls', 'Excel5', $from, $until, $adnNumber, $operatorName, $reqType, $serviceName, $msisdnNumber, $msisdnCheckbox, $smsRequest);
        }                    
    }
     
    public function exportFile($ext, $type, $from, $until, $adnNumber, $operatorName, $reqType, $serviceName, $msisdnNumber, $msisdnCheckbox, $smsRequest)
    {
        $objPHPExcel = new PHPExcel();
        
        // Set properties
        $objPHPExcel->getProperties()->setCreator('Bonus Tool')
                                     ->setLastModifiedBy("")
                                     ->setTitle("Winner List")
                                     ->setSubject("Winner List For_".date('Y_m_d'))
                                     ->setDescription("")
                                     ->setKeywords("")
                                     ->setCategory("");
         
         
        // Add some data
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'No')
                    ->setCellValue('B1', 'MSISDN');
        
        // Setting width header
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
                
        $getReports = $this->bonus_model->getBONUSWinner();
        //var_dump($getReports);
        $reports = $getReports["result"]["data"];
        //var_dump($reports);
        
        $line = 2;
        foreach($reports as $value)
        {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A".$line++."",$line-2);
        }
         
        $line = 2;
        foreach($reports as $value)
        {
            $detail = $this->bonus_model->getBONUSTrafficByMSISDN($value["msisdn"]);
            foreach ($detail as $key => $dtl) {
			    $row_detail .= "\n".($key+1).". [".$dtl["time_downloaded"]."] ".$dtl["content_url"];
			}
			//var_dump($row_detail);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B".$line++."",$value['msisdn'].$row_detail);
        }
        
        /*foreach($objPHPExcel->getActiveSheet()->getRowDimensions() as $rd) { 
            $rd->setRowHeight(-1); 
        }*/
                
        $objPHPExcel->getActiveSheet()->setTitle("BONUS Winner_".date('Y_m_d'));
         
        $objPHPExcel->setActiveSheetIndex(0);
        
        $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray(
            array(
                    'font'    => array(
                            'bold'      => true
                    ),
                    'alignment' => array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    ),
                    'borders' => array(
                            'bottom'     => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                            )
                    ),
                    'fill' => array(
                            'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                            'rotation'   => 90,
                            'startcolor' => array(
                                    'argb' => '000FFF'
                            ),
                            'endcolor'   => array(
                                    'argb' => 'FFFFFF'
                            )
                    )
            )
        );
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="bonus_winner_'.date('Y_m_d').'.'.$ext.'"');
        header('Cache-Control: max-age=0');
        
        // Set Orientation, size and scaling
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
        
        $objWriter = IOFactory::createWriter($objPHPExcel, $type);
        $objWriter->save('php://output');
         
    }        
}
