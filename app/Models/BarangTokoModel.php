<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangTokoModel extends Model
{
    protected $table      = 'barang_toko';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';

    protected $allowedFields = ['toko_id', 'barang_distributor_id', 'stok', 'harga_dasar', 'harga_jual', 'keterangan'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    public function filter($limit = 10, $offset = 0, $id = null, $tokoId = null, $search = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select("{$this->table}.*");
        $builder->select("barang_distributor.nama_barang,barang_distributor.foto,barang_distributor.jenis_barang_id,barang_distributor.ukuran_barang_id");
        $builder->select("ukuran_barang.nama as ukuran_barang_nama");
        $builder->select("jenis_barang.nama as jenis_barang_nama");
        $builder->join("barang_distributor", "barang_distributor.id = {$this->table}.barang_distributor_id");
        $builder->join("jenis_barang", "jenis_barang.id = barang_distributor.jenis_barang_id");
        $builder->join("ukuran_barang", "ukuran_barang.id = barang_distributor.ukuran_barang_id");
        $builder->where("toko_id", $tokoId);
        if ($id != null) {
            $builder->where("{$this->table}.id", $id);
            $query = $builder->get()->getRow();
        } else {
            if ($search != null) {
                $builder->like("barang_distributor.nama_barang", $search);
            }
            $builder->limit($limit, $offset);
            $query = $builder->get()->getResultArray();
        }
        return $query;
    }

    public function getBarang($limit = 10, $offset = 0, $id = null, $tokoId = null, $search = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select("{$this->table}.*");
        $builder->select("barang_distributor.foto,barang_distributor.nama_barang,barang_distributor.jenis_barang_id,barang_distributor.ukuran_barang_id,");
        $builder->select("ukuran_barang.nama as ukuran_barang_nama");
        $builder->select("jenis_barang.nama as jenis_barang_nama");
        $builder->join("barang_distributor", "barang_distributor.id = {$this->table}.barang_distributor_id");
        $builder->join("jenis_barang", "jenis_barang.id = barang_distributor.jenis_barang_id");
        $builder->join("ukuran_barang", "ukuran_barang.id = barang_distributor.ukuran_barang_id");
        $builder->where("toko_id", $tokoId);
        if ($id != null) {
            $builder->where("{$this->table}.id", $id);
            $query = $builder->get()->getRow();
        } else {
            if ($search != null) {
                $builder->like("barang_distributor.nama_barang", $search);
            }
            $builder->limit($limit, $offset);
            $query = $builder->get()->getResultArray();
        }
        return $query;
    }
    public function totalAset()
    {
        $builder = $this->db->table($this->table);
        $builder->select("harga_jual,stok");
        $query = $builder->get()->getResultArray();
        $total = 0;
        if ($query) {
            foreach ($query as $q) {
                $total += $q['harga_jual'] * $q['stok'];
            }
        }
        return $total;
    }
    public function getLastId()
    {
        return $this->insertID();
    }
}
