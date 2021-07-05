<?php

namespace App\Models\ResourceManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Time_options extends Model
{
    protected $table = 'time_option';

    protected $primaryKey='id_time_option';

    protected $fillable = ['id_time_option', 'name'];

    public $timestamps = false;
}
