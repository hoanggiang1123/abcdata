<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\Keyword;
use App\Models\SatiliteDomain;
use App\Models\KeyCode;
use App\Models\KeywordTracking;

class KeywordController extends Controller
{
    protected $model;

    public function __construct(Keyword $keyword) {
        $this->model = $keyword;
    }

    public function index (Request $request) {
        $result = $this->model->listItems($request->all());
        if (count($result) > 0) {
            return $result;
        }
        return \response(['message' => 'Not Found'], 404);
    }

    public function update (Request $request, $id) {
        $keyword = $this->model->where('id', $id)->first();

        if ($keyword) {

            $updateData = prepareParams($request->all(), $this->model->crudNotAccepted);

            $update = $keyword->update($updateData);

            if ($update) return $update;

            return response(['message' => 'Unprocess Entiry'], 422);

        }

        return response(['message' => 'Unprocess Entiry'], 422);
    }

    public function deleteMany (Request $request) {
        $ids = $request->ids;

        $delete = $this->model->destroy($ids);

        if ($delete) {
            return $delete;
        }

        return response(['message' => 'Unprocess Entity'], 422);
    }

    public function store (Request $request) {
        $validator = Validator::make($request->all(), [
            'name'=> 'bail|required',
            'content' => 'bail|required',
        ]);

        if ($validator->fails()) {
            return response([
                'message' => $validator->messages()->first()
            ], 422);
        }

        $updateData = prepareParams($request->all(), $this->model->crudNotAccepted);

        $keyword = $this->model->create($updateData);

        if ($keyword) {
            return response($keyword);
        }

        return response(['message' => 'Unprocess Entity'], 422);
    }

    public function createMany (Request $request) {
        $params = $request->input();

        DB::beginTransaction();

        try {

            if ($params && count($params) > 0) {
                foreach ($params as $val) {
                    $item = prepareParams($val, $this->model->crudNotAccepted);
                    $this->model->create($item);
                }
            }

            DB::commit();

            return 1;

        } catch (\Exception $e) {

            DB::rollback();

            return response(['message' => 'Unprocess Entity'], 422);
        }
    }

    public function getContent (Request $request) {
        $host = $request->headers->get('referer');

        $domain = SatiliteDomain::where('name', $host)->get();

        if ($domain && count($domain) > 0) {

            $results = $this->model->select('name', 'content', 'appearable_time')->get();

            $keyArr = [];

            if ($results && count($results) > 0) {

                foreach ($results as $value) {

                    if ($value->appearable_time > 0) {

                        for ($i = 0; $i < $value->appearable_time; $i++) {

                            $keyArr[] = $value->name;
                        }
                    }
                }

                return \response(['data' => $results, 'keys' => $keyArr]);
            }

            return response(['message' => 'Not Found'], 404);
        }

        return response(['message' => 'Not Found'], 404);
    }

    public function getPassword (Request $request) {

        $domain = SatiliteDomain::where('name', $request->host)->get();

        if ($domain && count($domain) > 0) {

            $resp = KeyCode::first();

            if ($resp) return $resp->code;

            return response(['message' => 'Not Found'], 404);
        }

        return response(['message' => 'Not Found'], 404);
    }

    public function checkPassword (Request $request) {

        $domain = SatiliteDomain::where('name', $request->host)->get();

        if ($domain && count($domain) > 0) {

            $resp = KeyCode::where('code', $request->password)->first();

            if ($resp) {
                
                $keyword = $this->model->where('name', $request->name)->first();

                if ($keyword) {
                    
                    $keyword->increment('intend_count');

                    $browserInfo = getBrowserInfo();

                    $ip = getIpAddress();

                    $referal = request()->headers->get('referer');

                    if (!$referal) {

                        if(strstr(strtolower($_SERVER['HTTP_USER_AGENT']), "googlebot")) {
                            $referal = 'googlebot';
                        } else {
                            $referal = 'direct_open';
                        }
                    }
                    
                    $data = [
                        'keyword_id' => $keyword->id,
                        'user_agent' => $browserInfo['user_agent'],
                        'browser' => $browserInfo['browser'],
                        'browser_version' => $browserInfo['browser_version'],
                        'os_platform' => $browserInfo['os_platform'],
                        'device' => $browserInfo['device'],
                        'ip' => $ip,
                        'referal' => $referal
                    ];

                    KeywordTracking::create( $data);
                }

                return 'ok';
            }

            return response(['message' => 'Not Found'], 404);
        }

        return response(['message' => 'Not Found'], 404);
    }

    public function getKeyCode () {

        $resp = KeyCode::first();

        if ($resp) return $resp;

        return \response(['message' => 'Not Found'], 404);
    }

    public function updateKeyCode (Request $request, $id) {

        $keycode = KeyCode::where('id', $id)->first();

        if ($keycode) {

            $updateData = prepareParams($request->all(), (new KeyCode)->crudNotAccepted);

            $update = $keycode->update($updateData);

            if ($update) {

                return response($update);

            }
        }
    }
}
