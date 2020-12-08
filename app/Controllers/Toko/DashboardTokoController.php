<?php

namespace App\Controllers\Toko;

use CodeIgniter\RESTful\ResourceController;
use App\Models\PenjualanTokoModel;

class DashboardTokoController extends ResourceController
{

  protected $format       = 'json';
  protected $modelName    = 'App\Models\BarangTokoModel';

  public function index()
  {
    $toko = $this->request->toko;
    $penjualanModel = new PenjualanTokoModel();
    $penjualans = $penjualanModel->getPenjualan($toko['id']);
    $modal = 0;
    $totalpenjualan = 0;
    foreach ($penjualans as $penjualan) {
      $modal += ($penjualan['jumlah_barang'] * $penjualan['harga_dasar']);
      $totalpenjualan += ($penjualan['jumlah_barang'] * $penjualan['harga_jual']);
    }
    $data['aset'] = $this->model->totalAset();
    $data['keuntungan'] = $totalpenjualan - $modal;
    return $this->respond(["status" => 1, "message" => "berhasil mengambil data dashboard", "data" => $data], 200);
  }
}
