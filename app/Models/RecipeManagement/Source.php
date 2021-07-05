<?php


namespace App\Models\RecipeManagement;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Source extends model
{
    use SoftDeletes;

    protected $table = 'source';

    protected $fillable = ['Name', 'Address', 'fk_Recipe'];
    protected $primaryKey = 'id_Source';
    public $timestamps = false;
}
