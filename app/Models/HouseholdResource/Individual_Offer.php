<?php


namespace App\Models\HouseholdResource;


use Illuminate\Database\Eloquent\Model;

class Individual_Offer extends Model
{
    protected $table = 'individual_offer';

    protected $fillable = ['user_id', 'offer_id', 'date'];

    public $timestamps = true;

    public function Home_Member()
    {
        return $this->BelongsTo('App\Models\UserManagement\Home_Member', 'fk_Home_member', 'id_Registered_user');
    }

    public function Sale_Offer()
    {
        return $this->BelongsTo('App\Models\HouseholdRecourse\Sale_Offer', 'fk_Sale_offer', 'id_Sale_offer');
    }
}
