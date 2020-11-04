<?php namespace App\Models;

use CodeIgniter\Model;

class BarangTokoModel extends Model
{
    protected $table      = 'barang_toko';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';

    protected $allowedFields = ['toko_id','barang_distributor_id','stok','harga_jual','keterangan'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getBarang($tokoId)
    {
        $builder = $this->db->table($this->table);
        $builder->select('barang_distributor.*');
        $builder->select('barang_toko.*');
        $builder->join('barang_distributor', 'barang_distributor.id = barang_toko.barang_distributor_id');
        $builder->where('toko_id',$tokoId);
        $query = $builder->get()->getResultArray();
        return $query;
    }
    
    public function getLastId()
    {
        return $this->db->insertID();
    }
}