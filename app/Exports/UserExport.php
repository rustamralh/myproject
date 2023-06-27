<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserExport implements FromCollection, WithHeadings
{
    use Exportable;

    private array $headers;
    private array $mappings;
    public function __construct(array $headers, array $mappings)
    {
        $this->mappings = $mappings;
        $this->headers = $headers;
    }

    public function headings(): array
    {
        return $this->headers;
    }


    public function map($user): array
    {
        $mappings = collect();

        foreach ($this->mappings as $mapping) {
            $mappings->push($user->$mapping);
        }

        return $mappings->toArray();
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::all();
    }
}
