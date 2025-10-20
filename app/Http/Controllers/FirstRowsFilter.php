<?php

namespace App\Http\Controllers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class FirstRowsFilter implements IReadFilter
{
    public function readCell($column, $row, $worksheetName = '')
    {
        // Read only first 5 rows
        return $row <= 5;
    }
}
