<?php

namespace App\Repositories;

use App\Interfaces\LinkRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Link;
use App\Traits\Upload;

class LinkRepository extends BaseRepository implements LinkRepositoryInterface {

    use Upload;

    public function __construct(Link $product)
    {
        parent::__construct($product);
    }

    public function listItems ($params = []) {

        $result = [];

        $perPage = isset($params['per_page']) ? (int) $params['per_page'] : 30;

        $orderBy = isset($params['order_by']) ? $params['order_by'] : 'created_at';

        $order = isset($params['order']) ? $params['order'] : 'desc';

        $resp = $this->model->with('category')->orderBy( $orderBy, $order)->paginate($perPage);

        if ($resp) $result = $resp;

        return $result;
    }

    public function getItemById($id)
    {
        return $this->model->where('id', $id)->with('linkHits')->first();
    }

    public function uploadFile () {
        return $this->upLoadFileSingle($this->model->uploadFoler, $this->model->uploadField);
    }

}
