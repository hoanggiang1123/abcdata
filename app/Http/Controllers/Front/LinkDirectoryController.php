<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\LinkDirectory;
use App\Models\Link;

class LinkDirectoryController extends Controller
{

    protected $model;

    public function __construct(LinkDirectory $linkDirectory)
    {
        $this->model = $linkDirectory;
    }

    public function show ($script_id) {
        $item = $this->model->where('script_id', $script_id)->first();

        if ($item) {

            $links = Link::where('link_directory_id', $item->id)->get();

            $adsArr = [];

            if ($links && count($links) > 0) {
                foreach ($links as $link) {
                    $adsArr[] = [
                        'link' => config('app.proxy_url') . '/api/redirector/' . $link->slug,
                        'image' => strpos($link->image, 'http') !== false ? $link->image : config('app.proxy_url') .$link->image,
                        'position' => $link->position
                    ];
                }
            }


            if (count($adsArr) > 0) {
                $html = $this->model->generateHtml($adsArr);
                $script = 'function createStyle() { let css = \'.anhqc-float_left{position:fixed;left:0;top:50%;transform:translateY(-50%)}.anhbn-qc{z-index:99994}.dong-anhqc{display:block;font-size:12px;padding:0 8px;cursor:pointer;position:absolute;right:0;background:grey;color:#fff;text-align:center}[class*=\" icofont-\"],[class^=icofont-]{font-family:IcoFont!important;speak:none;font-style:normal;font-weight:400;font-variant:normal;text-transform:none;white-space:nowrap;word-wrap:normal;direction:ltr;line-height:1;-webkit-font-feature-settings:"liga";-webkit-font-smoothing:antialiased}.img-fluid{max-width:100%;height:auto}.anhqc-float_right{position:fixed;right:0;top:50%;transform:translateY(-50%)}.anhqc-balloon_left{position:fixed;bottom:0;left:0}.d-none{display:none!important}.anhqc-balloon_right{position:fixed;bottom:0;right:0}.div_anhqc{position:fixed;bottom:0;left:0;right:0;z-index:99994}.div_anhqc .anhbn-qc{position:unset}.anhqc-catfish_1{position:fixed;bottom:0;left:0;right:0}.anhqc-balloon_left .anhqc-url img.img-fluid,.anhqc-balloon_right .anhqc-url img.img-fluid{max-width:50vw}@media (min-width:768px){.d-md-block{display:block!important}.d-md-none{display:none!important}}\'; let head = document.head || document.getElementsByTagName(\'head\')[0]; let style = document.createElement(\'style\'); head.appendChild(style); style.type = \'text/css\'; if (style.styleSheet){ style.styleSheet.cssText = css; } else { style.appendChild(document.createTextNode(css));}} let adsDiv = document.createElement("div"); adsDiv.innerHTML = `'. $html .'`; setTimeout(() => {document.body.appendChild(adsDiv); const closes = document.querySelectorAll(".dong-anhqc"); if (closes.length) { for (let i = 0; i < closes.length; i++) { closes[i].addEventListener("click", function() { closes[i].parentElement.remove();})}}}, 2000); createStyle();';

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
