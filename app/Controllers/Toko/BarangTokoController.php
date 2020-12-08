<?php

namespace App\Controllers\Toko;

use CodeIgniter\RESTful\ResourceController;

class BarangTokoController extends ResourceController
{

  protected $format       = 'json';
  protected $modelName    = 'App\Models\BarangTokoModel';

  public function index()
  {
    $toko = $this->request->toko;
    $dataGet = $this->request->getGet();
    $limit = $dataGet["limit"] ?? 10;
    $offset = $dataGet["offset"] ?? 0;
    $search = $dataGet["search"] ?? "";
    $barangs = $this->model->filter($limit, $offset, null, $toko['id'], $search);
    return $this->respond(["status" => 1, "message" => "berhasil mengambil data barang toko", "data" => $barangs], 200);
  }
  public function show($id = NULL)
  {
    $toko = $this->request->toko;
    $barang = $this->model->filter(null, null, $id, $toko['id']);
    return $this->respond(["status" => 1, "message" => "berhasil mengambil data barang toko", "data" => $barang], 200);
  }
  public function update($id = NULL)
  {
    $validation =  \Config\Services::validation();
    $updateBarangRule = [
      'harga_jual' => [
        'label'  => 'Harga Jual',
        'rules'  => 'required',
        'errors' => [
          'required' => '{field} tidak boleh kosong'
        ]
      ],
      'keterangan' => [
        'label'  => 'Keterangan',
        'rules'  => 'required',
        'errors' => [
          'required' => '{field} tidak boleh kosong'
        ]
      ],
    ];
    $dataJson = $this->request->getJson();
    $data = [
      'harga_jual' => htmlspecialchars($dataJson->harga_jual ?? ''),
      'keterangan' => htmlspecialchars($dataJson->keterangan ?? ''),
    ];
    $validation->setRules($updateBarangRule);
    if (!$validation->run($data)) {
      return $this->respond(["status" => 0, "message" => "validasi error", "data" => $validation->getErrors()], 400);
    }
    $update = $this->model->update($id, $data);
    if ($update) {
      return $this->respond(["status" => 1, "message" => "barang berhasil diubah", "data" => []], 200);
    } else {
      return $this->respond(["status" => 0, "message" => "barang gagal diubah", "data" => []], 400);
    }
  }
  public function delete($id = NULL)
  {
    $data = [
      'stok' => 0
    ];
    $update = $this->model->update($id, $data);
    if ($update) {
      return $this->respond(["status" => 1, "message" => "barang berhasil di ubah stok menjadi 0", "data" => []], 200);
    } else {
      return $this->respond(["status" => 0, "message" => "barang gagal di ubah stok menjadi 0", "data" => []], 400);
    }
  }
}
