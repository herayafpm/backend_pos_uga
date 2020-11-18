<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class BarangDistributorController extends ResourceController
{

  protected $format       = 'json';
  protected $modelName    = 'App\Models\BarangDistributorModel';

  public function index()
  {
    $dataGet = $this->request->getGet();
    $limit = $dataGet["limit"] ?? 10;
    $offset = $dataGet["offset"] ?? 0;
    $search = $dataGet["search"] ?? '';
    $barang = $this->model->getBarang($limit, $offset, null, $search);
    return $this->respond(["status" => 1, "message" => "berhasil mengambil data barang", "data" => $barang], 200);
  }
  public function static()
  {
    $jenisBarangModel = new \App\Models\JenisBarangModel();
    $jeniss = $jenisBarangModel->get()->getResultArray();
    $ukuranBarangModel = new \App\Models\UkuranBarangModel();
    $ukurans = $ukuranBarangModel->get()->getResultArray();
    return $this->respond(["status" => 1, "message" => "berhasil mengambil data barang", "data" => compact('jeniss', 'ukurans')], 200);
  }
  public function show($id = NULL)
  {
    $barang = $this->model->getBarang(null, null, $id);
    return $this->respond(["status" => 1, "message" => "berhasil mengambil data barang", "data" => $barang], 200);
  }

  public function create()
  {
    $validation =  \Config\Services::validation();
    $createBarangRule = [
      'nama_barang' => [
        'label'  => 'Nama Barang',
        'rules'  => 'required',
        'errors' => [
          'required' => '{field} tidak boleh kosong'
        ]
      ],
      'jenis_barang_id' => [
        'label'  => 'Jenis Barang',
        'rules'  => 'required',
        'errors' => [
          'required' => '{field} tidak boleh kosong'
        ]
      ],
      'ukuran_barang_id' => [
        'label'  => 'Ukuran Barang',
        'rules'  => 'required',
        'errors' => [
          'required' => '{field} tidak boleh kosong'
        ]
      ],
      'stok' => [
        'label'  => 'Stok',
        'rules'  => 'required',
        'errors' => [
          'required' => '{field} tidak boleh kosong'
        ]
      ],
      'harga_dasar' => [
        'label'  => 'Harga Dasar',
        'rules'  => 'required',
        'errors' => [
          'required' => '{field} tidak boleh kosong'
        ]
      ],
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
      'foto' => $dataJson->foto ?? 'kosong.png',
      'nama_barang' => htmlspecialchars($dataJson->nama_barang ?? ''),
      'jenis_barang_id' => htmlspecialchars($dataJson->jenis_barang_id ?? ''),
      'ukuran_barang_id' => htmlspecialchars($dataJson->ukuran_barang_id ?? ''),
      'stok' => htmlspecialchars($dataJson->stok ?? ''),
      'harga_dasar' => htmlspecialchars($dataJson->harga_dasar ?? ''),
      'harga_jual' => htmlspecialchars($dataJson->harga_jual ?? ''),
      'keterangan' => htmlspecialchars($dataJson->keterangan ?? ''),
    ];
    $validation->setRules($createBarangRule);
    if (!$validation->run($data)) {
      return $this->respond(["status" => 0, "message" => "validasi error", "data" => $validation->getErrors()], 400);
    }
    try {
      helper('upload_file');
      $foto = upload_file($data['foto']);
      $data['foto'] = $foto;
      $create = $this->model->save($data);
      if ($create) {
        $riwayatStokBarangDistributorModel = new \App\Models\RiwayatStokBarangDistributorModel();
        $riwayatStokBarangDistributorModel->save(['stok_sekarang' => $data['stok'], 'stok_perubahan' => $data['stok'], 'barang_id' => $this->model->getLastId(), 'keterangan' => 'stok pertama']);
        return $this->respond(["status" => 1, "message" => "barang berhasil ditambah", "data" => []], 200);
      } else {
        unlink(FCPATH . $foto);
        return $this->respond(["status" => 0, "message" => "barang gagal ditambah", "data" => []], 400);
      }
    } catch (\Exception $e) {
      return $this->respond(["status" => 0, "message" => $e->getMessage(), "data" => []], 400);
    }
  }

  public function update($id = NULL)
  {
    $barang = $this->model->where('id', $id)->get()->getRow();
    if ($barang) {
      $validation =  \Config\Services::validation();
      $updateBarangRule = [
        'nama_barang' => [
          'label'  => 'Nama Barang',
          'rules'  => 'required',
          'errors' => [
            'required' => '{field} tidak boleh kosong'
          ]
        ],
        'jenis_barang_id' => [
          'label'  => 'Jenis Barang',
          'rules'  => 'required',
          'errors' => [
            'required' => '{field} tidak boleh kosong'
          ]
        ],
        'ukuran_barang_id' => [
          'label'  => 'Ukuran Barang',
          'rules'  => 'required',
          'errors' => [
            'required' => '{field} tidak boleh kosong'
          ]
        ],
        'harga_dasar' => [
          'label'  => 'Harga Dasar',
          'rules'  => 'required',
          'errors' => [
            'required' => '{field} tidak boleh kosong'
          ]
        ],
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
        'foto' => $dataJson->foto ?? 'kosong.png',
        'nama_barang' => htmlspecialchars($dataJson->nama_barang ?? ''),
        'jenis_barang_id' => htmlspecialchars($dataJson->jenis_barang_id ?? ''),
        'ukuran_barang_id' => htmlspecialchars($dataJson->ukuran_barang_id ?? ''),
        'harga_dasar' => htmlspecialchars($dataJson->harga_dasar ?? ''),
        'harga_jual' => htmlspecialchars($dataJson->harga_jual ?? ''),
        'keterangan' => htmlspecialchars($dataJson->keterangan ?? ''),
      ];
      $validation->setRules($updateBarangRule);
      if (!$validation->run($data)) {
        return $this->respond(["status" => 0, "message" => "validasi error", "data" => $validation->getErrors()], 400);
      }
      try {
        if ($barang->foto != $data['foto']) {
          helper('upload_file');
          $foto = upload_file($data['foto']);
          $data['foto'] = $foto;
        } else {
          unset($data['foto']);
        }
        $update = $this->model->update($id, $data);
        if ($update) {
          if (isset($data['foto'])) {
            if ($barang->foto != 'kosong.png') {
              unlink(FCPATH . $barang->foto);
            }
          }
          return $this->respond(["status" => 1, "message" => "barang berhasil diubah", "data" => []], 200);
        } else {
          if ($barang->foto != $data['foto']) {
            unlink(FCPATH . $data['foto']);
          }
          return $this->respond(["status" => 0, "message" => "barang gagal diubah", "data" => []], 400);
        }
      } catch (\Exception $e) {
        return $this->respond(["status" => 0, "message" => $e->getMessage(), "data" => []], 400);
      }
    } else {
      return $this->respond(["status" => 0, "message" => "barang tidak ditemukan", "data" => []], 400);
    }
  }
  public function updatestok($id = NULL)
  {
    $barang = $this->model->where('id', $id)->get()->getRow();
    if ($barang) {
      $validation =  \Config\Services::validation();
      $addstokrule = [
        'stok' => [
          'label'  => 'Stok',
          'rules'  => 'required',
          'errors' => [
            'required' => '{field} tidak boleh kosong'
          ]
        ],
      ];
      $dataJson = $this->request->getJson();
      $data = [
        'stok' => $dataJson->stok ?? '',
        'keterangan' => $dataJson->keterangan ?? 'mengubah stok',
      ];
      $validation->setRules($addstokrule);
      if (!$validation->run($data)) {
        return $this->respond(["status" => 0, "message" => "validasi error", "data" => $validation->getErrors()], 400);
      }
      $riwayatStokBarangDistributorModel = new \App\Models\RiwayatStokBarangDistributorModel();
      $createStok = $riwayatStokBarangDistributorModel->save(['stok_sekarang' => $barang->stok, 'stok_perubahan' => $data['stok'], 'barang_id' => $barang->id, 'keterangan' => $data['keterangan']]);
      if ($createStok) {
        $this->model->update($barang->id, ['stok' => (int) $data['stok'] + (int) $barang->stok]);
        return $this->respond(["status" => 1, "message" => "stok barang berhasil diubah", "data" => []], 200);
      } else {
        return $this->respond(["status" => 0, "message" => "stok barang gagal diubah", "data" => []], 400);
      }
    } else {
      return $this->respond(["status" => 0, "message" => "barang tidak ditemukan", "data" => []], 400);
    }
  }
  public function riwayatstok($id = NULL)
  {
    $barang = $this->model->where('id', $id)->get()->getRow();
    if ($barang) {
      $riwayatStokBarangDistributorModel = new \App\Models\RiwayatStokBarangDistributorModel();
      $dataGet = $this->request->getGet();
      $limit = $dataGet["limit"] ?? 10;
      $offset = $dataGet["offset"] ?? 0;
      $stok = $riwayatStokBarangDistributorModel->where('barang_id', $id)->orderBy('id', 'DESC')->get($limit, $offset)->getResultArray();
      return $this->respond(["status" => 1, "message" => "riwayat stok barang berhasil didapatkan", "data" => $stok], 200);
    } else {
      return $this->respond(["status" => 0, "message" => "barang tidak ditemukan", "data" => []], 400);
    }
  }
  public function delete($id = NULL)
  {
    $barang = $this->model->where('id', $id)->get()->getRow();
    if ($barang) {
      $riwayatStokBarangDistributorModel = new \App\Models\RiwayatStokBarangDistributorModel();
      $deleteStok = $riwayatStokBarangDistributorModel->where('barang_id', $barang->id)->delete();
      if ($deleteStok) {
        $delete = $this->model->delete($id);
        if ($delete) {
          if ($barang->foto != 'kosong.png') {
            unlink(FCPATH . $barang->foto);
          }
          return $this->respond(["status" => 1, "message" => "barang berhasil dihapus", "data" => []], 200);
        } else {
          return $this->respond(["status" => 0, "message" => "barang gagal dihapus", "data" => []], 400);
        }
      }
    } else {
      return $this->respond(["status" => 0, "message" => "barang tidak ditemukan", "data" => []], 400);
    }
  }
}
