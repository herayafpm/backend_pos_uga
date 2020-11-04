<?php namespace App\Models;

use CodeIgniter\Model;

class DistributorModel extends Model
{
    protected $table      = 'distributor';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';

    protected $allowedFields = ['nama_distributor'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getDistributor()
    {
        $builder = $this->db->table($this->table);
        $builder->select('distributor.*');
        $builder->select('users.id as user_id,users.nama as nama_user');
        $builder->join('users', 'users.id = distributor.user_id');
        $query = $builder->get()->getRow();
        return $query;
    }
    
}