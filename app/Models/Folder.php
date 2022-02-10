<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Folder extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function enterLinks () {
        return $this->hasMany(EnterLink::class, 'folder_id')->orderBy('feature', 'desc')->orderBy('blocked', 'asc')->orderBy(DB::raw("`vote_count` + `init_vote_count`"), 'desc')->orderBy('id', 'asc');
    }

    public $crudNotAccepted = [
        'created_at', 'updated_at', 'id', 'script_url'
    ];

    protected $appends = ['script_url'];

    public function getScriptUrlAttribute () {
        return config('app.proxy_url') . '/api/link-enter/' . $this->script_id;
    }

    public function getHeaderColorAttribute ($value) {
        return $value === null ? '' : $value;
    }

    public function getTitleColorAttribute ($value) {
        return $value === null ? '' : $value;
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

    public function listItemShortCode ($params) {

        $result = null;

        if (isset($params['ids']) && $params['ids'] !== '') {

            if ($params['ids'] === 'all') {

                $result = self::with('enterLinks')->orderBy('feature', 'desc')->get();
            }
            else {

                $idArr = explode(',', $params['ids']);

                if (isset($idArr) && count($idArr) > 0) {

                    $result = self::with('enterLinks')->whereIn('id', $idArr)->orderBy('feature', 'desc')->get();
                }
            }
        }

        return $result;
    }
}
