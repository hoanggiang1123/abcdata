<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeleSaleAgent extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $crudNotAccepted = [
        'created_at', 'updated_at', 'id', 'line', 'telesales_count'
    ];

    public function line () {
        return $this->belongsTo(TeleSaleLine::class, 'line_id');
    }

    public function telesales () {
        return $this->hasMany(TeleSale::class, 'agent_id');
    }

    public function listItems ($params) {
        $result = [];

        $perPage = isset($params['per_page']) ? (int) $params['per_page'] : 10000;

        $orderBy = isset($params['order_by']) ? $params['order_by'] : 'created_at';

        $order = isset($params['order']) ? $params['order'] : 'desc';

        $resp = self::withCount('telesales')->with('line')
        ->when(isset($params['name']) && $params['name'] !== '', function ($query) use ($params) {

            return $query->where('name', $params['name']);

        })->when(isset($params['description'])  && $params['description'] !== '', function ($query) use ($params) {

            return $query->where('description', $params['description']);

        })->when(isset($params['line_id'])  && $params['line_id'] !== '', function ($query) use ($params) {

            return $query->where('line_id', $params['line_id']);

        })->when(isset($params['line'])  && $params['line'] !== '', function ($query) use ($params) {

            return $query->whereHas('line', function ($q) use ($params) {

                $q->where('name', $params['line']);

            });

        })->orderBy($orderBy, $order)->paginate($perPage);

        if ($resp) $result = $resp;

        return $result;
    }

}
