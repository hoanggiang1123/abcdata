<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeleSaleLine extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $crudNotAccepted = [
        'created_at', 'updated_at', 'id', 'telesales_count'
    ];

    public function telesales () {
        return $this->hasMany(TeleSale::class, 'line_id');
    }

    public function agents () {
        return $this->hasMany(TeleSaleAgent::class, 'line_id');
    }

    public function listItems ($params) {
        $result = [];

        $perPage = isset($params['per_page']) ? (int) $params['per_page'] : 10000;

        $orderBy = isset($params['order_by']) ? $params['order_by'] : 'created_at';

        $order = isset($params['order']) ? $params['order'] : 'desc';

        $resp = self::withCount('telesales')
        ->when(isset($params['name']) && $params['name'] !== '', function ($query) use ($params) {

            return $query->where('name', $params['name']);

        })->when(isset($params['description'])  && $params['description'] !== '', function ($query) use ($params) {

            return $query->where('description', $params['description']);

        })->orderBy($orderBy, $order)->paginate($perPage);

        if ($resp) $result = $resp;

        return $result;
    }
}
