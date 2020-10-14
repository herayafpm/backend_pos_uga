<?php namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;

class DashboardController extends ResourceController
{   
    
	protected $format       = 'json';
    protected $modelName    = 'App\Models\DifabelModel';

    public function index()
    {
        $user = $this->request->user;
        $dataGet = $this->request->getGet();
        $limit = $dataGet["limit"] ?? 10;
        $offset = $dataGet["offset"] ?? 0;
        $difabels = $this->model->getDifabels($user['id'],$limit,$offset);
        $current_page = ($offset == 0)?1:($offset/$limit) * $limit;
        $total = $this->model->countDifabels();
        $total_page = ceil($total/$limit);
        return $this->respond([
            "status" => true,
            "message"=>"OK",
            "current_page"=>$current_page,
            "total"=>$total,
            "total_page"=>$total_page,
            "data"=>$difabels], 200);  
    }
    public function show($id = NULL)
    {
        $user = $this->request->user;
        $difabel = $this->model->getDifabel(['id' => $id]);
        if($difabel){
            $parentModel = new \App\Models\ParentModel();
            $difabel['ayah'] = $parentModel->where(['no_kk' => $difabel['no_kk'],'jenis'=>1])->first();
            $difabel['ibu'] = $parentModel->where(['no_kk' => $difabel['no_kk'],'jenis'=>0])->first();
            return $this->respond(["status" => true,"message" => "Data difabel ditemukkan","data" => $difabel],200);
        }else{
            return $this->respond(["status" => false,"message" => "Data difabel tidak ditemukkan"], 400);
        }
    }
    public function cek_status()
    {
        $dataJson = $this->request->getJson();
        $difabel = $this->model->getDifabel(['nik' => $dataJson->nik]);
        if($difabel){
            $message = "";
            if((bool)$difabel['status']){
                $message = "sudah terverivikasi";
            }else{
                $message = "belum diverivikasi, masih dalam proses 2 x 24Jam";
            }
            return $this->respond(["status" => true,"message" =>$message,"data" => (bool) $difabel['status']], 200);
        }else{
            return $this->respond(["status" => false,"message" => "Data difabel tidak ditemukkan"], 400);
        }
    }
    private function insertIsiSendiri($table,$data)
    {
        $db = \Config\Database::connect();
        $result = $db->table($table)->where('id',$data)->get()->getRow();
        if(!$result){
            $resultId = $db->table($table)->select('id')->where('nama',$data)->get()->getRow();
            if($resultId){
                return $resultId->id;
            }else{
                $insertresult = $db->table($table)->insert(['nama' => $data]);
                return $db->insertID();
            }
        }else{
            return $data;
        }
    }
    public function register()
    {
        $user = $this->request->user;
        $dataJson = $this->request->getJson();
        $dataJson->pendaftar_id = $user['id'];
        // upload file
        $fotoData = base64_decode($dataJson->foto);
        var_dump($fotoData);
        die();
        $f = finfo_open();
        $mime_type = finfo_buffer($f, $fotoData, FILEINFO_MIME_TYPE);
        finfo_close($f);
        $acceptType = ['image/jpeg','image/png','image/jpg'];
        if(in_array($mime_type,$acceptType)){
            [$image,$imageType] = explode('/',$mime_type);
            $fileName = uniqid('img_').time()."_".rand(1,100).".$imageType";
            $filePath = FCPATH.'uploads/'.$fileName;
            $image = file_put_contents($filePath, $fotoData);
            $ImageSize = filesize($filePath) / 1024;
            // Ukuruan maximum file 2MB
            $maxFotoSize = 2;
            if($ImageSize > $maxFotoSize * 1024){
                unlink($filePath);
                return $this->respond(["status" => false,"message" => "Foto harus kurang dari ".$maxFotoSize."MB"], 400); 
            }else{
                $dataJson->foto = "uploads/$fileName";
                $difabel = null;
                if($dataJson->nik == "0"){
                    $difabel = $this->model->getDifabel(['no_kk'=>$dataJson->ayah->no_kk,'nama' => $dataJson->nama]);
                }else{
                    $difabel = $this->model->getDifabel(['nik'=>$dataJson->nik,'nama' => $dataJson->nama]);
                }
                if($difabel){
                    unlink($filePath);
                    return $this->respond(["status" => false,"message" => 'Data ini sudah dikirim dan mungkin sedang diproses'], 400); 
                }else{
                    $dataJson->keterampilan = self::insertIsiSendiri('keterampilan',$dataJson->keterampilan);
                    $dataJson->organisasi = self::insertIsiSendiri('organisasi',$dataJson->organisasi);
                    $dataJson->pekerjaan = self::insertIsiSendiri('pekerjaan',$dataJson->pekerjaan);
                    $dataJson->kebutuhan_pelatihan = self::insertIsiSendiri('kebutuhan_pelatihan',$dataJson->kebutuhan_pelatihan);
                    $dataJson->kebutuhan_perawatan = self::insertIsiSendiri('kebutuhan_perawatan',$dataJson->kebutuhan_perawatan);
                    $parentModel = new \App\Models\ParentModel();
                    $parents = [$dataJson->ayah,$dataJson->ibu];
                    foreach ($parents as $parent) {
                        $p = $parentModel->getParent($parent->nik);
                        if(!$p){
                            $parentModel->save($parent);
                        }
                    }
                    $dif = $this->model->insertData($dataJson);
                    if($dif){
                        return $this->respond(["status" => true,"message"=>"Berhasil mengirim data, silahkan tunggu untuk admin memvalidasi data"], 200);  
                    }else{
                        unlink($filePath);
                        return $this->respond(["status" => false,"message" => 'Data tidak bisa diproses silahkan periksa kembali data anda'], 400); 
                    }
                }
            }
        }else{
            return $this->respond(["status" => false,"message" => "tipe foto yang diperbolehkan adalah ".implode(", ",$acceptType)], 400); 
        }
    }
    public function delete($id = NULL)
    {
        $user = $this->request->user;
        $difabel = $this->model->getDifabel(['id' => $id,'pendaftar_id' => $user['id']]);
        if($difabel){
            if((bool)$difabel['status']){
                return $this->respond(["status" => false,"message" => "Data tidak bisa dihapus, karena sudah terverifikasi"], 400); 
            }else{
                $delete = $this->model->delete($id);
                if($delete){
                    $foto = FCPATH.$difabel['foto'];
                    unlink($foto);
                    return $this->respond(["status" => true,"message" => "Data berhasil dihapus"], 200); 
                }else{
                    return $this->respond(["status" => false,"message" => "Data gagal dihapus"], 400); 
                }
            }
        }else{
            return $this->respond(["status" => false,"message" => "Data tidak ditemukkan"], 400); 
        }
    }
    public function static()
	{
        $db      = \Config\Database::connect();
        $jenis_kelamin_tabel = $db->table('jenis_kelamin');
        $jenis_kelamin = $jenis_kelamin_tabel->get()->getResultArray();
        
        $agama_tabel = $db->table('agama');
        $agama = $agama_tabel->get()->getResultArray();

        $alat_bantu_tabel = $db->table('alat_bantu');
        $alat_bantu = $alat_bantu_tabel->get()->getResultArray();
        
        $fasilitas_kesehatan_tabel = $db->table('fasilitas_kesehatan');
        $fasilitas_kesehatan = $fasilitas_kesehatan_tabel->get()->getResultArray();
        
        $jenis_disabilitas_tabel = $db->table('jenis_disabilitas');
        $jenis_disabilitas = $jenis_disabilitas_tabel->get()->getResultArray();
        
        $kebutuhan_pelatihan_tabel = $db->table('kebutuhan_pelatihan');
        $kebutuhan_pelatihan = $kebutuhan_pelatihan_tabel->get()->getResultArray();

        $kebutuhan_perawatan_tabel = $db->table('kebutuhan_perawatan');
        $kebutuhan_perawatan = $kebutuhan_perawatan_tabel->get()->getResultArray();
        
        $keterampilan_tabel = $db->table('keterampilan');
        $keterampilan = $keterampilan_tabel->get()->getResultArray();
        
        $kondisi_difabel_tabel = $db->table('kondisi_difabel');
        $kondisi_difabel = $kondisi_difabel_tabel->get()->getResultArray();
        
        $kondisi_orang_tua_tabel = $db->table('kondisi_orang_tua');
        $kondisi_orang_tua = $kondisi_orang_tua_tabel->get()->getResultArray();
        
        $organisasi_tabel = $db->table('organisasi');
        $organisasi = $organisasi_tabel->get()->getResultArray();
        
        $pekerjaan_tabel = $db->table('pekerjaan');
        $pekerjaan = $pekerjaan_tabel->get()->getResultArray();

		return $this->respond(["status" => true,"message" => "Data berhasil diambil","data" => compact('jenis_kelamin','agama','alat_bantu','fasilitas_kesehatan','jenis_disabilitas','kebutuhan_perawatan','keterampilan','kondisi_difabel','kondisi_orang_tua','organisasi','pekerjaan','kebutuhan_pelatihan')], 200); 
	}
    
}
