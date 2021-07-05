<?php


namespace App\Models\UserManagement;


use Illuminate\Database\Eloquent\Model;

class User_Permission extends Model
{
    protected $table = 'user_permission';

    protected $fillable = ['fk_user_id', 'fk_permission_id', 'restricted', 'fk_household_id'];

    public $timestamps = false;

}
