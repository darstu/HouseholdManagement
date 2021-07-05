<?php


namespace App\Models\HouseholdResource;


use Illuminate\Database\Eloquent\Model;

class Stock_Unit extends Model
{
    protected $table = 'stock_unit';

    protected $fillable = ['id_Stock_unit', 'Amount', 'Expire_by', 'Status', 'Units', 'Type', 'fk_Home_member', 'fk_Supplier', 'fk_Transit_road', 'fk_Stock_type'];

    public $timestamps = false;

    public function Supplier()
    {
        return $this->BelongsTo('App\Models\HouseholdRecourse\Supplier', 'fk_Supplier', 'id_Supplier');
    }

    public function Stock_Type()
    {
        return $this->BelongsTo('App\Models\HouseholdRecourse\Stock_Type', 'fk_Stock_type', 'id_Stock_type');
    }

    public function Home_Member()
    {
        return $this->BelongsTo('App\Models\UserManagement\Home_member', 'fk_Home_member', 'id_Registered_user');
    }
}
