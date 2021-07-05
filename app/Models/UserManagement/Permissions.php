<?php


namespace App\Models\UserManagement;


use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
    protected $table = 'permissions';

    protected $fillable = ['name'];

    public $timestamps = false;

}
