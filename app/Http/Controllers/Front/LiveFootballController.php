<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Models\FbLeague;

use App\Utils\Curl;

class LiveFootballController extends Controller
{

    protected $baseUrl = 'https://bongdanet.vn';

    public function schedule (Request $request) {

        $day = $request->day;

        $date = $request->date;

        $league = $request->league;

        $round = $request->round;

        if ($day !== null && $date !== null && $league !== null && $round !== null) {
            return \response(['message' => 'Not Found'], 404);
        }

        $scheduleUrl = $this->baseUrl . '/lich-thi-dau-bong-da';

        $data = [];

        if ($day !== null) {

            if ($day === 'hom-nay') $day = '';

            $scheduleUrl = $scheduleUrl . '/' . $day;

            $data = Redis::get($scheduleUrl);

            if ($data) return $data;

            $data = (new Curl)->getFootballSchedule($scheduleUrl);


            if (count($data) > 0) {
                $data['league_list'] = FbLeague::all();
                $dataStr = json_encode($data);

                Redis::set($scheduleUrl, $dataStr, 'EX', 3600);

                return $dataStr;
            }
        }

        if ($date !== null) {

            $scheduleUrl = $scheduleUrl . '/ngay-' . $date;

            $data = Redis::get($scheduleUrl);

            if ($data) return $data;

            $data = (new Curl)->getFootballSchedule($scheduleUrl);


            if (count($data) > 0) {
                $data['league_list'] = FbLeague::all();
                $dataStr = json_encode($data);

                Redis::set($scheduleUrl, $dataStr, 'EX', 3600);

                return $dataStr;
            }
        }

        if($league !== null) {

            $scheduleUrl = $scheduleUrl . '/' . $league;

            $data = Redis::get($scheduleUrl);

            if ($data) return $data;

            $data = (new Curl)->getFootballScheduleLeague($scheduleUrl);


            if (count($data) > 0) {
                $data['league_list'] = FbLeague::all();
                $dataStr = json_encode($data);

                Redis::set($scheduleUrl, $dataStr, 'EX', 3600);

                return $dataStr;
            }
        }

        if($round !== null) {

            $rounds = explode('-', $round);

            if (!isset($rounds[0]) || !isset($rounds[1])) return \response(['message' => 'Not Found'], 404);

            $scheduleUrl = $scheduleUrl . '/' . $rounds[0] . '/' . $rounds[1];

            $data = Redis::get($scheduleUrl);

            if ($data) return $data;

            $data = (new Curl)->getFootballScheduleLeagueAjax($scheduleUrl);


            if (count($data) > 0) {
                $dataStr = json_encode($data);

                Redis::set($scheduleUrl, $dataStr, 'EX', 3600);

                return $dataStr;
            }
        }

        return \response(['message' => 'Not Found'], 404);
    }

    public function result (Request $request) {

        $day = $request->day;

        $date = $request->date;

        $league = $request->league;

        $round = $request->round;

        $type = $request->type;

        if ($day !== null && $date !== null && $league !== null && $round !== null && $type !== null) {
            return \response(['message' => 'Not Found'], 404);
        }

        $scheduleUrl = $this->baseUrl . '/ket-qua-bong-da';

        $data = [];

        if ($day !== null) {

            if ($day === 'hom-nay') $day = '';

            $scheduleUrl = $scheduleUrl . '/' . $day;

            $data = Redis::get($scheduleUrl);

            if ($data) return $data;

            $data = (new Curl)->getFootballResult($scheduleUrl);

            if (count($data) > 0) {
                $data['league_list'] = FbLeague::all();
                $dataStr = json_encode($data);

                Redis::set($scheduleUrl, $dataStr, 'EX', 3600);

                return $dataStr;
            }
        }

        if ($date !== null) {

            $scheduleUrl = $scheduleUrl . '/ngay-' . $date;

            $data = Redis::get($scheduleUrl);

            if ($data) return $data;

            $data = (new Curl)->getFootballResult($scheduleUrl);


            if (count($data) > 0) {
                $data['league_list'] = FbLeague::all();
                $dataStr = json_encode($data);

                Redis::set($scheduleUrl, $dataStr, 'EX', 3600);

                return $dataStr;
            }
        }

        if($league !== null) {

            $scheduleUrl = $scheduleUrl . '/' . $league;

            $data = Redis::get($scheduleUrl);

            if ($data) return $data;

            $data = (new Curl)->getFootballResultLeague($scheduleUrl);


            if (count($data) > 0) {
                $data['league_list'] = FbLeague::all();
                $dataStr = json_encode($data);

                Redis::set($scheduleUrl, $dataStr, 'EX', 3600);

                return $dataStr;
            }
        }

        if($round !== null) {

            $rounds = explode('-', $round);

            if (!isset($rounds[0]) || !isset($rounds[1])) return \response(['message' => 'Not Found'], 404);

            $scheduleUrl =$this->baseUrl . '/lich-thi-dau-bong-da/' . $rounds[0] . '/' . $rounds[1];

            $data = Redis::get($scheduleUrl);

            if ($data) return $data;

            $data = (new Curl)->getFootballResultLeagueAjax($scheduleUrl);


            if (count($data) > 0) {
                $dataStr = json_encode($data);

                Redis::set($scheduleUrl, $dataStr, 'EX', 3600);

                return $dataStr;
            }
        }

        if ($type !== null && $type === 'live') {

            $data = (new Curl)->getFootballResultLive($this->baseUrl . '/livescore');


            if (count($data) > 0) {
                $data['league_list'] = FbLeague::all();
                $dataStr = json_encode($data);

                Redis::set($scheduleUrl, $dataStr, 'EX', 60);

                return $dataStr;
            }
        }

        return \response(['message' => 'Not Found'], 404);
    }

    public function ranking (Request $request) {

        $league = $request->league;

        $rankingUrl = $this->baseUrl . '/bang-xep-hang-bong-da';

        $data = [];

        if ($league === null) {

            $data = Redis::get($rankingUrl);

            if ($data) return $data;

            $data = (new Curl)->getFootballRanking($rankingUrl);


            if (count($data) > 0) {
                $data['league_list'] = FbLeague::all();
                $dataStr = json_encode($data);

                Redis::set($rankingUrl, $dataStr, 'EX', 43200);

                return $dataStr;
            }

        }
        else {
            $rankingUrl = $rankingUrl . '/' . $league;
            $data = Redis::get($rankingUrl);

            if ($data) return $data;

            $data = (new Curl)->getFootballRankingLeague($rankingUrl);


            if (count($data) > 0) {
                $data['league_list'] = FbLeague::all();
                $dataStr = json_encode($data);

                Redis::set($rankingUrl, $dataStr, 'EX', 43200);

                return $dataStr;
            }
        }

        return \response(['message' => 'Not Found'], 404);
    }


    public function tylekeo (Request $request) {

        $day = $request->day;

        $date = $request->date;

        $live = $request->type;

        $tylekeoUrl = $this->baseUrl . '/keo-bong-da';

        if ($day !== null && $date !== null && $live !== null) return response(['message' => 'Not Found'], 404);

        if ($day !== null) {

            if ($day === 'hom-nay') {

                $day = '';
            }

            $tylekeoUrl = $tylekeoUrl .'/' . $day;

        } else if ($date !== null) {

            $tylekeoUrl = $tylekeoUrl .'/ngay-' . $date;

        } else if ($live !== null && $live === 'live') {
            $tylekeoUrl = $tylekeoUrl . '-live';
        }

        $data = Redis::get($tylekeoUrl);

        if ($data) return $data;

        $data = (new Curl)->getTylekeo($tylekeoUrl);

        if (count($data) > 0) {
            $data['league_list'] = FbLeague::all();
            $dataStr = json_encode($data);

            $time = $live !== null && $live === 'live' ? 60 : 3600;

            Redis::set($tylekeoUrl, $dataStr, 'EX', $time);

            return $dataStr;
        }

        return response(['message' => 'Not Found'], 404);

    }
}
