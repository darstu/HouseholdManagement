<?php


namespace App\Models\RecipeManagement;


use Illuminate\Database\Eloquent\Model;

class Dish_type extends Model
{
    protected $table = 'dish_type';

    protected $fillable = ['Name'];
    protected $primaryKey ='id_Dish_type';

    public $timestamps = false;

}
