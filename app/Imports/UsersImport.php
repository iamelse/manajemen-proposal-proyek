<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue as ShouldQueueContract;
use Illuminate\Support\Str;

class UsersImport implements ToCollection, WithHeadingRow, WithBatchInserts, WithChunkReading, ShouldQueueContract
{
    use \Maatwebsite\Excel\Concerns\Importable;

    protected $fields;

    public function __construct(array $fields = ['id', 'name', 'email'])
    {
        $this->fields = $fields;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $data = [];

            foreach ($this->fields as $field) {
                if (isset($row[$field])) {
                    $data[$field] = $row[$field];
                }
            }

            if (empty($data['email'])) {
                continue; // skip kalau email kosong
            }

            // Jika id tidak ada atau kosong, generate UUID baru
            if (empty($data['id'])) {
                $data['id'] = (string) Str::uuid();
            }

            // Kondisi updateOrInsert menggunakan id kalau ada, atau email kalau tidak ada
            $condition = ['id' => $data['id']];

            User::updateOrInsert($condition, $data);
        }
    }


    public function batchSize(): int
    {
        return 100; // sesuaikan batch insert size untuk optimasi
    }

    public function chunkSize(): int
    {
        return 100; // sesuaikan chunk size untuk proses import
    }
}