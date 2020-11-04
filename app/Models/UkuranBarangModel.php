<?php namespace App\Models;

use CodeIgniter\Model;

class UkuranBarangModel extends Model
{
    protected $table      = 'ukuran_barang';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';

    protected $allowedFields = ['nama'];

    protected $useTimestamps = false;
    
}