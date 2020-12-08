<?php

namespace App\Controllers\Toko;

use CodeIgniter\RESTful\ResourceController;

class PenjualanTokoController extends ResourceController
{

  protected $format       = 'json';
  protected $modelName    = 'App\Models\PenjualanTokoModel';

  public function index()
  {
    $toko = $this->request->toko;
    $penjualans = $this->model->getPenjualan($toko['id']);
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
