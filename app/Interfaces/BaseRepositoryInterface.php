<?php
namespace App\Interfaces;

interface BaseRepositoryInterface {

    public function saveItem($params, $id = null, $addField = null);
}
