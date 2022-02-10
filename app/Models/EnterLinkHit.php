<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Carbon;

class EnterLinkHit extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function enterLink () {
        return $this->belongsTo(EnterLink::class, 'enter_link_id');
    }

    public function getCreatedAtAttribute($value) {
        return date('d-m-y', strtotime($value));
    }

    protected $appends = ['nice_date'];

    public function getNiceDateAttribute () {
        return date('d/m/Y H:i', strtotime($this->updated_at));
    }

    public function listItems ($params = '', $id = null) {
        $resp = [];

        $resp = self::with('enterLink')
            ->when($id !== null, function($query) use ($id) {

                return $query->where('enter_link_id', '=', $id);
            })
            ->when(isset($params['browser']) && $params['browser'] !== '', function($query) use ($params) {

                return $query->where('browser', '=', $params['browser']);

            })
            ->when(isset($params['device']) && $params['device'] !== '', function($query) use ($params) {

                return $query->where('device', '=', $params['device']);

            })
            ->when(isset($params['os_platform']) && $params['os_platform'] !== '', function($query) use ($params) {

                return $query->where('os_platform', '=', $params['os_platform']);

            })
            ->when(isset($params['referal']) && $params['referal'] !== '', function($query) use ($params) {

                return $query->where('referal', '=', $params['referal']);

            })
            ->when(isset($params['position']) && $params['position'] !== '', function($query) use ($params) {

                return $query->whereHas('link', function ($q) use ($params) {

                    $q->where('position', $params['position']);

                });

            })
            ->when(isset($params['link']) && $params['link'] !== '', function($query) use ($params) {

                return $query->whereHas('link', function ($q) use ($params) {

                    $q->where('link', $params['link']);

                });

            })
            ->when(isset($params['day']) && $params['day'] === 'today', function($query) {

                return $query->whereDate('created_at', '=', Carbon::today());

            })
            ->when(isset($params['day']) && $params['day'] === 'thisweek', function($query) {

                return $query->where('created_at', '>=', Carbon::now()->subdays(7));

            })
            ->when(isset($params['day']) && $params['day'] === 'twoweek', function($query) {

                return $query->where('created_at', '>=', Carbon::now()->subdays(14));

            })
            ->when(isset($params['day']) && $params['day'] === 'threeweek', function($query) {

                return $query->where('created_at', '>=', Carbon::now()->subdays(21));
            })
            ->when(isset($params['day']) && $params['day'] === 'onemonth', function($query) {

                return $query->where('created_at', '>=', Carbon::now()->subdays(30));
            })
            ->when(isset($params['day']) && $params['day'] === 'threemonth', function($query) {

                return $query->where('created_at', '>=', Carbon::now()->subdays(90));
            })
            ->when(isset($params['day']) && $params['day'] === 'sixmonth', function($query) {

                return $query->where('created_at', '>=', Carbon::now()->subdays(180));
            })
            ->when(isset($params['day']) && $params['day'] === 'oneyear', function($query) {

                return $query->whereYear('created_at', '=', Carbon::now()->year);
            })
            ->when(isset($params['from']) && isset($params['to']), function($query) use($params) {

                $date = new \DateTime($params['to']);

                $date->modify('+1 day');

                return $query->whereBetween('created_at', [date($params['from']), $date]);

            })
            ->when(isset($params['order_by']) && $params['order_by'] !== '' && isset($params['order']) && $params['order'] !== '', function($query) use($params) {

                return $query->orderBy($params['order_by'], $params['order']);

            })
            ->when((isset($params['order_by']) && $params['order_by'] === '' && isset($params['order']) && $params['order'] === '') || (!isset($params['order_by']) && !isset($params['order'])), function($query) use($params) {

                return $query->orderBy('created_at', 'desc');

            });

            if (isset($params['page']) && isset($params['per_page'])) {

                $resp = $resp->paginate($params['per_page']);

            } else {

                $resp = $resp->get();
            }

        return $resp;
    }
}
