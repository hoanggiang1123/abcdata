<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use App\Models\LinkEmbeded;
use App\Models\Link;

class LinkEmbededController extends Controller
{

    protected $model;

    public function __construct(LinkEmbeded $linkEmbeded)
    {
        $this->model = $linkEmbeded;
    }

    public function show (Request $request, $slug) {

        $item = $this->model->where('script_id', $slug)->first();

        if ($item) {

            $ids = $item->link_ids;

            $adsArr = [];

            foreach((explode(',', $ids)) as $id) {

                $link = Link::where('id', $id)->first();

                if ($link) {

                    $adsArr[] = [
                        'link' => config('app.proxy_url') . '/api/redirector/' . $link->slug,
                        'image' => $link->image,
                        'position' => $link->position
                    ];
                }

            }

            if (count($adsArr) > 0) {
                $html = $this->model->generateHtml($adsArr);
                $script = 'function createStyle() { let css = \'.banner-float_left{position:fixed;left:0;top:50%;transform:translateY(-50%)}.banner-ads{z-index:99999}.banner-close{display:block;font-size:12px;padding:0 8px;cursor:pointer;position:absolute;right:0;background:grey;color:#fff;text-align:center}[class*=\" icofont-\"],[class^=icofont-]{font-family:IcoFont!important;speak:none;font-style:normal;font-weight:400;font-variant:normal;text-transform:none;white-space:nowrap;word-wrap:normal;direction:ltr;line-height:1;-webkit-font-feature-settings:"liga";-webkit-font-smoothing:antialiased}.img-fluid{max-width:100%;height:auto}.banner-float_right{position:fixed;right:0;top:50%;transform:translateY(-50%)}.banner-balloon_left{position:fixed;bottom:0;left:0}.d-none{display:none!important}.banner-balloon_right{position:fixed;bottom:0;right:0}.div_banner{position:fixed;bottom:0;left:0;right:0;z-index:9999999}.div_banner .banner-ads{position:unset}.banner-catfish_1{position:fixed;bottom:0;left:0;right:0}.banner-balloon_left .banner-url img.img-fluid,.banner-balloon_right .banner-url img.img-fluid{max-width:50vw}@media (min-width:768px){.d-md-block{display:block!important}.d-md-none{display:none!important}}\'; let head = document.head || document.getElementsByTagName(\'head\')[0]; let style = document.createElement(\'style\'); head.appendChild(style); style.type = \'text/css\'; if (style.styleSheet){ style.styleSheet.cssText = css; } else { style.appendChild(document.createTextNode(css));}} let adsDiv = document.createElement("div"); adsDiv.innerHTML = `'. $html .'`; document.body.appendChild(adsDiv); createStyle(); const closes = document.querySelectorAll(".banner-close"); if (closes.length) { for (let i = 0; i < closes.length; i++) { closes[i].addEventListener("click", function() { closes[i].parentElement.remove();})}}';

                header("Content-Type: application/javascript");
                header("Cache-Control: max-age=604800, public");

                echo $script;
                exit();
            }

            return response([], 404);

        }
        return response([], 404);
    }
}
