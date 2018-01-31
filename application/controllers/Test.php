<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Test extends CI_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->library('Excel');
    }

    public function exportarExcel() {
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Informe');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Celda1');
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(10);

        $filename = "nombre.xls"; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
    
  
        exit;
        
    }
    public function excel() {
        // Load libreria
        $this->load->library('excel');
        // Propiedades del archivo excel
        $this->excel->getProperties()
                ->setTitle("Esto es una prueba")
                ->setDescription("Descripcion del excel bla bla blaaa");
        // Setiar la solapa que queda actia al abrir el excel
        $this->excel->setActiveSheetIndex(0);
        // Solapa excel para trabajar con PHP
        $sheet = $this->excel->getActiveSheet();
        $sheet->setTitle("Titulo Demo Pestaña");
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->setCellValue('A1', 'Nombre');
        $sheet->setCellValue('B1', 'Apellido');
        $sheet->setCellValue('A2', 'Pepe Luis');
        $sheet->setCellValue('B2', 'Gomez');
        $sheet->setCellValue('A3', 'Alejandro');
        $sheet->setCellValue('B3', 'Mandre');
        // Salida
        header("Content-Type: application/vnd.ms-excel");
        $nombreArchivo = 'export_lisatdo_'.date('YmdHis');
        header("Content-Disposition: attachment; filename=\"$nombreArchivo.xls\"");
        header("Cache-Control: max-age=0");
        // Genera Excel
        $writer = PHPExcel_IOFactory::createWriter($this->excel, "Excel5");
        // Escribir
        $writer->save('php://output');
        exit;
    }
    
       
    public function testing() {
        // Load libreria
        $this->load->library('excel');
        // Propiedades del archivo excel
        $this->excel->getProperties()
                ->setTitle("Esto es una prueba")
                ->setDescription("Descripcion del excel bla bla blaaa");
        // Setiar la solapa que queda actia al abrir el excel
        $this->excel->setActiveSheetIndex(0);
        // Solapa excel para trabajar con PHP
        $sheet = $this->excel->getActiveSheet();
        $sheet->setTitle("Titulo Demo Pestaña");
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->setCellValue('A1', 'Nombre');
        $sheet->setCellValue('B1', 'Apellido');
        $sheet->setCellValue('A2', 'Pepe Luis');
        $sheet->setCellValue('B2', 'Gomez');
        $sheet->setCellValue('A3', 'Alejandro');
        $sheet->setCellValue('B3', 'Mandre');
        // Salida
      header('Content-Type: application/ vnd.openxlmlformats-officedocument.spreadsheetml.sheet');
        $nombreArchivo = 'export_lisatdo_'.date('YmdHis');
        header("Content-Disposition: attachment; filename=\"$nombreArchivo.xlsx\"");
        header("Cache-Control: max-age=0");
        // Genera Excel
        $writer = PHPExcel_IOFactory::createWriter($this->excel, "Excel2007");
        // Escribir
        $writer->save('php://output');
        exit;
    

}
}

/*
 defined('BASEPATH') OR exit('No direct script access allowed');

class Export extends CI_Controller {

    public function excel() {
        require(APPPATH . 'third_party/PHPExcel-1.8/Classes/PHPExcel.php');
        require(APPPATH . 'third_party/PHPExcel-1.8/Classes/PHPExcel/Writer/Excel2007.php');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("");
        $objPHPExcel->getProperties()->setLastModifiedBy("");
        $objPHPExcel->getProperties()->setTitle("");
        $objPHPExcel->getProperties()->setSubject("");
        $objPHPExcel->getProperties()->setDescription("");

        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->SetCellValue("A1", "Nombre");

        $nombre_archivo = "Lucho";

        $objPHPExcel->getActiveSheet()->setTitle("Nose Que Cosa");
 // $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    //    header('Content-Type: application/ vnd.openxlmlformats-officedocument.spreadsheetml.sheet');
       header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $nombre_archivo . '.xls"');
        header('Cache-Control: max-age=0');
      
     //   $writer->save('php//output');
        exit;
    }

    public function hola() {
        echo "HOLA";
    }

}

 */