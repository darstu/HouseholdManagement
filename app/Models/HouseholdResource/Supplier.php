<?php


namespace App\Models\HouseholdResource;


use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'supplier';

    protected $fillable = ['fk_household_id', 'fk_type_id', 'Name', 'Address', 'City', 'Phone', 'removed', 'removed_date','supplier_id'];

    public $timestamps = false;

}
