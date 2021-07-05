<?php

namespace App\Models\ResourceManagement;

use App\Models\HouseholdResource\Home;
use App\Models\HouseholdResource\Stock_Card;
use App\Models\User;
use App\Models\RecipeManagement\Unit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase_Offer extends Model
{
    protected $table = 'purchase_offer';

    protected $primaryKey='offer_id';


    protected $fillable = ['date', 'offer_id','fk_household_id', 'fk_stock_card_id', 'amount', 'want_to_buy', 'buyer', 'fk_Warehouse_place', 'byQuantity','who_added'];

    public $timestamps = false;

    public function household()
    {
        return $this->belongsTo(Home::class);
    }

    public function Stock_Card()
    {
        return $this->BelongsTo('App\Models\HouseholdResource\Stock_Card', 'fk_stock_card_id', 'id_Stock_card');

    }

    public function Warehouse_Place()
    {
        return $this->BelongsTo('App\Models\ResourceManagement\Warehouse_place', 'fk_Warehouse_place', 'id_Warehouse_place');

    }
    public function Buyer()
    {
        return $this->BelongsTo('App\Models\User', 'buyer', 'id');

    }

    public function AddedBy()
    {
        return $this->BelongsTo('App\Models\User', 'who_added', 'id');

    }

}
