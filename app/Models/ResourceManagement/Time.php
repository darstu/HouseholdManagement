<?php

namespace App\Models\ResourceManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ResourceManagement\Time_options;

class Time extends Model
{
    protected $table = 'times';

    protected $primaryKey='id_Time';

    protected $fillable = ['id_Time', 'fk_Home',
        'Quantity_check_time1',
        'Quantity_check_time2' ,
        'Quantity_check_time3',
        'Quantity_check_time4',
        'Quantity_check_time5' ,
        'Quantity_check_time6',
        'Quantity_check_time7',
        'Expiration_check_time1',
        'Expiration_check_time2' ,
        'Expiration_check_time3' ,
        'Expiration_check_time4' ,
        'Expiration_check_time5' ,
        'Expiration_check_time6' ,
        'Expiration_check_time7'
        ];

    public $timestamps = false;

//    use SoftDeletes;

    public function Time_Option()
    {
        return $this->BelongsTo('App\Models\ResourceManagement\Time_options', 'Quantity_check_time', 'id_time_option');
    }
    public function Time_Option2()
    {
        return $this->BelongsTo('App\Models\ResourceManagement\Time_options', 'Status_check_time', 'id_time_option');
    }

}
