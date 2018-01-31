<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Generador_de_archivo extends CI_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->library('Excel');
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
        $sheet->setTitle("RESUMEN");
        $this->excel->setActiveSheetIndex(0)->mergeCells('A1:H1'); // Unir columnas
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->setCellValue('A1', 'RESUMEN DIARIO DE CAJA');
        $sheet->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // ALINEAR EL TEXTO EN LAS CELDAS



        $sheet->setCellValue('B1', 'Apellido');
        $sheet->setCellValue('A2', 'Fecha: Jueves 18 de Enero 2018');

        $sheet->setCellValue('A3', 'EMPRESA:');
        $sheet->setCellValue('A4', 'CAJA/LOCAL:');
        $sheet->setCellValue('F3', 'FECHA:');
        $sheet->setCellValue('F4', 'CAJERO:');

        $sheet->getStyle("A1:H1")->applyFromArray(
                array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        //  'color' => array('rgb' => 'DDDDDD')
                        )
                    )
                )
        );
        $sheet->getStyle("A6:H21")->applyFromArray(
                array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_DASHDOT
                        //  'color' => array('rgb' => 'DDDDDD')
                        )
                    )
                )
        );

        // Salida
        header('Content-Type: application/ vnd.openxlmlformats-officedocument.spreadsheetml.sheet');
        $nombreArchivo = 'export_lisatdo_' . date('YmdHis');
        header("Content-Disposition: attachment; filename=\"$nombreArchivo.xlsx\"");
        header("Cache-Control: max-age=0");
        // Genera Excel
        $writer = PHPExcel_IOFactory::createWriter($this->excel, "Excel2007");
        // Escribir
        $writer->save('php://output');
        exit;
    }

}
