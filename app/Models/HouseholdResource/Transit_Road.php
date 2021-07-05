<?php


namespace App\Models\HouseholdResource;


use Illuminate\Database\Eloquent\Model;

class Transit_Road extends Model
{
    protected $table = 'transit_road';

    protected $fillable = ['id_Transit_road', 'comment', 'reason',  'fk_Stock'];

    public $timestamps = false;

//    public function Warehouse_place_Goes()
//    {
//        return $this->HasMany('App\Models\HouseholdRecourse\Supplier', 'fk_Transit_road', 'id_Transit_road');
//    }
//
//    public function Stock_unit()
//    {
//        return $this->HasMany('App\Models\HouseholdRecourse\Stock_Unit', 'fk_Transit_road', 'id_Transit_road');
//    }
//
//    public function Warehouse_Place()
//    {
//        return $this->BelongsTo('App\Models\HouseholdRecourse\Warehouse_Place', 'fk_Warehouse_place', 'id_Warehouse_place');
//    }
//
//    public function Home_Member()
//    {
//        return $this->BelongsTo('App\Models\UserManagement\Home_member', 'fk_Home_member', 'id_Registered_user');
//    }
}
