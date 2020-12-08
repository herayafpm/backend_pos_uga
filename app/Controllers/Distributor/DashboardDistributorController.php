<?php

namespace App\Controllers\Distributor;

use CodeIgniter\RESTful\ResourceController;
use App\Models\PenjualanDistributorModel;

class DashboardDistributorController extends ResourceController
{

  protected $format       = 'json';
  protected $modelName    = 'App\Models\BarangDistributorModel';

  public function index()
  {
    $penjualanModel = new PenjualanDistributorModel();
    $penjualans = $penjualanModel->getPenjualan();
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
