<?php


namespace App\Models\UserManagement;


use Illuminate\Database\Eloquent\Model;

class Home_Member extends Model
{
    protected $table = 'household_member';

    protected $fillable = ['users_id', 'household_id', 'owner'];

    public $timestamps = false;

    /*public function User()
    {
        return $this->HasOne('App\Models\User', 'id_Registered_user', 'id_Registered_user');
    }
*/
    public function Household()
    {
        return $this->BelongsTo('App\Models\HouseholdResource\Home', 'household_id', 'id_Home');
    }

    public function Favorite_Recipe()
    {
        return $this->HasMany('App\Models\Recipes\Favorite_Recipe', 'fk_Home_member', 'id_Registered_user');
    }

    public function Time()
    {
        return $this->HasMany('App\Models\UserManagement\Time', 'fk_Home_member', 'id_Registered_user');
    }

    public function Permissions()
    {
        return $this->HasMany('App\Models\UserManagement\Permissions', 'fk_Home_member', 'id_Registered_user');
    }

    public function User_Sale_Offer()
    {
        return $this->HasMany('App\Models\HouseholdResource\User_Sale_Offer', 'fk_Home_member', 'id_Registered_user');
    }

}
