<?php


namespace App\Models\RecipeManagement;


use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';

    protected $fillable = ['Name'];
    protected $primaryKey = 'id_Product';

    public $timestamps = false;
    public function Conversion()
    {
        return $this->HasOne('App\Models\RecipeManagement\Product_unit_conversion', 'fk_Product', 'id_Product');
    }
}
