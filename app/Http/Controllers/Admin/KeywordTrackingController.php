<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KeywordTracking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class KeywordTrackingController extends Controller
{
    protected $model;

    public function __construct(KeywordTracking $keywordTracking) {
        $this->model = $keywordTracking;
    }

    public function index (Request $request) {
        $result = $this->model->listItems($request->all());
        if ($result) return $result;
        return response(['message' => 'Not Found'], 404);
    }

    public function filter () {
        $browser = $this->model->distinct('browser')->pluck('browser');
        $device = $this->model->distinct('device')->pluck('device');
        $os_platform = $this->model->distinct('os_platform')->pluck('os_platform');
        $keyword = \App\Models\Keyword::distinct('name')->pluck('name');

        return response(['browser' => $browser, 'device' => $device, 'os_platform' => $os_platform, 'keyword' => $keyword]);
    }

    public function getChartClick (Request $request) {
        $params = $request->all();

        $offsetDate = Carbon::now();

        if (isset($params['day']) && $params['day'] === 'thisweek') $offsetDate = Carbon::now()->subDay(7);

        if (isset($params['day']) && $params['day'] === 'twoweek') $offsetDate = Carbon::now()->subDay(14);

        if (isset($params['day']) && $params['day'] === 'threeweek') $offsetDate = Carbon::now()->subDay(21);

        if (isset($params['day']) && $params['day'] === 'onemonth') $offsetDate = Carbon::now()->subDay(30);

        if (isset($params['day']) && $params['day'] === 'threemonth') $offsetDate = Carbon::now()->subDay(90);

        if (isset($params['day']) && $params['day'] === 'sixmonth') $offsetDate = Carbon::now()->subDay(180);

        if (isset($params['day']) && $params['day'] === 'oneyear') $offsetDate = Carbon::now()->subDay(180);

        if (isset($params['from']) && isset($params['to']) && $params['from'] !== '' && $params['to'] !== '') {
            $from = $params['from'];

            $to = date('Y-m-d', strtotime($params['to'] . ' +1 day'));

            $res = DB::select(DB::raw("SELECT k.id, k.name, (SELECT count(*) FROM keyword_trackings as track  WHERE k.id = track.keyword_id) as click, t.referal, t.browser, t.os_platform, t.device, t.ip, t.created_at FROM keywords as k RIGHT JOIN keyword_trackings as t ON k.id = t.keyword_id WHERE t.created_at >= '$from' AND t.created_at <= '$to' ORDER BY click DESC"));
        }
        else if (isset($params['day']) && $params['day'] === 'oneyear') {

            $res = DB::select(DB::raw("SELECT k.id, k.name, (SELECT count(*) FROM keyword_trackings as tracking  WHERE k.id = tracking.keyword_id) as click, t.referal, t.browser, t.os_platform, t.device, t.ip, t.created_at FROM keywords as k RIGHT JOIN keyword_trackings as t ON k.id = t.keyword_id WHERE year(t.created_at) = year(curdate()) ORDER BY click DESC"));

        } else {

            $res = DB::select(DB::raw("SELECT k.id, k.name, (SELECT count(*) FROM keyword_trackings as track  WHERE k.id = track.keyword_id) as click, t.referal, t.browser, t.os_platform, t.device, t.ip, t.created_at FROM keywords as k RIGHT JOIN keyword_trackings as t ON k.id = t.keyword_id WHERE t.created_at >= '$offsetDate' ORDER BY click DESC"));

        }


        $result = [];

        if (count($res) > 0) {

            foreach($res as $val) {

                $date = date('Y-m-d', strtotime($val->created_at));

                $result[$date][] = $val;
            }
        }

        if (count($result) > 0) {

            uksort($result, function($a, $b) {
                return strtotime($a) > strtotime($b);
            });

            return $result;
        }

        return response(['message' => 'Not Found'], 404);
    }
}
