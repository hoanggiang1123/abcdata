<?php

namespace App\Models;

use App\Traits\Upload;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory, Upload;

    protected $guarded = [];

    public $uploadFolder = 'Media';

    public $uploadField = 'image';

    protected $table = 'media';

    public $crudNotAccepted = [
        'created_at', 'updated_at', 'id', 'image_url'
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute () {
        return config('app.proxy_url') . $this->path;
    }

    public function links() {
        return $this->morphedByMany(Link::class, 'mediaable');
    }

    public function upLoadFile () {
        return $this->upLoadFileSingle($this->uploadFolder, $this->uploadField);
    }

    public function listItem ($params) {

        $result = [];

        $perPage = isset($params['per_page']) ? (int) $params['per_page'] : 30;

        $orderBy = isset($params['order_by']) ? $params['order_by'] : 'created_at';

        $order = isset($params['order']) ? $params['order'] : 'desc';

        $resp = self::orderBy($orderBy, $order)->paginate($perPage);

        if ($resp) $result = $resp;

        return $result;
    }
}
