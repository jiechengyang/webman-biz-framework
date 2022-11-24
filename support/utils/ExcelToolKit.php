<?php


namespace support\utils;


use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Exception;

class ExcelToolKit
{
    /**
     *
     * 导入Excel
     * @param $filePath
     * @param array $cols
     * @param int $startRow
     * @param int $sheetIndex
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public static function import($filePath, $cols = [], $startRow = 2, $sheetIndex = 0)
    {
        if (!is_file($filePath)) {
            throw new \Exception("文件不存在");
        }

        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(TRUE);
        $spreadsheet = $reader->load($filePath);
        $worksheet = $spreadsheet->getSheet($sheetIndex);
        $highestRow = $worksheet->getHighestRow();
        $lines = $highestRow - $startRow;
        if ($lines <= 0) {
            return [];
        }

        $data = [];
        for ($row = $startRow; $row <= $highestRow; $row++) {
            for ($i = 0; $i < count($cols); $i ++){
                $index = $i + 1;
                $key = $cols[$i];
                $data[$row][$key] = $worksheet->getCellByColumnAndRow($index, $row)->getValue();
            }
        }

        return array_values($data);
    }

    /**
     * @param $name
     * @param $titles [['name'=>'姓名'],['gender'=>'性别']]
     * @param array $data
     * @return \PhpOffice\PhpSpreadsheet\Writer\IWriter
     * @throws Exception
     */
    public static function download($name, $titles, $data=[])
    {
        $count = count($titles);  //计算表头数量
        $spreadsheet = new Spreadsheet();
        $styleArray = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER_CONTINUOUS,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];
        $sheet = $spreadsheet->getActiveSheet();
        for ($i = 65; $i < $count + 65; $i++) {     //数字转字母从65开始，循环设置表头
            $sheet->getStyle(strtoupper(chr($i)))->applyFromArray($styleArray);
            $sheet->getCell(strtoupper(chr($i)).'1')->getStyle()->getFont()->setBold(true);
            $index = $i - 65;
            $sheet->setCellValue(strtoupper(chr($i)) . '1',  $titles[$index][key($titles[$index])] );
        }

        foreach ($data as $key => $item) {
            for ($i = 65; $i < $count + 65; $i++) {
                $sheet->setCellValue(strtoupper(chr($i)) . ($key + 2),$item[key($titles[$i - 65])]);
                $spreadsheet->getActiveSheet()->getColumnDimension(strtoupper(chr($i)))->setAutoSize(true);
            }
        }
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $name . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');

        return $writer;
//            $spreadsheet->disconnectWorksheets();
//            unset($spreadsheet);
//            exit;
    }
}