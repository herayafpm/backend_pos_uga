<?php

namespace App\Controllers\Toko;

use CodeIgniter\RESTful\ResourceController;

class TanggunganTokoController extends ResourceController
{

  protected $format       = 'json';
  protected $modelName    = 'App\Models\TransaksiPenjualanDistributorModel';

  public function index()
  {
    $dataGet = $this->request->getGet();
    $limit = $dataGet["limit"] ?? 10;
    $offset = $dataGet["offset"] ?? 0;
    $orderby = $dataGet["orderby"] ?? 'id';
    $ordered = $dataGet["ordered"] ?? 'desc';
    $params = [];
    $toko = $this->request->toko;
    $params['where'] = ['toko_id' => $toko['id']];
    $tanggungans = $this->model->filter($limit, $offset, $orderby, $ordered, $params);
    return $this->respond(["status" => 1, "message" => "berhasil mengambil tanggungan", "data" => $tanggungans], 200);
  }
}
