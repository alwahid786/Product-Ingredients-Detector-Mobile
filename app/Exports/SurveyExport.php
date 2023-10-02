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
        $result = UserSurvey::select('user_id','question','answer')->get()->toArray();
        return collect($result);
    }

    public function headings():array{
        return [
            'Device_Id',
            'Question',
            'Answer'
        ];
    }
}
