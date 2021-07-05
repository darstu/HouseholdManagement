<?php


namespace App\Models\HouseholdResource;


use Illuminate\Database\Eloquent\Model;

class New_Product extends Model
{
    protected $table = 'new_product';

    protected $fillable = ['id_stock', 'id_individual_offer', 'amount', 'unit_id'];

    public $timestamps = false;
}
