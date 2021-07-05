<?php


namespace App\Models\ResourceManagement;
use Illuminate\Database\Eloquent\Model;

class Stock_Type extends Model
{
    protected $table = 'stock_type';

    protected $fillable = ['id_Stock_type', 'Type_name', 'Type_description', 'fk_household_id'];

    public $timestamps = false;


}
