<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Carbon;

class TeleSaleRecord extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $crudNotAccepted = [
        'created_at', 'updated_at', 'id'
    ];

    public function user () {
        return $this->belongsTo(User::class, 'user_id');
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

        $resp = self::with('user')->when(isset($params['from']) && $params['from'] !== '', function ($query) use ($params) {

                return $query->where('from', $params['from']);

            })->when(isset($params['to']) && $params['to'] !== '', function ($query) use ($params) {


                return $query->where('to', $params['to']);
            })
            ->when(isset($params['direction']) && $params['direction'] !== '', function ($query) use ($params) {

                return $query->where('direction', $params['direction']);
            })
            ->when(isset($params['status']) && $params['status'] !== '', function ($query) use ($params) {

                return $query->where('status', $params['status']);

            })->when(isset($params['remark'])  && $params['remark'] !== '', function ($query) use ($params) {

                return $query->whereLike('remark', $params['remark']);


            })->when(isset($params['day']) && $params['day'] !== '', function($query) use ($params) {

                $day = $params['day'];
                $day = Carbon::now()->subdays((int)$day);

                return $query->whereDate('created_at', '>=', $day);

            })
            ->when(isset($params['month']) && $params['month'] !== '', function($query) use ($params) {

                $month = $params['month'];
                $month = Carbon::now()->subMonth((int)$month)->month;

                return $query->whereMonth('created_at', '>=', $month);

            })
            ->when(isset($params['from_date']) && isset($params['to_date']), function($query) use($params) {

                $date = new \DateTime($params['to_date']);

                $date->modify('+1 day');

                return $query->whereBetween('created_at', [date($params['from_date']), $date]);

            })
            ->orderBy($orderBy, $order)->paginate($perPage);

            if ($resp) $result = $resp;

            return $result;
    }

    public function statisticCall ($params) {
        $result = [];

        $perPage = isset($params['per_page']) ? (int) $params['per_page'] : 30;

        $orderBy = isset($params['order_by']) ? $params['order_by'] : 'created_at';

        $order = isset($params['order']) ? $params['order'] : 'desc';

        $resp = self::with('user')->when(isset($params['from']) && $params['from'] !== '', function ($query) use ($params) {

                return $query->where('from', $params['from']);

            })->when(isset($params['to']) && $params['to'] !== '', function ($query) use ($params) {

                return $query->where('to', $params['to']);
            })
            ->when(isset($params['direction']) && $params['direction'] !== '', function ($query) use ($params) {

                return $query->where('direction', $params['direction']);
            })
            ->when(isset($params['status']) && $params['status'] !== '', function ($query) use ($params) {

                return $query->where('status', $params['status']);

            })->when(isset($params['remark'])  && $params['remark'] !== '', function ($query) use ($params) {

                return $query->whereLike('remark', $params['remark']);


            })
            ->when(isset($params['day']) && $params['day'] !== '', function($query) use ($params) {

                $day = $params['day'];
                $day = Carbon::now()->subdays((int)$day);

                return $query->whereDate('created_at', '>=', $day);

            })
            ->when(isset($params['month']) && $params['month'] !== '', function($query) use ($params) {

                $month = $params['month'];
                $month = Carbon::now()->subMonth((int)$month)->month;

                return $query->whereMonth('created_at', '>=', $month);

            })
            ->when(isset($params['from_date']) && isset($params['to_date']), function($query) use($params) {

                $date = new \DateTime($params['to_date']);

                $date->modify('+1 day');

                return $query->whereBetween('created_at', [date($params['from_date']), $date]);

            })
            ->orderBy('created_at', 'asc')->get();

            if (count($resp) > 0) {

                $totalCall = count($resp);

                $complete = [];

                $totalInboundCall = 0;
                $totalOutboundCall = 0;
                $totalComplete = 0;

                $chart = [];

                foreach ($resp as $item) {
                    if ($item->status === 'completed') {
                        $complete[] = $item->duration;
                    }
                    if ($item->direction === 'inbound') {
                        $totalInboundCall++;
                    }
                    if ($item->direction === 'outbound-dial') {
                        $totalOutboundCall++;
                    }
                    $date = date('Y-m-d', \strtotime($item->created_at));
                    $chart[$date][] = $item;
                }

                if (count($complete) > 0) {
                    $totalComplete = floor(array_sum($complete) / count($complete));
                }

                $chartFormat = $this->createChart($chart, $resp);

                return [
                    'statistic_call' => [
                        'total_call' => $totalCall,
                        'average_call' => $totalComplete,
                        'total_inbound' => $totalInboundCall,
                        'total_outbound' => $totalOutboundCall
                    ],
                    'statistic_chart' => $chartFormat
                ];
            }

            return [];
    }

    public function getDateRange($first, $last, $step = '+1 day', $format = 'Y-m-d' ) {
        $dates = [];
        $current = strtotime( $first );
        $last = strtotime( $last );

        while( $current <= $last ) {

            $dates[] = date( $format, $current );
            $current = strtotime( $step, $current );
        }

        return $dates;
    }

    public function createChart($chart, $resp) {

        if (count($chart) > 0) {
            $min = date('Y-m-d', strtotime(($resp[0])->created_at));
            $max = date('Y-m-d', strtotime(($resp[(count($resp) - 1)])->created_at));

            $dates = $this->getDateRange($min, $max);

            foreach ($dates as $date) {

                if (isset($chart[$date])) {
                    $chartFormat[$date] = $chart[$date];
                }
                else {
                    $chartFormat[$date] = [];
                }
            }

            $labels = [];
            $data = [];

            foreach ($chartFormat as $key => $value) {
                $labels[] = $key;
                $data[] = count($value);
            }

            return [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Total Call',
                        'data' => $data,
                        'borderColor' => '#4caf50',
                        'backgroundColor' => '#4caf50',
                    ]
                ]
            ];
        }
        return [];
    }

    public function getFilter () {
        $from = self::distinct('from')->pluck('from');
        $to = self::distinct('to')->where('direction', 'inbound')->pluck('to');
        $status = self::distinct('status')->pluck('status');
        $direction = self::distinct('direction')->pluck('direction');
        return ['from' =>$from, 'to'=> $to, 'status' => $status, 'direction' => $direction];
    }

}
