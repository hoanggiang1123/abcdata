<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkDirectory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function links () {
        return $this->hasMany(Link::class, 'link_directory_id');
    }

    public $crudNotAccepted = [
        'created_at', 'updated_at', 'id', 'script_url'
    ];

    protected $appends = ['script_url'];

    public function getScriptUrlAttribute () {
        return config('app.proxy_url') . '/api/banner/' . $this->script_id;
    }

    public function listItems ($params = []) {
        $result = [];
        $perPage = isset($params['per_page']) ? (int) $params['per_page'] : 30;

        $orderBy = isset($params['order_by']) ? $params['order_by'] : 'created_at';

        $order = isset($params['order']) ? $params['order'] : 'desc';

        $resp = self::when(isset($params['description'])  && $params['description'] !== '', function ($query) use ($params) {

            return $query->where('description', $params['description']);

        })->when(isset($params['name']) && $params['name'] !== '', function ($query) use ($params) {

            return $query->where('name', $params['name']);

        })->orderBy( $orderBy, $order)->paginate($perPage);

        if ($resp) $result = $resp;

        return $result;
    }


    public function getItemByPosition ($position, $data) {

        $item = [];

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
            return '<div data-position="'. $position .'" class="anhbn-qc anhqc-pc anhqc-'. $position .' anhqc-'. $position .'_1 d-none d-md-block"><span class="dong-anhqc">X</span><a class="anhqc-url" href="'. $data['link'] .'" rel="nofollow" target="_blank"><img class="img-fluid" src="'. $data['image'] .'" alt="balloon_right"></a></div>';
        }
        return '<div data-position="'. $position .'" class="anhbn-qc anhqc-mobile anhqc-'. $position .' anhqc-'. $position .'_1 d-block d-md-none"><span class="dong-anhqc">X</span><a class="anhqc-url" href="'. $data['link'] .'" rel="nofollow" target="_blank"><img class="img-fluid" src="'. $data['image'] .'" alt="catfish"></a></div>';
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

        $adsMobiHtml = '<div class="div_anhqc">'. $bottomLeftMobiHtml . $bottomRightMobiHtml .'</div>';

        $html = $adsHtml . $adsMobiHtml;

        return $html;

    }

}
