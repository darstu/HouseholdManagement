<?php


namespace App\Models\HouseholdResource;


use App\Models\RecipeManagement\Unit;
use Illuminate\Database\Eloquent\Model;

class Sale_offer extends Model
{
    protected $table = 'purchase_offer';

    protected $fillable = ['date', 'fk_household_id', 'fk_stock_card_id', 'amount', 'unit_id', 'want_to_buy', 'buyer'];

    public $timestamps = true;

    public function household()
    {
        return $this->belongsTo(Home::class);
    }

    public function stock_card()
    {
        return $this->belongsTo(Stock_Card::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
