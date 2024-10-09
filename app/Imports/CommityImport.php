<?php

namespace App\Imports;
use App\Models\Commity;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class CommityImport implements ToCollection,WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            Commity::create([
                'full_name' => $row['full_name'], 
                'email' => $row['email'],
                'phone' => $row['phone'],
                'salary' =>$row['salary'],
                'type' =>$row['type'],


            ]);
        }
    }
}
