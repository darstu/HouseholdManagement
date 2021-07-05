<?php


namespace App\Models\UserManagement;


use Illuminate\Database\Eloquent\Model;

class Locked_Warehouses extends Model
{
    protected $table = 'locked_warehouse';

    protected $fillable = ['user_id', 'household_id', 'warehouse_id'];

    public $timestamps = false;

}
