<?php


namespace App\Models\HouseholdResource;


use Illuminate\Database\Eloquent\Model;

class Stock_Card extends Model
{
    protected $table = 'stock_card';

    protected $fillable = ['Name', 'Description', 'fk_Home', 'fk_Stock_type', 'fk_Purchase_offer', 'measurement_unit', 'image', 'removed', 'removed_date'];
    protected $primaryKey= 'id_Stock_card';

    public $timestamps = false;


    public function Stock_Place()
    {
        return $this->HasMany('App\Models\HouseholdRecourse\Stock_Place', 'fk_Stock_Card', 'id_Stock_card');
    }

    public function Sale_Offer()
    {
        return $this->BelongsTo('App\Models\HouseholdRecourse\Sale_Offer', 'fk_Sale_offer', 'id_Sale_offer');
    }
    public function Stock_Type()
    {
        return $this->BelongsTo('App\Models\ResourceManagement\Stock_Type', 'fk_Stock_type', 'id_Stock_type');
    }




}
