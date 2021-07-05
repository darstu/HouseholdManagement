<?php


namespace App\Models\RecipeManagement;


use Illuminate\Database\Eloquent\Model;

class Diet_Type extends Model
{
    protected $table = 'diet_type';

    protected $fillable = ['Name'];
    protected $primaryKey ='id_Diet_type';

    public $timestamps = false;
}
