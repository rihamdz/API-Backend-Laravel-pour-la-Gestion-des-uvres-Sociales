<?php

namespace App\Imports;
use App\Models\Employee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeImport implements ToCollection,WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            Employee::create([
                'full_name' => $row['full_name'], 
                'email' => $row['email'],
                'phone' => $row['phone'],
                'salary' =>$row['salary']

            ]);
        }
    }
}
