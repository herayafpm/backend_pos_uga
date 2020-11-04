<?php namespace App\Models;

use CodeIgniter\Model;
use App\Models\PelunasanTransaksiDistributorModel;
use App\Models\PenjualanDistributorModel;
class TransaksiPenjualanDistributorModel extends Model
{
    protected $table      = 'transaksi_penjualan_distributor';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';

    protected $allowedFields = ['id','toko_id','total_bayar','bayar','status'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    public function getTransasksi($tokoId = NULL,$limit = 10,$offset = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('transaksi_penjualan_distributor.*');
        $builder->select('toko.id as toko_id,toko.nama_toko');
        $builder->join('toko', 'toko.id = transaksi_penjualan_distributor.toko_id');
        if($tokoId != null){
            $builder->where(['toko_id' => $tokoId]);
        }
        $builder->orderBy('id','DESC');
        $transaksis = $builder->get($limit,$offset)->getResultArray();
        $no = 0;
        $penjualanModel = new PenjualanDistributorModel();
        $pelunasanModel = new PelunasanTransaksiDistributorModel();
        foreach ($transaksis as $transaksi) {
            $transaksis[$no]['penjualan'] = $penjualanModel->getPenjualan($transaksi['id']);
            $transaksis[$no]['pelunasan'] = $pelunasanModel->getPelunasan($transaksi['id']);
            $no++;
        }
        return $transaksis;
    }

    public function setStatus($transaksiId,$bayar,$keterangan)
    {
        $transaksi = $this->where('id',$transaksiId)->get()->getRow();
        $bayarSekarang = (int) $transaksi->bayar + (int) $bayar;
        if((int) $bayarSekarang >= (int) $transaksi->total_bayar){
            $this->where('id',$transaksiId)->set(['status' => 1,'bayar' => $bayarSekarang])->update();
        }else{
            $this->where('id',$transaksiId)->set(['status' => 0,'bayar' => $bayarSekarang])->update();
        }
        $pelunasan = new PelunasanTransaksiDistributorModel();
        $dataPelunasan = [
            'transaksi_penjualan_id' => $transaksiId,
            'bayar_sebelumnya' => $transaksi->bayar,
            'bayar' => $bayar,
            'keterangan' => $keterangan
        ];
        $pelunasan->save($dataPelunasan);
    }
    public function getLastId()
    {
        return $this->db->insertID();
    }
}