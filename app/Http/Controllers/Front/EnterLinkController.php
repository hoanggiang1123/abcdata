<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\EnterLink;
use App\Models\EnterLinkHit;
use App\Models\Vote;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class EnterLinkController extends Controller
{
    protected $model;

    public function __construct(EnterLink $link)
    {
        $this->model = $link;
    }
    public function show(Request $request, $slug) {

        $link = $this->model->where('slug', $slug)->first();

        if ($link) {
            $link->increment('hit');

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
                'enter_link_id' => $link->id,
                'user_agent' => $browserInfo['user_agent'],
                'browser' => $browserInfo['browser'],
                'browser_version' => $browserInfo['browser_version'],
                'os_platform' => $browserInfo['os_platform'],
                'device' => $browserInfo['device'],
                'ip' => $ip,
                'referal' => $referal
            ];

            EnterLinkHit::create( $data);

            $linkRedirect = substr( $link->link, 0, 4 ) === "http" ? $link->link : '//' .$link->link;

            return Redirect::to($linkRedirect);

        }
        return response(['message' => 'Not Found'], 404);
    }


    public function vote (Request $request) {
        $validator = Validator::make($request->all(), [
            'id'=> 'bail|required',
            'ip' => 'bail|required',
            'type' => 'bail|required'
        ]);

        if ($validator->fails()) {

            return response([

                'message' => $validator->messages()->first()

            ], 422);
        }

        $enterLink = $this->model->where('id', $request->id)->first();

        if ($enterLink) {

            $vote = Vote::where(['enter_link_id' =>$request->id, 'ip' => $request->ip])->first();

            if($vote) {
                return response(['message' => 'Already Voted'], 422);
            }

            DB::beginTransaction();

            try {

                $vote = Vote::create(['enter_link_id' =>$request->id, 'ip' => $request->ip]);

                $enterLink->increment('vote_count');

                $enterLink->folder()->increment('total_vote');

                DB::commit();

                if ($request->type === 'folder') return 1;

                return $enterLink;

            } catch (\Exception $e) {

                DB::rollback();

                return response(['message' => 'Unprocess Entity'], 422);
            }
        }
    }
}
