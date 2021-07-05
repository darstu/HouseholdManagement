<?php


namespace App\Models\HouseholdResource;


use Illuminate\Database\Eloquent\Model;

class Home extends Model
{
    protected $table = 'household';

    protected $fillable = ['Name', 'Address', 'Phone', 'Alternative_address', 'City', 'removed', 'removed_date', 'created_date'];

    public $timestamps = false;

    public function offers()
    {
        return $this->hasMany(Sale_offer::class);
    }
}
