<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PenjualanPreviewImport implements ToArray, WithHeadingRow
{
    public function array(array $array): void
    {
        // Data Excel akan diproses oleh Excel::toArray()
    }
}
