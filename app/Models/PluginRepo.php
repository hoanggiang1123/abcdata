<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PluginRepo extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['last_updated'];

    public function getLastUpdatedAttribute () {
        return date('Y-m-d H:i:s', strtotime($this->updated_at));
    }

    public $crudNotAccepted = [
        'created_at', 'updated_at', 'id', 'last_updated'
    ];
}
