<?php namespace App\Models;

use CodeIgniter\Model;

class ParentModel extends Model
{
    protected $table      = 'parents';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';

    protected $allowedFields = ['nik','no_kk','nama', 'tempat','tanggal','jenis','agama','no_telp','alamat','desa','rt','rw','kecamatan','kabupaten'];

    protected $useTimestamps = false;
    public function getParent($nik)
    {
        return $this->where('nik',$nik)->first();
    }
    
}