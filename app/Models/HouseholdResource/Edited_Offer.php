<?php


namespace App\Models\HouseholdResource;


use Illuminate\Database\Eloquent\Model;

class Edited_Offer extends Model
{
    protected $table = 'edit_purchase_offer';

    protected $fillable = ['id_individual_offer', 'id_offer', 'amount', 'unit_id'];

    public $timestamps = false;
}
