<?php namespace App\Models;

use CodeIgniter\Model;

class TokoModel extends Model
{
    protected $table      = 'toko';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';

    protected $allowedFields = ['user_id','nama_toko','email', 'alamat','no_telp','status'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function tokos($limit = 10, $offset = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('toko.*');
        $builder->select('users.nama as pemilik_toko');
        $builder->join('users', 'users.id = toko.user_id');
        $builder->limit($limit,$offset);
        $query = $builder->get()->getResultArray();
        return $query;
    }
}