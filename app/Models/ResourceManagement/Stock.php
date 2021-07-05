<?php

namespace App\Models\ResourceManagement;

use App\Models\HouseholdResource\Stock_Card;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stock';

    protected $fillable = ['stock_id', 'quantity', 'fk_Batch', 'expiration_date','fk_Home', 'fk_Stock_card', 'fk_Warehouse_place','fk_Entry_type',	'posting_date'];

    protected $primaryKey='stock_id';

    public $timestamps = false;

    public function Stock_Card()
    {
        return $this->BelongsTo('App\Models\HouseholdResource\Stock_Card', 'fk_Stock_card', 'id_Stock_card');
    }
    public function Stock_Batch()
    {
        return $this->BelongsTo('App\Models\ResourceManagement\Batch', 'fk_Batch', 'id_Stock_batch');
    }
    public function Entry_Type()
    {
        return $this->BelongsTo('App\Models\ResourceManagement\Entry_Type', 'fk_Entry_type', 'entry_type_id');
    }
    public function Warehouse_Place()
    {
        return $this->BelongsTo('App\Models\ResourceManagement\Warehouse_place', 'fk_Warehouse_place', 'id_Warehouse_place');
    }

//    public function Quantity()
//    {
//    }

//    public function Stock_Place()
//    {
//        return $this->HasMany('App\Models\HouseholdRecourse\Stock_Place', 'fk_Stock_Card', 'id_Stock_card');
//    }
//
//    public function Sale_Offer()
//    {
//        return $this->BelongsTo('App\Models\HouseholdRecourse\Sale_Offer', 'fk_Sale_offer', 'id_Sale_offer');
//    }
}
