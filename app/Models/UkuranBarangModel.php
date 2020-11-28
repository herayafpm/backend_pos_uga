<?php

namespace App\Models;

use CodeIgniter\Model;

class UkuranBarangModel extends Model
{
    protected $table      = 'ukuran_barang';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';

    protected $allowedFields = ['nama'];

    protected $useTimestamps = false;
    public function filter($search, $limit, $start, $order_field, $order_ascdesc, $params = [])
    {
        $builder = $this->db->table($this->table);
        $builder->orderBy($order_field, $order_ascdesc); // Untuk menambahkan query ORDER BY
        $builder->limit($limit, $start); // Untuk menambahkan query LIMIT
        $builder->select('*');
        if (isset($params['where'])) {
            $builder->where($params['where']);
        }
        $search = strtolower($search);
        $builder->like($this->allowedFields[0], $search);
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
        $builder->like($this->allowedFields[0], $search);
        if (isset($params['like'])) {
            foreach ($params['like'] as $key => $value) {
                $builder->like($key, $value);
            }
        }
        $data = $builder->countAllResults();
        return $data;
    }
}
