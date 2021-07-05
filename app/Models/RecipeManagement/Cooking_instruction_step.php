<?php


namespace App\Models\RecipeManagement;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cooking_Instruction_Step extends Model
{
    use SoftDeletes;

    protected $table = 'cooking_instruction_step';

    protected $fillable = ['Step_Description', 'Step_number', 'Image_address', 'fk_Recipe'];
    protected $primaryKey = 'id_cooking_instruction_step';

    public $timestamps = false;
}
