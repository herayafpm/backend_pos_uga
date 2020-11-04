<?php namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';

    protected $allowedFields = ['username','nama', 'email','alamat','no_telp','aktif','role_id','password'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];
    protected function hashPassword(array $data)
    {

        if (!isset($data['data']['password'])) return $data;
        $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        return $data;
    }
    public function insertUser($data)
    {
        return $this->save($data);
    }
    public function getUser($username)
    {
        return $this->where('username',$username)->get()->getRow();
    }
    public function getUserById($id)
    {
        return $this->where('id',$id)->get()->getRow();
    }
    public function getUserByEmail($email)
    {
        return $this->where('email',$email)->get()->getRow();
    }
    public function change_pass($id,$password)
    {
        return $this->update($id,['password' => $password]);
    }
    public function getUserWithRole($username)
    {
        $builder = $this->db->table($this->table);
        $builder->select('users.*');
        $builder->select('roles.id as role_id,roles.nama as role_nama');
        $builder->join('roles', 'roles.id = users.role_id');
        $builder->where(['username' => $username]);
        $query = $builder->get()->getRow();
        return $query;
    }
    public function authenticate($username,$password)
    {
        $auth = $this->where('username',$username)->first();
        if($auth){
            if(password_verify($password,$auth['password'])){
                return $this->getUserWithRole($auth['username']);
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    public function getLastId()
    {
        return $this->db->insertID();
    }
}