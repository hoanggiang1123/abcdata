<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Popup extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $crudNotAccepted = [
        'created_at', 'updated_at', 'id'
    ];

    public function listItems($params = []) {
        $result = [];

        $perPage = isset($params['per_page']) ? (int) $params['per_page'] : 30;

        $orderBy = isset($params['order_by']) ? $params['order_by'] : 'created_at';

        $order = isset($params['order']) ? $params['order'] : 'desc';

        $resp = self::when(isset($params['domain']) && $params['domain'] !== '', function ($query) use ($params) {

            return $query->where('domain', $params['domain']);

        })
        ->orderBy($orderBy, $order)->paginate($perPage);

        if ($resp) $result = $resp;

        return $result;
    }
}
