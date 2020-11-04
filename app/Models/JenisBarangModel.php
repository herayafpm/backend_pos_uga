<?php namespace App\Models;

use CodeIgniter\Model;

class JenisBarangModel extends Model
{
    protected $table      = 'jenis_barang';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';

    protected $allowedFields = ['nama'];

    protected $useTimestamps = false;
    
}