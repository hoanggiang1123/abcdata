<?php
namespace App\Repositories;

use App\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface {

    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }
    public function index() {

    }

    public function saveItem($params, $id = null, $addSlug = false)
    {
        $result = null;

        $saveData = prepareParams($params, $this->model->crudNotAccepted);

        if ($id === null) {

            if ($addSlug) $saveData['slug'] = hexdec(uniqid()) . time();

            $result = $this->model->create($saveData);
        }
        else {

            $result = $this->model->where('id', $id)->update($saveData);
        }

        return $result;
    }
}
