<?php


namespace App\Models\RecipeManagement;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Favorite_Recipe extends Model
{
    use SoftDeletes;

    protected $table = 'favorite_recipe';

    protected $fillable = ['fk_Recipe', 'fk_User'];
    protected $primaryKey = 'id_Favorite_recipe';

    public $timestamps = false;

    public function Recipe()
    {
        return $this->BelongsTo('App\Models\RecipeManagement\Recipe', 'fk_Recipe', 'id_Recipe');
    }
    public function Category(){
        return $this->BelongsTo('App\Models\RecipeManagement\Category', 'fk_Category', 'id_Category');
    }
}
