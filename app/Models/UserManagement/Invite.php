<?php


namespace App\Models\UserManagement;


use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    protected $table = 'invite';

    protected $fillable = ['fk_household_id', 'fk_sender_id', 'fk_receiver_id', 'Message'];

    public $timestamps = false;

}
