<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeeExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        // chuyển dữ liệu từ Collection hoặc Eloquent về  map => mảng mới là có thể áp dụng Accesstor
        return $this->data->map(function ($employee) {
            return [
                'ID' => $employee->id,
                'FullName' => $employee->full_name,
                'Email' => $employee->email,
                'Position'=> $employee->position,
                'Status'=> $employee->status,
                'Gender'=> $employee->gender,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'FullName',
            'Email',
            'Position',
            'Status',
            'Gender',
        ];
    }
}
