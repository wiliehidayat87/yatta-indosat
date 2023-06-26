<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Export_file extends CI_Controller {
 
    function __construct()
    {
        parent::__construct();
        $this->load->helper('html');
        $this->load->model('traffic/mo_traffic_model');
        $this->load->library('PHPExcel/IOFactory');
        $this->load->library('PHPExcel');
    }
 
    function index()
    {
        $from           = $this->input->post("exportFromDate");
        $until          = $this->input->post("exportUntilDate");
        $adnNumber      = $this->input->post("exportADN");
        $operatorName   = $this->input->post("exportOperator");
        $reqType        = $this->input->post("exportType");
        $serviceName    = $this->input->post("exportService");
        $msisdnNumber   = $this->input->post("exportMSISDN");
        $msisdnCheckbox = $this->input->post("exportMSISDNCheck");
        $smsRequest     = $this->input->post("exportSMS");
 
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
        $objPHPExcel->getProperties()->setCreator('MO Traffic')
                                     ->setLastModifiedBy("")
                                     ->setTitle("MO Traffic Reports")
                                     ->setSubject("Reports MO Traffic For_".date('Y_m_d'))
                                     ->setDescription("")
                                     ->setKeywords("")
                                     ->setCategory("");
         
         
        // Add some data
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'No')
                    ->setCellValue('B1', 'MO Date')                    
                    ->setCellValue('C1', 'Operator')
                    ->setCellValue('D1', 'ADN')
                    ->setCellValue('E1', 'MSISDN')
                    ->setCellValue('F1', 'Service')
                    ->setCellValue('G1', 'Type')
                    ->setCellValue('H1', 'SMS Request');
        
        // Setting width header
        $sheet = $objPHPExcel->getActiveSheet();
                $sheet->getColumnDimension('A')->setAutoSize(true);
                $sheet->getColumnDimension('B')->setAutoSize(true);
                $sheet->getColumnDimension('C')->setAutoSize(true);
                $sheet->getColumnDimension('D')->setAutoSize(true);
                $sheet->getColumnDimension('E')->setAutoSize(true);
                $sheet->getColumnDimension('F')->setAutoSize(true);
                $sheet->getColumnDimension('G')->setAutoSize(true);
                $sheet->getColumnDimension('H')->setAutoSize(true);
                
        $getReports = $this->mo_traffic_model->dataSummary($from, $until, $adnNumber, $operatorName, $reqType, $serviceName, $msisdnNumber, $msisdnCheckbox, $smsRequest);
        $reports = $getReports->result_array();
        
        $line = 2;
        foreach($reports as $value)
        {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A".$line++."",$value['id']);
        }
         
        $line = 2;
        foreach($reports as $value)
        {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B".$line++."",$value['mo_date']);
        }
        
        $line = 2;
        foreach($reports as $value)
        {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C".$line++."",$value['operator']);
        }
        
        $line = 2;
        foreach($reports as $value)
        {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D".$line++."",$value['adn']);
        }
        
        $line = 2;
        foreach($reports as $value)
        {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E".$line++."",$value['msisdn']);
        }
        
        $line = 2;
        foreach($reports as $value)
        {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F".$line++."",$value['service']);
        }
        
        $line = 2;
        foreach($reports as $value)
        {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G".$line++."",$value['req_type']);
        }
        
        $line = 2;
        foreach($reports as $value)
        {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H".$line++."",$value['sms']);
        }         
         
        $objPHPExcel->getActiveSheet()->setTitle("MO Traffic_".date('Y_m_d'));
         
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
        header('Content-Disposition: attachment;filename="mo_traffic_'.date('Y_m_d').'.'.$ext.'"');
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
