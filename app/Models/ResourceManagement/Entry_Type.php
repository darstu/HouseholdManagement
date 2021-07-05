<?php

namespace App\Models\ResourceManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entry_Type extends Model
{
    protected $table = 'entry_type';

    protected $fillable = ['entry_type_id', 'name'];

    public $timestamps = false;

}
