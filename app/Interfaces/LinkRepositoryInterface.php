<?php

namespace App\Interfaces;

interface LinkRepositoryInterface {

    public function saveItem($params, $id = null);

    public function listItems($params);

    public function getItemById($id);

    public function uploadFile();
}
