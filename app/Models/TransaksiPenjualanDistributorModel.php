<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\PelunasanTransaksiDistributorModel;
use App\Models\PenjualanDistributorModel;

class TransaksiPenjualanDistributorModel extends Model
{
    protected $table      = 'transaksi_penjualan_distributor';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';

    protected $allowedFields = ['id', 'toko_id', 'total_bayar', 'bayar', 'status'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    public function filter($limit, $start, $order_field, $order_ascdesc, $params = [])
    {
        $builder = $this->db->table($this->table);
        $builder->orderBy($order_field, $order_ascdesc); // Untuk menambahkan query ORDER BY
        $builder->limit($limit, $start); // Untuk menambahkan query LIMIT
        $builder->select("{$this->table}.*");
        $builder->select('toko.id as toko_id,toko.nama_toko');
        $builder->join('toko', "toko.id = {$this->table}.toko_id", 'LEFT');
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
        $penjualanModel = new PenjualanDistributorModel();
        $pelunasanModel = new PelunasanTransaksiDistributorModel();
        foreach ($datas as $data) {
            $datas[$no]['penjualan'] = $penjualanModel->getPenjualan($data['id']);
            $datas[$no]['pelunasan'] = $pelunasanModel->getPelunasan($data['id']);
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

    public function getTransaksi($tokoId = NULL, $limit = 10, $offset = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('transaksi_penjualan_distributor.*');
        $builder->select('toko.id as toko_id,toko.nama_toko');
        $builder->join('toko', 'toko.id = transaksi_penjualan_distributor.toko_id');
        if ($tokoId != null) {
            $builder->where(['toko_id' => $tokoId]);
        }
        $builder->orderBy('id', 'DESC');
        $transaksis = $builder->get($limit, $offset)->getResultArray();
        $no = 0;
        $penjualanModel = new PenjualanDistributorModel();
        $pelunasanModel = new PelunasanTransaksiDistributorModel();
        foreach ($transaksis as $transaksi) {
            $transaksis[$no]['penjualan'] = $penjualanModel->getPenjualan($transaksi['id']);
            $transaksis[$no]['pelunasan'] = $pelunasanModel->getPelunasan($transaksi['id']);
            $no++;
        }
        return $transaksis;
    }

    public function setStatus($transaksiId, $bayar, $keterangan)
    {
        $transaksi = $this->where('id', $transaksiId)->get()->getRow();
        $bayarSekarang = (int) $transaksi->bayar + (int) $bayar;
        if ((int) $bayarSekarang >= (int) $transaksi->total_bayar) {
            $this->where('id', $transaksiId)->set(['status' => 1, 'bayar' => $bayarSekarang])->update();
        } else {
            $this->where('id', $transaksiId)->set(['status' => 0, 'bayar' => $bayarSekarang])->update();
        }
        $pelunasan = new PelunasanTransaksiDistributorModel();
        $dataPelunasan = [
            'transaksi_penjualan_id' => $transaksiId,
            'bayar_sebelumnya' => $transaksi->bayar,
            'bayar' => $bayar,
            'keterangan' => $keterangan
        ];
        $pelunasan->save($dataPelunasan);
    }
    public function getLastId()
    {
        return $this->insertID();
    }
}
