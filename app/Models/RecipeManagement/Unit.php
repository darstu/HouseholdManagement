<?php


namespace App\Models\RecipeManagement;


use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'unit';

    protected $fillable = ['Name'];
    protected $primaryKey = 'id_Unit';

    public $timestamps = false;
}
