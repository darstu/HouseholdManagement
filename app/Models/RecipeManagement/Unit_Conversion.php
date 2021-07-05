<?php


namespace App\Models\RecipeManagement;


use Illuminate\Database\Eloquent\Model;

class Unit_Conversion extends Model
{
    protected $table = 'unit_conversion';

    protected $fillable = ['Unit_from', 'Unit_to', 'value'];

    protected $primaryKey = 'id_Unit_conversion';
}
