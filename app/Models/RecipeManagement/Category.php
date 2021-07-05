<?php


namespace App\Models\RecipeManagement;


use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    protected $fillable = ['Name','fk_User'];
    protected $primaryKey = 'id_Category';
    public $timestamps = false;
}

