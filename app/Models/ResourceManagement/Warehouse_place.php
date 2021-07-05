<?php


namespace App\Models\ResourceManagement;


use Illuminate\Database\Eloquent\Model;

class Warehouse_place extends Model
{
    protected $table = 'warehouse_place';

    protected $fillable = ['Warehouse_name', 'Address', 'Description', 'Place', 'fk_Home', 'fk_Transit_road', 'fk_Warehouse_place'=> 'warehouse', 'removed','id_Warehouse_place', 'removed_date'];

    public $timestamps = false;

    public function categories()
    {
        return $this->hasMany(Warehouse_place::class, 'id_Warehouse_place','fk_Warehouse_place');
    }

    public function Warehouse_Place()
    {
//        return $this->HasMany('App\Models\ResourceManagement\Warehouse_place', 'fk_Warehouse_place', 'id_Warehouse_place');
        return $this->HasMany(Warehouse_place::class, 'id_Warehouse_place','warehouse')->with('categories');
    }


    public function categories2()
    {
        return $this->hasMany(Warehouse_place::class, 'fk_Warehouse_place','id_Warehouse_place');
    }

    public function Warehouse_Place2()
    {
//        return $this->HasMany('App\Models\ResourceManagement\Warehouse_place', 'fk_Warehouse_place', 'id_Warehouse_place');
        return $this->HasMany(Warehouse_place::class, 'fk_Warehouse_place','id_Warehouse_place')->with('categories');
    }


}
