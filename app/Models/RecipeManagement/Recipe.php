<?php


namespace App\Models\RecipeManagement;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Recipe extends Model
{
    use SoftDeletes;

    protected $table = 'recipe';

    protected $fillable = ['Name', 'Image_Address', 'Date_created', 'Description', 'Difficulty', 'Cooking_time',
        'Servings_count', 'Author', 'fk_Dish_type','fk_Diet_type', 'fk_User','fk_Main_Recipe', 'Visibility',
        'AbilityToMake','TimeTillExpiry','Message'];

    protected $primaryKey = 'id_Recipe';
    public $timestamps = false;

    public function Recipe_ingredient()
    {
        return $this->HasMany('App\Models\RecipeManagement\Recipe_Ingredient', 'fk_Recipe', 'id_Recipe');
    }

    public function User()
    {
        return $this->BelongsTo('App\Models\User', 'fk_User', 'id');
    }

    public function Comment()
    {
        return $this->HasMany('App\Models\RecipeManagement\Comment', 'fk_Recipe', 'id_Recipe')->whereNull(
            'fk_Main_Comment');
    }

    public function Favorites()
    {
        return $this->HasMany('App\Models\RecipeManagement\Favorite_Recipe', 'fk_Recipe', 'id_Recipe')->where('fk_User', Auth::id());
    }

    public function Dish_type()
    {
        return $this->BelongsTo('App\Models\RecipeManagement\Dish_type', 'fk_Dish_type', 'id_Dish_type');
    }
    public function Diet_type()
    {
        return $this->BelongsTo('App\Models\RecipeManagement\Diet_Type', 'fk_Diet_type', 'id_Diet_type');
    }

    public function Cooking_instruction_step()
    {
        return $this->HasMany('App\Models\RecipeManagement\Cooking_Instruction_Step', 'fk_Recipe', 'id_Recipe');
    }

    public function Source()
    {
        return $this->HasOne('App\Models\RecipeManagement\Source', 'fk_Recipe', 'id_Recipe');
    }

    public function Rating()
    {
        return $this->HasMany('App\Models\RecipeManagement\Rating', 'fk_Recipe', 'id_Recipe');
    }
    public function Main(){
        return $this->BelongsTo('App\Models\RecipeManagement\Recipe', 'fk_Main_Recipe', 'id_Recipe');
    }
}
