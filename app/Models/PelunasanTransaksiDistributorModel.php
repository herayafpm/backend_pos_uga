<?php namespace App\Models;

use CodeIgniter\Model;

class PelunasanTransaksiDistributorModel extends Model
{
    protected $table      = 'pelunasan_transaksi_distributor';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';

    protected $allowedFields = ['transaksi_penjualan_id','bayar_sebelumnya','bayar','keterangan'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    public function getPelunasan($transaksiId)
    {
        return $this->where('transaksi_penjualan_id',$transaksiId)->get()->getResultArray();
    }
}