<?php


namespace App\Models\ResourceManagement;


use Illuminate\Database\Eloquent\Model;

class Stock_Place extends Model
{
    protected $table = 'stock_place';

    protected $fillable = [ 'Min_amount', 'Max_amount', 'fk_Stock_unit', 'fk_Stock_card', 'fk_Warehouse_place', 'fk_Home'];

    public $timestamps = false;


    public function Stock_Unit()
    {

        return $this->BelongsTo('App\Models\HouseholdRecourse\Stock_Unit', 'fk_Stock_unit', 'id_Stock_unit');

    }
    public function Stock_Card()
    {
        return $this->BelongsTo('App\Models\HouseholdResource\Stock_Card', 'fk_Stock_card', 'id_Stock_card');

    }

    public function Warehouse_Place()
    {
        return $this->BelongsTo('App\Models\ResourceManagement\Warehouse_place', 'fk_Warehouse_place', 'id_Warehouse_place');

    }
}
