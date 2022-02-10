<?php

namespace App\Imports;

use App\Models\TeleSale;
use Maatwebsite\Excel\Concerns\ToModel;

use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TeleSaleImport implements ToModel, WithHeadingRow
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $teleSale = [];

        if (isset($row['phone']) && $row['phone'] !== null) {

            $teleSale['phone'] = $row['phone'];

            if (isset($row['full_name']) && $row['full_name'] !== null) $teleSale['full_name'] = $row['full_name'];

            if (isset($row['email']) && $row['email'] !== null) $teleSale['email'] = $row['email'];

            if (isset($row['dob']) && $row['dob'] !== null) $teleSale['dob'] = date('Y-m-d', strtotime($row['dob']));

            if (count($teleSale) > 0) {

                return new TeleSale($teleSale);
            }

        }

    }

    // public function rules(): array
    // {
    //     return [
    //         'phone' => ['required']
    //     ];
    // }
}
