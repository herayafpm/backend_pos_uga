<?php namespace App\Models;

use CodeIgniter\Model;

class DifabelModel extends Model
{
    protected $table      = 'difabels';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';

    protected $allowedFields = ['pendaftar_id','nik','no_kk','nama', 'tempat','tanggal','jenis','agama','no_telp','no_dtks','jenis_disabilitas','alat_bantu','fasilitas_kesehatan','keterampilan','organisasi','pekerjaan','kebutuhan_pelatihan','kebutuhan_perawatan','kondisi_difabel','permasalahan','alamat','desa','rt','rw','kecamatan','kabupaten','status','kondisi_orang_tua','foto'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    public function insertData($data)
    {
        return $this->save($data);
    }
    public function getDif($nik)
    {
        return $this->where('nik',$nik)->first();
    }
    public function getDifabel($where)
    {
        return $this->where($where)->first();
    }
    public function getParent($nik)
    {
        return $this->where('nik',$nik)->first();
    }
    public function getDifabels($id,$limit,$offset)
    {
        $parentModel = new \App\Models\ParentModel();
        $difabels = $this->where('pendaftar_id',$id)->orderBy('id','desc')->get($limit,$offset)->getResultArray();
        $db = \Config\Database::connect();
        $jenisKelamins = $db->table('jenis_kelamin')->get()->getResultArray();
        $no = 0;
        foreach ($difabels as $difabel) {
            foreach ($jenisKelamins as $jenis) {
                if(strtolower($jenis['nama']) == "laki - laki"){
                    $ayah = $parentModel->where(["no_kk" => $difabel["no_kk"],"jenis" => $jenis['id']])->first(); 
                }else if(strtolower($jenis['nama']) == "perempuan"){
                    $ibu = $parentModel->where(['no_kk'=>$difabel['no_kk'],'jenis' => $jenis['id']])->first(); 
                }
            }
            $difabels[$no]['foto'] = "uploads/".$difabel['foto'];
            $difabels[$no]['ayah'] = $ayah;
            $difabels[$no]['ibu'] = $ibu;
            $no++;
        }
        return $difabels;
    }
    public function countDifabels()
    {
        return $this->countAll();
    }
}