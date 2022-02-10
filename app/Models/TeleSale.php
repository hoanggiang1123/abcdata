<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class TeleSale extends Model
{
    use HasFactory;

    protected $guarded = [];


    public $crudNotAccepted = [
        'created_at', 'updated_at', 'id', 'updated_by_id', 'updated_by_user', 'assigned_to_user', 'line', 'logs', 'assign_id', 'agent_l', 'vip', 'category'
    ];

    public function line () {
        return $this->belongsTo(TeleSaleLine::class, 'line_id');
    }
    public function agentL () {
        return $this->belongsTo(TeleSaleAgent::class, 'agent_id');
    }

    public function assignedToUser () {
        return $this->belongsTo(User::class, 'assign_id');
    }

    public function logs () {
        return $this->hasMany(TeleSaleLog::class, 'tele_sale_id')->orderBy('created_at', 'desc');
    }

    public function vip () {
        return $this->belongsTo(TeleSaleVip::class, 'vip_id');
    }

    public function category () {
        return $this->belongsTo(TeleSaleCategory::class, 'category_id');
    }

    public function scopeWhereLike($query, $column, $value) {
        return $query->where($column, 'like', '%'.$value.'%');
    }

    public function scopeOrWhereLike($query, $column, $value) {
        return $query->orWhere($column, 'like', '%'.$value.'%');
    }

    public function listItems($params) {

        $result = [];

        $perPage = isset($params['per_page']) ? (int) $params['per_page'] : 30;

        $orderBy = isset($params['order_by']) ? $params['order_by'] : 'created_at';

        $order = isset($params['order']) ? $params['order'] : 'desc';

        if ($params['dup'] === 'false') {

            $resp = self::with('line.agents')->with('assignedToUser')->with('logs.user')->with('agentL')->with('vip')->with('category')

            ->when(isset($params['own_id']) && $params['own_id'] !== '', function ($query) use ($params) {

                return $query->where('assign_id', $params['own_id']);

            })->when(isset($params['filter_all']) && $params['filter_all'] !== '', function ($query) use ($params) {

                return $query->whereLike('full_name', $params['filter_all'])

                    ->orWhereLike('phone', $params['filter_all'])

                        ->orWhereLike('email', $params['filter_all'])

                        ->orWhereLike('username', $params['filter_all']);
            })
            ->when(isset($params['note']) && $params['note'] !== '', function ($query) use ($params) {

                if ($params['note'] === 'null') {

                    return $query->whereNull('note');
                }
                else {

                    return $query->whereLike('note', $params['note']);
                }
            })
            ->when(isset($params['full_name']) && $params['full_name'] !== '', function ($query) use ($params) {

                if ($params['full_name'] === 'null') {

                    return $query->whereNull('full_name');
                }
                else {

                    return $query->whereLike('full_name', $params['full_name']);
                }
            })->when(isset($params['phone'])  && $params['phone'] !== '', function ($query) use ($params) {

                if ($params['phone'] === 'null') {

                    return $query->whereNull('phone');
                }
                else {

                    return $query->whereLike('phone',  $params['phone']);
                }

            })->when(isset($params['email']) && $params['email'] !== '', function ($query) use ($params) {

                if ($params['email'] === 'null') {

                    return $query->whereNull('email');
                }
                else {

                    return $query->whereLike('email',  $params['email']);
                }

            })->when(isset($params['call_date_time']) && $params['call_date_time'] !== '', function ($query) use ($params) {

                return $query->whereDate('call_date', $params['call_date_time']);

            })->when(isset($params['username']) && $params['username'] !== '', function ($query) use ($params) {

                if ($params['username'] === 'null') {

                    return $query->whereNull('username');

                }
                else if ($params['username'] === 'is not empty') {

                    return $query->whereNotNull('username');
                }
                else {

                    return $query->whereLike('username',  $params['username']);
                }

            })->when(isset($params['status']) && $params['status'] !== '', function ($query) use ($params) {

                return $query->where('status', $params['status']);

            })->when(isset($params['line_id']) && $params['line_id'] !== '', function ($query) use ($params) {

                if ($params['line_id'] === 'null') {

                    return $query->whereNull('line_id');

                } else {

                    return $query->where('line_id', $params['line_id']);
                }


            })->when(isset($params['agent_id']) && $params['agent_id'] !== '', function ($query) use ($params) {

                if ($params['agent_id'] === 'null') {

                    return $query->whereNull('agent_id');

                } else {

                    return $query->where('agent_id', $params['agent_id']);
                }

            })->when(isset($params['assign_id']) && $params['assign_id'] !== '', function ($query) use ($params) {

                if ($params['assign_id'] === 'null') {

                    return $query->whereNull('assign_id');
                }
                else {

                    return $query->where('assign_id', $params['assign_id']);
                }

            })->when(isset($params['vip_id']) && $params['vip_id'] !== '', function ($query) use ($params) {

                if ($params['vip_id'] === 'null') {

                    return $query->whereNull('vip_id');

                } else {

                    return $query->where('vip_id', $params['vip_id']);
                }

            })->when(isset($params['category_id']) && $params['category_id'] !== '', function ($query) use ($params) {

                if ($params['category_id'] === 'null') {

                    return $query->whereNull('category_id');

                } else {

                    return $query->where('category_id', $params['category_id']);
                }

            })->when(isset($params['source']) && $params['source'] !== '', function ($query) use ($params) {

                if ($params['source'] === 'null') {

                    return $query->whereNull('source');

                } else {

                    return $query->whereLike('source', $params['source']);
                }


            })->orderBy($orderBy, $order)->paginate($perPage);

            if ($resp) $result = $resp;

            return $result;

        } else if ($params['dup'] === 'true') {

            return $this->duplicateList();
        }


    }

    public function duplicateList () {

        $uniquePhone = self::select('phone')->groupBy('phone')->havingRaw('count(*) > 1')->get()->map(function($phone) {

            return $phone->phone;
        });

        if (count($uniquePhone) > 0) {

            $result = self::with('line.agents')
                ->with('assignedToUser')
                    ->with('logs.user')
                        ->with('agentL')
                            ->whereIn('phone', $uniquePhone)
                                ->orderBy('phone', 'desc')
                                    ->paginate(50);
            return $result;
        }

        return null;
    }

}
