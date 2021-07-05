<?php

namespace App\Models\ResourceManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batch extends Model
{
//    use HasFactory;
    protected $table = 'batch';

    protected $primaryKey='id_Stock_batch';

    protected $fillable = ['id_Stock_batch', 'fk_Stock_card', 'number','fk_Home', 'comment'];

    public $timestamps = false;

    use SoftDeletes;

    public function Stock_Card()
    {
        return $this->BelongsTo('App\Models\HouseholdResource\Stock_Card', 'fk_Stock_card', 'id_Stock_card');
    }
}
