<?php namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;

class TokoController extends ResourceController
{   
    
	protected $format       = 'json';
  protected $modelName    = 'App\Models\TokoModel';

  public function index()
  {
    return $this->respond(["status" => 1,"message"=>"berhasil mengambil data toko","data" => $this->request->toko], 200);
  }

  public function updatetoko()
  {
    $toko = $this->request->toko;
    $validation =  \Config\Services::validation();
    $createTokoRule = [
      'nama_toko' => [
          'label'  => 'Nama Toko',
          'rules'  => 'required',
          'errors' => [
              'required' => '{field} tidak boleh kosong',
          ]
      ],
    ];
    $dataJson = $this->request->getJson();
    $data = [
        'nama_toko' => htmlspecialchars($dataJson->nama_toko ?? ''),
        'email' => htmlspecialchars($dataJson->email ?? ''),
        'alamat' => htmlspecialchars($dataJson->alamat ?? ''),
        'no_telp' => htmlspecialchars($dataJson->no_telp ?? ''),
    ];
    $validation->setRules($createTokoRule);
    if(!$validation->run($data)){
        return $this->respond(["status" => 0,"message"=>"validasi error","data"=>$validation->getErrors()], 400);
    }
    $update = $this->model->update($toko['id'],$data);
    if($update){
      return $this->respond(["status" => 1,"message"=>"toko berhasil diubah","data" => []], 200); 
    }else{
      return $this->respond(["status" => 0,"message"=>"toko gagal diubah","data" => []], 400); 
    }
  }
    
}
