<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class UsersExport implements FromQuery, WithHeadings, ShouldQueue, WithChunkReading
{
    use Exportable;

    protected $fields;

    public function __construct(array $fields = [])
    {
        $this->fields = $fields;
    }

    public function query()
    {
        return User::select($this->fields);
    }

    public function headings(): array
    {
        return $this->fields;
    }

    public function chunkSize(): int
    {
        return 10; // kamu bisa sesuaikan ini supaya export lebih ringan
    }
}