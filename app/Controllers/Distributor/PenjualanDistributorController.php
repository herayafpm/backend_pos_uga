<?php

namespace App\Controllers\Distributor;

use CodeIgniter\RESTful\ResourceController;

class PenjualanDistributorController extends ResourceController
{

  protected $format       = 'json';
  protected $modelName    = 'App\Models\PenjualanDistributorModel';

  public function index()
  {
    $penjualans = $this->model->getPenjualan();
    $data['modal'] = 0;
    $data['penjualan'] = 0;
    foreach ($penjualans as $penjualan) {
      $data['modal'] += ($penjualan['jumlah_barang'] * $penjualan['harga_dasar']);
      $data['penjualan'] += ($penjualan['jumlah_barang'] * $penjualan['harga_jual']);
    }
    $data['laba'] = $data['penjualan'] - $data['modal'];
    return $this->respond(["status" => 1, "message" => "berhasil mengambil data penjualan", "data" => $data], 200);
  }
}
