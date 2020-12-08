<?php

namespace App\Controllers\Distributor;

use CodeIgniter\RESTful\ResourceController;
use App\Models\PenjualanDistributorModel;
use App\Models\BarangDistributorModel;
use App\Models\BarangTokoModel;

class TransaksiPenjualanDistributorController extends ResourceController
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
    if (isset($dataGet['toko_id']) && !empty($dataGet['toko_id'])) {
      $params['where'] = ['toko_id' => $dataGet['toko_id']];
    }
    $transaksis = $this->model->filter($limit, $offset, $orderby, $ordered, $params);
    return $this->respond(["status" => 1, "message" => "berhasil mengambil transaksi", "data" => $transaksis], 200);
  }

  public function create()
  {
    $dataJson = $this->request->getJson();
    $data = [
      'toko_id' => $dataJson->toko_id,
    ];
    $create = $this->model->save($data);
    if ($create) {
      $transaksi_id = $this->model->getLastId();
      $penjualanModel = new PenjualanDistributorModel();
      $barangTokoModel = new BarangTokoModel();
      $barangDistributorModel = new BarangDistributorModel();
      foreach ($dataJson->barang as $barang) {
        $b = $barangDistributorModel->where('id', $barang->id)->get()->getRow();
        $dataPenjualan = [
          'transaksi_penjualan_distributor_id' => $transaksi_id,
          'barang_distributor_id' => $barang->id,
          'jumlah_barang'         => $barang->jumlah_barang,
          'harga_jual'            => $barang->harga_jual
        ];
        $penjualanModel->save($dataPenjualan);
        // add barang to toko
        $jumlahBarang = $barang->jumlah_barang;
        $barangToko = $barangTokoModel->where('barang_distributor_id', $barang->id)->get()->getRow();
        if ($barangToko) {
          if ($jumlahBarang < 0) {
            $stok = (int) $barangToko->stok - (int) $jumlahBarang;
          } else {
            $stok = (int) $barangToko->stok + (int) $jumlahBarang;
          }
          $barangTokoModel->where('id', $barangToko->id)->set(['stok' => $stok])->update();
        } else {
          $stok = (int) -$jumlahBarang;
          $dataToko = [
            'toko_id' => $dataJson->toko_id,
            'barang_distributor_id' => $barang->id,
            'harga_dasar' => $barang->harga_jual,
            'harga_jual' => $barang->harga_jual,
            'stok' => $stok,
            'keterangan' => $b->keterangan,
          ];
          $barangTokoModel->save($dataToko);
        }
      }
      $totalHarga = $penjualanModel->getTotalHarga($transaksi_id);
      $update = $this->model->where('id', $transaksi_id)->set(['total_bayar' => $totalHarga])->update();
      if ($update) {
        $this->model->setStatus($transaksi_id, $dataJson->bayar ?? 0, $dataJson->keterangan ?? "Penjualan");
        return $this->respond(["status" => 1, "message" => "transaksi berhasil", "data" => []], 200);
      } else {
        $this->model->delete($transaksi_id);
        return $this->respond(["status" => 0, "message" => "transaksi gagal", "data" => []], 400);
      }
    } else {
      return $this->respond(["status" => 0, "message" => "transaksi gagal", "data" => []], 400);
    }
  }
  public function pelunasan($transaksiId = NULL)
  {
    $dataJson = $this->request->getJson();
    $this->model->setStatus($transaksiId, $dataJson->bayar ?? 0, $dataJson->keterangan ?? "Pelunasan Transaksi");
    return $this->respond(["status" => 1, "message" => "berhasil menambah pelunasan", "data" => []], 200);
  }
}
