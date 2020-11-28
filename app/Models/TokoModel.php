<?php

namespace App\Models;

use CodeIgniter\Model;

class TokoModel extends Model
{
    protected $table      = 'toko';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';

    protected $allowedFields = ['user_id', 'nama_toko', 'email', 'alamat', 'no_telp', 'status'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function filter($search, $limit, $start, $order_field, $order_ascdesc, $params = [])
    {
        $builder = $this->db->table($this->table);
        $builder->orderBy($order_field, $order_ascdesc); // Untuk menambahkan query ORDER BY
        $builder->limit($limit, $start); // Untuk menambahkan query LIMIT
        $builder->select('toko.*');
        $builder->select('users.nama as pemilik_toko');
        $builder->join('users', 'users.id = toko.user_id');
        if (isset($params['where'])) {
            $builder->where($params['where']);
        }
        $search = strtolower($search);
        $builder->like($this->allowedFields[1], $search);
        if (isset($params['like'])) {
            foreach ($params['like'] as $key => $value) {
                $builder->like($key, $value);
            }
        }
        $datas = $builder->get()->getResultArray();
        return $datas; // Eksekusi query sql sesuai kondisi diatas
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
    public function count_filter($search, $params = [])
    {
        $builder = $this->db->table($this->table);
        if (isset($params['where'])) {
            $builder->where($params['where']);
        }
        $search = strtolower($search);
        $builder->like($this->allowedFields[1], $search);
        if (isset($params['like'])) {
            foreach ($params['like'] as $key => $value) {
                $builder->like($key, $value);
            }
        }
        $data = $builder->countAllResults();
        return $data;
    }
    public function tokos($limit = 10, $offset = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('toko.*');
        $builder->select('users.nama as pemilik_toko');
        $builder->join('users', 'users.id = toko.user_id');
        $builder->limit($limit, $offset);
        $query = $builder->get()->getResultArray();
        return $query;
    }
}
