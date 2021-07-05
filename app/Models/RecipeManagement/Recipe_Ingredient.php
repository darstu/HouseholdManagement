<?php


namespace App\Models\RecipeManagement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recipe_Ingredient extends Model
{
    use SoftDeletes;

    protected $table = 'recipe_ingredient';

    protected $fillable = ['fk_Unit', 'Amount', 'fk_Product', 'fk_Recipe'];
    protected $primaryKey = 'id_Recipe_ingredient';
    public $timestamps = false;

    public function Product()
    {
        return $this->belongsTo('App\Models\RecipeManagement\Product', 'fk_Product', 'id_Product');
    }

    public function Unit()
    {
        return $this->belongsTo('App\Models\RecipeManagement\Unit', 'fk_Unit', 'id_Unit');
    }
}
