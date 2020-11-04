<?php namespace App\Models;

use CodeIgniter\Model;

class RiwayatStokBarangDistributorModel extends Model
{
    protected $table      = 'riwayat_stok_barang_distributor';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';

    protected $allowedFields = ['barang_id','stok_sekarang','stok_perubahan','keterangan'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}