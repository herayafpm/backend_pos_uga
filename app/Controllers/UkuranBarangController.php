<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\BarangDistributorModel;

class UkuranBarangController extends ResourceController
{

  protected $format       = 'json';
  protected $modelName    = 'App\Models\UkuranBarangModel';

  public function index()
  {
    try {
      $dataGet = $this->request->getGet();
      $search = $dataGet["search"];
      $limit = $dataGet["limit"] ?? 10;
      $offset = $dataGet["offset"] ?? 0;
      $orderby = $dataGet["orderby"] ?? 'id';
      $ordered = $dataGet["ordered"] ?? 'desc';
      $ukuranBarangs = $this->model->filter($search, $limit, $offset, $orderby, $ordered);
      return $this->respond(["status" => 1, "message" => "berhasil mengambil data ukuran barang", "data" => $ukuranBarangs], 200);
    } catch (\Exception $th) {
      return $this->respond(["status" => 0, "message" => $th->getMessage(), "data" => []], 500);
    }
  }
  public function show($id = NULL)
  {
    try {
      $ukuran = $this->model->find($id);
      if (!$ukuran) {
        return $this->respond(["status" => 0, "message" => "ID tidak ditemukan", "data" => []], 400);
      }
      return $this->respond(["status" => 1, "message" => "berhasil mengambil data ukuran barang", "data" => $ukuran], 200);
    } catch (\Exception $th) {
      return $this->respond(["status" => 0, "message" => $th->getMessage(), "data" => []], 500);
    }
  }

  public function create()
  {
    try {
      $validation =  \Config\Services::validation();
      $createUkuranRule = [
        'nama' => [
          'label'  => 'Nama Ukuran Barang',
          'rules'  => 'required',
          'errors' => [
            'required' => '{field} tidak boleh kosong',
          ]
        ],
      ];
      $dataJson = $this->request->getJson();
      $data = [
        'nama' => htmlspecialchars($dataJson->nama ?? ''),
      ];
      $validation->setRules($createUkuranRule);
      if (!$validation->run($data)) {
        return $this->respond(["status" => 0, "message" => "validasi error", "data" => $validation->getErrors()], 400);
      }
      $create = $this->model->save($data);
      if ($create) {
        return $this->respond(["status" => 1, "message" => "Ukuran barang berhasil ditambahkan", "data" => []], 200);
      } else {
        return $this->respond(["status" => 0, "message" => "Ukuran barang gagal ditambahkan", "data" => []], 400);
      }
    } catch (\Exception $th) {
      return $this->respond(["status" => 0, "message" => $th->getMessage(), "data" => []], 500);
    }
  }
  public function update($id = NULL)
  {
    try {
      $ukuran = $this->model->find($id);
      if (!$ukuran) {
        return $this->respond(["status" => 0, "message" => "ID tidak ditemukan", "data" => []], 400);
      }
      $validation =  \Config\Services::validation();
      $updateUkuranRule = [
        'nama' => [
          'label'  => 'Nama Ukuran Barang',
          'rules'  => 'required',
          'errors' => [
            'required' => '{field} tidak boleh kosong',
          ]
        ],
      ];
      $dataJson = $this->request->getJson();
      $data = [
        'nama' => htmlspecialchars($dataJson->nama ?? ''),
      ];
      $validation->setRules($updateUkuranRule);
      if (!$validation->run($data)) {
        return $this->respond(["status" => 0, "message" => "validasi error", "data" => $validation->getErrors()], 400);
      }
      $update = $this->model->update($id, $data);
      if ($update) {
        return $this->respond(["status" => 1, "message" => "Ukuran barang {$ukuran['nama']} berhasil diubah", "data" => []], 200);
      } else {
        return $this->respond(["status" => 0, "message" => "Ukuran barang {$ukuran['nama']} gagal diubah", "data" => []], 400);
      }
    } catch (\Exception $th) {
      return $this->respond(["status" => 0, "message" => $th->getMessage(), "data" => []], 500);
    }
  }
  public function delete($id = NULL)
  {
    try {
      $ukuran = $this->model->find($id);
      if (!$ukuran) {
        return $this->respond(["status" => 0, "message" => "ID tidak ditemukan", "data" => []], 400);
      }
      $barangDistributorModel = new BarangDistributorModel();
      $cekInBarangDistri =  $barangDistributorModel->is_using(['where' => ['ukuran_barang_id' => $id]]);
      if ($cekInBarangDistri) {
        return $this->respond(["status" => 0, "message" => "Ukuran barang {$ukuran['nama']} masih digunakan, tidak bisa dihapus", "data" => []], 400);
      }
      $delete = $this->model->delete($id);
      if ($delete) {
        return $this->respond(["status" => 1, "message" => "Ukuran barang {$ukuran['nama']} berhasil dihapus", "data" => []], 200);
      } else {
        return $this->respond(["status" => 0, "message" => "Ukuran barang {$ukuran['nama']} gagal dihapus", "data" => []], 400);
      }
    } catch (\Exception $th) {
      return $this->respond(["status" => 0, "message" => $th->getMessage(), "data" => []], 500);
    }
  }
}
