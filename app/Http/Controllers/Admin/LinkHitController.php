<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\LinkHit;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LinkHitController extends Controller
{

    protected $model;

    public function __construct(LinkHit $linkHit)
    {
        $this->model = $linkHit;
    }
    public function getLinkHitByLinkId (Request $request, $id) {
        return $this->model->listItems($request->all(), $id);

        // if(count($linkHits)) {
        //     return  $linkHits;
        // }

        // return response(['message' => 'Not Found'], 404);
    }

    public function index (Request $request) {

        $hits = $this->model->listItems($request->all());

        if (count($hits) > 0) {
            return $hits;
        }

        return response(['message' => 'Not Found'], 404);
    }

    public function filter () {
        $browser = $this->model->distinct('browser')->pluck('browser');
        $device = $this->model->distinct('device')->pluck('device');
        $os_platform = $this->model->distinct('os_platform')->pluck('os_platform');
        $position = \App\Models\Link::distinct('position')->pluck('position');
        $link = \App\Models\Link::distinct('link')->pluck('link');

        return response(['browser' => $browser, 'device' => $device, 'position' => $position, 'os_platform' => $os_platform, 'link' => $link]);
    }

    public function getChartClick(Request $request) {

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

            $res = DB::select(DB::raw("SELECT l.id, l.link, (SELECT count(*) FROM link_hits as hit  WHERE l.id = hit.link_id) as click, h.referal, h.browser, h.os_platform, h.device, h.ip, h.created_at FROM links as l RIGHT JOIN link_hits as h ON l.id = h.link_id WHERE h.created_at >= '$from' AND h.created_at <= '$to' ORDER BY click DESC"));
        }
        else if (isset($params['day']) && $params['day'] === 'oneyear') {

            $res = DB::select(DB::raw("SELECT l.id, l.link, (SELECT count(*) FROM link_hits as hit  WHERE l.id = hit.link_id) as click, h.referal, h.browser, h.os_platform, h.device, h.ip, h.created_at FROM links as l RIGHT JOIN link_hits as h ON l.id = h.link_id WHERE year(h.created_at) = year(curdate()) ORDER BY click DESC"));

        } else {

            $res = DB::select(DB::raw("SELECT l.id, l.link, (SELECT count(*) FROM link_hits as hit  WHERE l.id = hit.link_id) as click, h.referal, h.browser, h.os_platform, h.device, h.ip, h.created_at FROM links as l RIGHT JOIN link_hits as h ON l.id = h.link_id WHERE h.created_at >= '$offsetDate' ORDER BY click DESC"));

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
