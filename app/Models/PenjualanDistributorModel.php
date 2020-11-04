<?php namespace App\Models;

use CodeIgniter\Model;
use App\Models\BarangDistributorModel;
class PenjualanDistributorModel extends Model
{
    protected $table      = 'penjualan_distributor';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';

    protected $allowedFields = ['transaksi_penjualan_distributor_id','barang_distributor_id','jumlah_barang','harga_jual'];

    protected $useTimestamps = false;
    protected $beforeInsert = ['setStok'];
    protected $beforeUpdate = ['setStok'];

    public function getPenjualan($transaksiId)
    {
        $builder = $this->db->table($this->table);
        $builder->select('penjualan_distributor.*');
        $builder->select('barang_distributor.id as barang_distributor_id,barang_distributor.nama_barang,barang_distributor.foto');
        $builder->join('barang_distributor', 'barang_distributor.id = penjualan_distributor.barang_distributor_id');
        $builder->where('transaksi_penjualan_distributor_id',$transaksiId);
        $penjualans = $builder->get()->getResultArray();
        return $penjualans;
    }

    protected function setStok(array $data)
    {
        $barangModel = new BarangDistributorModel();
        $barang = $barangModel->where('id',$data['data']['barang_distributor_id'])->get()->getRow();
        $stok = (int) $barang->stok - (int) $data['data']['jumlah_barang'];

        $riwayatStokBarangDistributorModel = new \App\Models\RiwayatStokBarangDistributorModel();
        $riwayatStokBarangDistributorModel->save(['stok_sekarang' => $barang->stok,'stok_perubahan' => $data['data']['jumlah_barang'],'barang_id' => $barang->id,'keterangan' => 'transaksi penjualan']);

        $barangModel->where('id',$barang->id)->set(['stok'=>$stok])->update();
        return $data;
    }
    public function getTotalHarga($transaksiId)
    {
        $penjualans = $this->where('transaksi_penjualan_distributor_id',$transaksiId)->get()->getResultArray();
        $totalHarga = 0;
        foreach ($penjualans as $penjualan) {
            $totalHargaBarang = (int) $penjualan['jumlah_barang'] * (int) $penjualan['harga_jual'];
            $totalHarga += $totalHargaBarang;
        }
        return $totalHarga;
    }
}