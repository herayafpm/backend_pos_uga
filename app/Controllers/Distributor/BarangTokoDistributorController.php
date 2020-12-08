<?php

namespace App\Controllers\Distributor;

use CodeIgniter\RESTful\ResourceController;

class BarangTokoDistributorController extends ResourceController
{

  protected $format       = 'json';
  protected $modelName    = 'App\Models\BarangTokoModel';

  public function index()
  {
    $dataGet = $this->request->getGet();
    $limit = $dataGet["limit"] ?? 10;
    $offset = $dataGet["offset"] ?? 0;
    $search = $dataGet["search"] ?? "";
    $barangs = $this->model->filter($limit, $offset, null, $dataGet["toko_id"], $search);
    return $this->respond(["status" => 1, "message" => "berhasil mengambil data barang toko", "data" => $barangs], 200);
  }
}
