<?php


namespace App\Models\HouseholdResource;


use Illuminate\Database\Eloquent\Model;

class Stock_Supplier extends Model
{
    protected $table = 'stocks_supplier';

    protected $fillable = ['fk_stock_card', 'fk_supplier'];

    public $timestamps = false;

    public function Stock_Card()
    {
        return $this->BelongsTo('App\Models\HouseholdResource\Stock_Card', 'fk_stock_card', 'id_Stock_card');

    }
    public function Supplier()
    {
        return $this->BelongsTo('App\Models\HouseholdResource\Supplier', 'fk_supplier', 'supplier_id');

    }

}

