<?php

namespace App\Models;

use App\Traits\Upload;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory, Upload;

    protected $guarded = [];

    public $crudNotAccepted = [
        'created_at', 'updated_at', 'id', 'slug', 'category', 'redirector', 'deleted_at'
    ];

    public $uploadFolder = 'Link';

    public $uploadField = 'image';

    public function medias () {
        return $this->morphToMany(Media::class, 'mediaable');
    }

    public function linkHits() {
        return $this->hasMany(LinkHit::class, 'link_id');
    }

    public function category () {
        return $this->belongsTo(LinkDirectory::class, 'link_directory_id');
    }

    protected $appends = ['redirector'];

    public function getRedirectorAttribute () {
        return config('app.proxy_url') . '/api/redirector/' . $this->slug;
    }

    public function listItems ($params = []) {
        $result = [];
        $perPage = isset($params['per_page']) ? (int) $params['per_page'] : 30;

        $orderBy = isset($params['order_by']) ? $params['order_by'] : 'created_at';

        $order = isset($params['order']) ? $params['order'] : 'desc';

        $resp = self::when(isset($params['directory']) && $params['directory'] !== '', function ($query) use ($params) {

            return $query->where('link_directory_id', $params['directory']);

        })->when(isset($params['description'])  && $params['description'] !== '', function ($query) use ($params) {

            return $query->where('description', $params['description']);

        })->when(isset($params['status']) && $params['status'] !== '', function ($query) use ($params) {

            return $query->where('status', $params['status']);

        })->when(isset($params['position']) && $params['position'] !== '', function ($query) use ($params) {

            return $query->where('position', $params['position']);

        })->when(isset($params['link']) && $params['link'] !== '', function ($query) use ($params) {

            return $query->where('link', $params['link']);

        })->with('category')->orderBy($orderBy, $order)->paginate($perPage);

        if ($resp) $result = $resp;

        return $result;
    }

    public function upLoadFile () {
        return $this->upLoadFileSingle($this->uploadFolder, $this->uploadField);
    }
}
