<?php


namespace App\Models\RecipeManagement;


use Illuminate\Database\Eloquent\Model;

class Product_unit_conversion extends Model
{
    protected $table = 'product_unit_conversion';

    protected $fillable = ['Unit_from', 'Unit_to', 'value','fk_Product'];

    protected $primaryKey = 'id_Product_unit_conversion';

}
