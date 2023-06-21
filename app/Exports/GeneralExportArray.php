<?php

namespace App\Exports;

use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GeneralExportArray implements FromArray, WithHeadings, Responsable
{
    use Exportable;

    private $heading, $rows, $fileName, $writerType;

    public function __construct($header, $data, $filename, $writer)
    {
        $this->heading = $header;
        $this->rows = $data;
        $this->fileName = $filename;
        $this->writerType = $writer;
    }
    /**
    * @return array
    */
    public function array(): array
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return $this->heading;
    }
}
