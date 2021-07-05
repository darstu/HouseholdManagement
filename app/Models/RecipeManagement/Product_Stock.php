<?php


namespace App\Models\RecipeManagement;


use Illuminate\Database\Eloquent\Model;

class Product_Stock extends Model
{
    protected $table = 'product_stock';

    protected $fillable = ['fk_Product', 'fk_Stock_card'];
    protected $primaryKey = 'id_Product_stock';

    public $timestamps = false;

    public function Stock_Card()
    {
        return $this->BelongsTo('App\Models\HouseholdResource\Stock_Card', 'fk_Stock_card', 'id_Stock_card');
    }

}
