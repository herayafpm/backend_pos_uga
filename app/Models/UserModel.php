<?php namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';

    protected $allowedFields = ['nik','nama', 'tempat','tanggal','jenis','no_telp','desa','rt','rw','kecamatan','kabupaten','provinsi','status','password'];

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
    public function getUser($nik)
    {
        return $this->where('nik',$nik)->first();
    }
    public function change_pass($id,$password)
    {
        return $this->update($id,['password' => $password]);
    }
    public function authenticate($nik,$password)
    {
        $auth = $this->where('nik',$nik)->first();
        if($auth){
            if(password_verify($password,$auth['password'])){
                return $auth;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}