<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection,WithHeadings
{
    /**
     * 数据列表(对象)
     */
    protected $list;

    /**
     * 表头
     */
    protected $header;

    public function __construct($list, $header)
    {
        $this->list = $list;
        $this->header = $header;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->list;
    }


    public function headings(): array
    {
        return $this->header;
    }
}
