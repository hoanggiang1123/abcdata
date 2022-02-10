<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LinkEmbeded extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public $crudNotAccepted = [
        'created_at', 'updated_at', 'id', 'script_url'
    ];

    protected $appends = ['script_url'];

    public function getScriptUrlAttribute () {
        return config('app.proxy_url') . '/api/link-embed/' . $this->script_id;
    }

    public function getItemByPosition ($position, $data) {

        $item = null;

        for ($i = 0; $i < count($data); $i++) {

            if ($data[$i]['position'] === $position) {

                $item = $data[$i];

                break;
            }
        }

        return $item;
    }

    public function createAdsHtml ($data, $position, $mobile = false) {
        if ($mobile === false) {
            return '<div data-position="'. $position .'" class="banner-ads banner-pc banner-'. $position .' banner-'. $position .'_1 d-none d-md-block"><span class="banner-close">X</span><a class="banner-url" href="'. $data['link'] .'" rel="nofollow" target="_blank"><img class="img-fluid" src="'. $data['image'] .'" alt="balloon_right"></a></div>';
        }
        return '<div data-position="'. $position .'" class="banner-ads banner-mobile banner-'. $position .' banner-'. $position .'_1 d-block d-md-none"><span class="banner-close">X</span><a class="banner-url" href="'. $data['link'] .'" rel="nofollow" target="_blank"><img class="img-fluid" src="'. $data['image'] .'" alt="catfish"></a></div>';
    }

    public function generateHtml ($data) {

        $leftAds = $this->getItemByPosition('middle_left', $data);

        $rightAds = $this->getItemByPosition('middle_right', $data);

        $bottomLeftAds = $this->getItemByPosition('bottom_left', $data);

        $bottomRightAds = $this->getItemByPosition('bottom_right', $data);

        $leftHtml = $rightHtml = $bottomLeftHtml = $bottomRightHtml = $bottomLeftMobiHtml = $bottomRightMobiHtml = "";

        if (count($leftAds)) $leftHtml = $this->createAdsHtml($leftAds, 'float_left');

        if (count($rightAds)) $rightHtml = $this->createAdsHtml($rightAds, 'float_right');

        if (count($bottomLeftAds)) {

            $bottomLeftHtml = $this->createAdsHtml($bottomLeftAds, 'balloon_left');

            $bottomLeftMobiHtml = $this->createAdsHtml($bottomLeftAds, 'catfish', true);
        }

        if (count($bottomRightAds)) {

            $bottomRightHtml = $this->createAdsHtml($bottomRightAds, 'balloon_right');

            $bottomRightMobiHtml = $this->createAdsHtml($bottomRightAds, 'catfish_1', true);
        }

        $adsHtml = $leftHtml . $rightHtml . '<div class="bottom-wrap">'. $bottomLeftHtml . $bottomRightHtml .'</div>';

        $adsMobiHtml = '<div class="div_banner">'. $bottomLeftMobiHtml . $bottomRightMobiHtml .'</div>';

        $html = $adsHtml . $adsMobiHtml;

        return $html;

    }
}
