<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Link;
use App\Models\LinkHit;
use Illuminate\Support\Facades\Redirect;

class LinkController extends Controller
{

    protected $model;

    public function __construct(Link $link)
    {
        $this->model = $link;
    }

    public function index (Request $request) {
        $params = $request->all();

        $resp = $this->model->when(isset($params['directory']), function ($query) use ($params) {
            return $query->where('link_directory_id', $params['directory']);
        })->get();

        if ($resp && count($resp) > 0) {
            return $resp->map(function($item) {
                $img = strpos($item->image, 'http') === 0 ?  $item->image : config('app.proxy_url') .$item->image;
                return [
                    'link' => config('app.proxy_url') .'/api/redirector/' . $item->slug,
                    'img' => $img,
                    'position' => $item->position
                ];
            });
        }
        return response(['message' => 'Not Found'], 404);
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
                'link_id' => $link->id,
                'user_agent' => $browserInfo['user_agent'],
                'browser' => $browserInfo['browser'],
                'browser_version' => $browserInfo['browser_version'],
                'os_platform' => $browserInfo['os_platform'],
                'device' => $browserInfo['device'],
                'ip' => $ip,
                'referal' => $referal
            ];

            LinkHit::create( $data);

            $linkRedirect = substr( $link->link, 0, 4 ) === "http" ? $link->link : '//' .$link->link;

            return Redirect::to($linkRedirect);

        }
        return response(['message' => 'Not Found'], 404);
    }
}
