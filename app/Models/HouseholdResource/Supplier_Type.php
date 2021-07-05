<?php


namespace App\Models\HouseholdResource;


use Illuminate\Database\Eloquent\Model;

class Supplier_Type extends Model
{
    protected $table = 'supplier_type';

    protected $fillable = ['fk_household_id', 'Name', 'Description'];

    public $timestamps = false;

}
