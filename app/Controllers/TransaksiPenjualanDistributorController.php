<?php

namespace App\Controllers;

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
    $transaksis = $this->model->getTransasksi();
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
          $stok = (int) $barangToko->stok + (int) $jumlahBarang;
          $barangTokoModel->where('id', $barangToko->id)->set(['stok' => $stok])->update();
        } else {
          $barang = $barangDistributorModel->where('id', $barang->id)->get()->getRow();
          $dataToko = [
            'toko_id' => $dataJson->toko_id,
            'barang_distributor_id' => $barang->id,
            'harga_jual' => $barang->harga_jual,
            'stok' => $jumlahBarang,
            'keterangan' => $barang->keterangan,
          ];
          $barangTokoModel->save($dataToko);
        }
      }
      $totalHarga = $penjualanModel->getTotalHarga($transaksi_id);
      $update = $this->model->where('id', $transaksi_id)->set(['total_bayar' => $totalHarga])->update();
      if ($update) {
        $this->model->setStatus($transaksi_id, $dataJson->bayar ?? 0, $dataJson->keterangan ?? "Transaksi Awal");
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
