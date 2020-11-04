<?php namespace App\Models;

use CodeIgniter\Model;

class KaryawanModel extends Model
{
    protected $table      = 'karyawan';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';

    protected $allowedFields = ['user_id','toko_id'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    public function getKaryawans($tokoId)
    {
        $builder = $this->db->table($this->table);
        $builder->select('karyawan.id as id');
        $builder->select('users.id as user_id,users.nama as nama_user');
        $builder->select('toko.id as toko_id,toko.nama_toko');
        $builder->join('users', 'users.id = karyawan.user_id');
        $builder->join('toko', 'toko.id = karyawan.toko_id');
        $builder->where(['toko_id' => $tokoId]);
        $query = $builder->get()->getResultArray();
        return $query;
    }
    
}