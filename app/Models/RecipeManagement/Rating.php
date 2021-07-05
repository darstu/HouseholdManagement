<?php


namespace App\Models\RecipeManagement;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rating extends Model
{
    use SoftDeletes;

    protected $table = 'review';

    protected $fillable = ['Rating', 'Headline','Feedback', 'Image_address', 'Date_created', 'fk_Recipe', 'fk_User'];
    protected $primaryKey = 'id_Rating';
    public $timestamps = false;

    public function User()
    {
        return $this->BelongsTo('App\Models\User', 'fk_User', 'id');
    }
}
