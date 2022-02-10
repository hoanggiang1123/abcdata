<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class EnterLink extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $crudNotAccepted = [
        'created_at', 'updated_at', 'id', 'slug', 'folder', 'redirector', 'deleted_at', 'init_vote_count'
    ];

    protected $table = 'enter_links';

    protected $appends = ['redirector'];

    public function getRedirectorAttribute () {
        return config('app.proxy_url') . '/api/redirector/link/' . $this->slug;
    }

    public function folder () {

        return $this->belongsTo(Folder::class, 'folder_id');
    }

    public function enterLinkHits () {
        return $this->hasMany(EnterLinkHit::class, 'enter_link_id');
    }

    public function listItems ($params) {

        $result = [];

        $perPage = isset($params['per_page']) ? (int) $params['per_page'] : 30;

        $orderBy = isset($params['order_by']) ? $params['order_by'] : 'created_at';

        $order = isset($params['order']) ? $params['order'] : 'desc';

        $resp = self::with('folder')->when(isset($params['folder']) && $params['folder'] !== '', function ($query) use ($params) {

            return $query->where('folder_id', $params['folder']);

        })->when(isset($params['name'])  && $params['name'] !== '', function ($query) use ($params) {

            return $query->where('name', $params['name']);

        })->when(isset($params['link']) && $params['link'] !== '', function ($query) use ($params) {

            return $query->where('link', $params['link']);

        })->when(isset($params['feature']) && $params['feature'] !== '', function ($query) use ($params) {

            return $query->where('feature', $params['feature']);

        })->when(isset($params['status']) && $params['status'] !== '', function ($query) use ($params) {

            return $query->where('status', $params['status']);

        });

        if (isset($params['folder']) && $params['folder'] !== '') {

            $resp = $resp->orderBy('blocked', 'asc')->orderBy(DB::raw("`vote_count` + `init_vote_count`"), 'desc')->orderBy('feature', 'desc')->orderBy('id', 'asc')->paginate($perPage);

        } else {

            $resp = $resp->orderBy($orderBy, $order)->paginate($perPage);
        }

        if ($resp) $result = $resp;

        return $result;
    }
}
