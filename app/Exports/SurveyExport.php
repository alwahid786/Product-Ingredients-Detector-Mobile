<?php

namespace App\Exports;

use App\Models\UserSurvey;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class SurveyExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $users = User::get();
        return collect($users);
    }

    public function headings():array{
        return [
            'Device_Id',
            'Question',
            'Answer',
            'Answer',
            'Answer',
            'Answer',
            'Answer',
            'Answer',
            'Answer',
        ];
    }
}
