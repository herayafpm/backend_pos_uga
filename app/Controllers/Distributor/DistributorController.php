<?php

namespace App\Controllers\Distributor;

use CodeIgniter\RESTful\ResourceController;

class DistributorController extends ResourceController
{

  protected $format       = 'json';
  protected $modelName    = 'App\Models\DistributorModel';

  public function index()
  {
    $distributor = $this->model->getDistributor();
    return $this->respond(["status" => 1, "message" => "berhasil mengambil data distributor", "data" => $distributor], 200);
  }

  public function updateDistributor()
  {
    $distributor = $this->model->getDistributor();
    $validation =  \Config\Services::validation();
    $createKaryawanRule = [
      'nama_distributor' => [
        'label'  => 'Nama Distributor',
        'rules'  => 'required',
        'errors' => [
          'required' => '{field} tidak boleh kosong'
        ]
      ],
    ];
    $dataJson = $this->request->getJson();
    $data = [
      'nama_distributor' => htmlspecialchars($dataJson->nama_distributor ?? ''),
    ];
    $validation->setRules($createKaryawanRule);
    if (!$validation->run($data)) {
      return $this->respond(["status" => 0, "message" => "validasi error", "data" => $validation->getErrors()], 400);
    }
    $update = $this->model->update($distributor->id, $data);
    if ($update) {
      return $this->respond(["status" => 1, "message" => "distributor berhasil diubah", "data" => []], 200);
    } else {
      return $this->respond(["status" => 0, "message" => "distributor gagal diubah", "data" => []], 400);
    }
  }
}
