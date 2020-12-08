<?php

namespace App\Controllers\Toko;

use CodeIgniter\RESTful\ResourceController;
use App\Models\PenjualanTokoModel;
use App\Models\BarangTokoModel;

class TransaksiPenjualanTokoController extends ResourceController
{

  protected $format       = 'json';
  protected $modelName    = 'App\Models\TransaksiPenjualanTokoModel';

  public function index()
  {
    $toko = $this->request->toko;
    $dataGet = $this->request->getGet();
    $limit = $dataGet["limit"] ?? 10;
    $offset = $dataGet["offset"] ?? 0;
    $orderby = $dataGet["orderby"] ?? 'id';
    $ordered = $dataGet["ordered"] ?? 'desc';
    $params = [];
    $params['where'] = ['transaksi_penjualan_toko.toko_id' => $toko['id']];
    if ($this->request->karyawan != null) {
      $karyawan = $this->request->karyawan;

      $params['where']['transaksi_penjualan_toko.karyawan_id'] = $karyawan['id'];
    }
    $transaksis = $this->model->filter($limit, $offset, $orderby, $ordered, $params);
    return $this->respond(["status" => 1, "message" => "berhasil mengambil transaksi", "data" => $transaksis], 200);
  }

  public function create()
  {
    $toko = $this->request->toko;
    $karyawan = $this->request->karyawan;
    $dataJson = $this->request->getJson();
    $data = [
      'toko_id' => $toko['id'],
      'karyawan_id' => $karyawan['id'],
    ];
    $create = $this->model->save($data);
    if ($create) {
      $transaksi_id = $this->model->getLastId();
      $penjualanModel = new PenjualanTokoModel();
      $barangModel = new BarangTokoModel();
      foreach ($dataJson->barang as $barang) {
        $b = $barangModel->where("id", $barang->id)->get()->getRow();
        $dataPenjualan = [
          'transaksi_penjualan_toko_id' => $transaksi_id,
          'barang_toko_id' => $barang->id,
          'jumlah_barang'         => $barang->jumlah_barang,
          'harga_jual'            => $barang->harga_jual,
          'harga_dasar'            => $b->harga_dasar,
        ];
        $penjualanModel->save($dataPenjualan);
      }
      $totalHarga = $penjualanModel->getTotalHarga($transaksi_id);
      $update = $this->model->where('id', $transaksi_id)->set(['total_bayar' => $totalHarga, 'bayar' => $dataJson->bayar])->update();
      if ($update) {
        return $this->respond(["status" => 1, "message" => "transaksi berhasil", "data" => []], 200);
      } else {
        $this->model->delete($transaksi_id);
        return $this->respond(["status" => 0, "message" => "transaksi gagal", "data" => []], 400);
      }
    } else {
      return $this->respond(["status" => 0, "message" => "transaksi gagal", "data" => []], 400);
    }
  }
}
