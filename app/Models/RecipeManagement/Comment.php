<?php


namespace App\Models\RecipeManagement;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $table = 'comment';

    protected $fillable = ['Text', 'Date_created', 'fk_User', 'fk_Recipe', 'fk_Main_Comment'];

    public $timestamps = false;

    protected $primaryKey = 'id_Comment';
    public function getDateCreatedAttribute($value)
    {
        return $this->asDateTime($value)->format('Y-m-d H:i');
    }

    public function User()
    {
        return $this->BelongsTo('App\Models\User', 'fk_User',
            'id');
    }

    public function Reply()
    {
        return $this->HasMany('App\Models\RecipeManagement\Comment', 'fk_Main_Comment', 'id_Comment');
    }
}
