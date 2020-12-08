<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\PenjualanTokoModel;

class TransaksiPenjualanTokoModel extends Model
{
  protected $table      = 'transaksi_penjualan_toko';
  protected $primaryKey = 'id';

  protected $returnType     = 'array';

  protected $allowedFields = ['id', 'toko_id', 'karyawan_id', 'total_bayar', 'bayar'];

  protected $useTimestamps = true;
  protected $createdField  = 'created_at';
  protected $updatedField  = '';
  public function filter($limit, $start, $order_field, $order_ascdesc, $params = [])
  {
    $builder = $this->db->table($this->table);
    $builder->orderBy($order_field, $order_ascdesc); // Untuk menambahkan query ORDER BY
    $builder->limit($limit, $start); // Untuk menambahkan query LIMIT
    $builder->select("{$this->table}.*");
    $builder->select('users.nama as karyawan_nama');
    $builder->join('karyawan', "karyawan.id = {$this->table}.karyawan_id", 'LEFT');
    $builder->join('users', "users.id = karyawan.user_id", 'LEFT');
    if (isset($params['where'])) {
      $builder->where($params['where']);
    }
    if (isset($params['like'])) {
      foreach ($params['like'] as $key => $value) {
        $builder->like($key, $value);
      }
    }
    $datas = $builder->get()->getResultArray();
    $no = 0;
    foreach ($datas as $data) {
      $penjualanModel = new PenjualanTokoModel();
      $datas[$no]['penjualan'] = $penjualanModel->getPenjualan(null, $data['id']);
      $no++;
    }
    return $datas;
  }
  public function count_all($params = [])
  {
    $builder = $this->db->table($this->table);
    if (isset($params['where'])) {
      $builder->where($params['where']);
    }
    if (isset($params['like'])) {
      foreach ($params['like'] as $key => $value) {
        $builder->like($key, $value);
      }
    }
    $data = $builder->countAllResults();
    return $data;
  }

  // public function getTransaksi($tokoId = NULL, $limit = 10, $offset = 0)
  // {
  //   $builder = $this->db->table($this->table);
  //   $builder->select('transaksi_penjualan_distributor.*');
  //   $builder->select('toko.id as toko_id,toko.nama_toko');
  //   $builder->join('toko', 'toko.id = transaksi_penjualan_distributor.toko_id');
  //   if ($tokoId != null) {
  //     $builder->where(['toko_id' => $tokoId]);
  //   }
  //   $builder->orderBy('id', 'DESC');
  //   $transaksis = $builder->get($limit, $offset)->getResultArray();
  //   $no = 0;
  //   $penjualanModel = new PenjualanDistributorModel();
  //   foreach ($transaksis as $transaksi) {
  //     $transaksis[$no]['penjualan'] = $penjualanModel->getPenjualan($transaksi['id']);
  //     $no++;
  //   }
  //   return $transaksis;
  // }
  public function getLastId()
  {
    return $this->insertID();
  }
}
