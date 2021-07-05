<?php


namespace App\Models\UserManagement;


class Time
{
    protected $table = 'time';

    protected $fillable = ['id_Time', 'Status_check_time', 'Amount_check_time', 'Sale_offer_time', 'fk_Home_member'];

    public $timestamps = false;
}
