<?php namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;

class TokoDistributorController extends ResourceController
{   
    
	protected $format       = 'json';
  protected $modelName    = 'App\Models\TokoModel';

  public function index()
  {
    $tokos = $this->model->tokos();
    return $this->respond(["status" => 1,"message"=>"berhasil mengambil data toko","data" => $tokos], 200); 
  }

  public function create()
  {
    $validation =  \Config\Services::validation();
    $dataJson = $this->request->getJson();
    $createTokoRule = [
      'username' => [
          'label'  => 'Username Pemilik Toko',
          'rules'  => 'required|is_unique[users.username]',
          'errors' => [
              'required' => '{field} tidak boleh kosong',
              'is_unique' => '{field} sudah digunakan'
          ]
      ],
      'email' => [
          'label'  => 'Email Pemilik Toko',
          'rules'  => 'required|is_unique[users.email]',
          'errors' => [
              'required' => '{field} tidak boleh kosong',
              'is_unique' => '{field} sudah digunakan'
          ]
      ],
      'no_telp' => [
          'label'  => 'No Telepon Pemilik Toko',
          'rules'  => 'required',
          'errors' => [
              'required' => '{field} tidak boleh kosong',
          ]
      ],
      'nama' => [
          'label'  => 'Nama Pemilik Toko',
          'rules'  => 'required',
          'errors' => [
              'required' => '{field} tidak boleh kosong',
          ]
      ],
      'nama_toko' => [
          'label'  => 'Nama Toko',
          'rules'  => 'required',
          'errors' => [
              'required' => '{field} tidak boleh kosong',
          ]
      ],
    ];
    $data = [
        'username' => htmlspecialchars($dataJson->username ?? ''),
        'email' => htmlspecialchars($dataJson->email ?? ''),
        'nama' => htmlspecialchars($dataJson->nama ?? ''),
        'no_telp' => htmlspecialchars($dataJson->no_telp ?? ''),
        'alamat' => htmlspecialchars($dataJson->alamat ?? ''),
        'nama_toko' => htmlspecialchars($dataJson->nama_toko ?? ''),
    ];
    $validation->setRules($createTokoRule);
    if(!$validation->run($data)){
      return $this->respond(["status" => 0,"message"=>"validasi error","data"=>$validation->getErrors()], 400);
    }
    $userModel = new UserModel();
    $data['password'] = "123456";
    $data['role_id'] = 2;
    $data['aktif'] = 1;
    $dataToko = [
      'nama_toko' => $data['nama_toko'],
      'email' => htmlspecialchars($dataJson->email_toko ?? ''),
      'no_telp' => htmlspecialchars($dataJson->no_telp_toko ?? ''),
      'alamat' => htmlspecialchars($dataJson->alamat_toko ?? ''),
    ];
    unset($data['nama_toko']);
    $user = $userModel->insertUser($data);
    if($user){
      $user_id = $userModel->getLastId();
      $dataToko['user_id'] = $user_id;
      $create = $this->model->save($dataToko);
      if($create){
        return $this->respond(["status" => 1,"message"=>"toko berhasil ditambahkan","data" => []], 200); 
      }else{
        $userModel->delete($user_id);
        return $this->respond(["status" => 0,"message"=>"toko gagal ditambahkan","data" => []], 400); 
      }
    }else{
      return $this->respond(["status" => 0,"message"=>"toko gagal ditambahkan","data" => []], 400); 
    }
  }

  
  public function delete($id = NULL)
  {
    $toko = $this->model->where('id',$id)->get()->getRow();
    if($toko){
      $delete = $this->model->where('id',$id)->delete();
      if($delete){
        return $this->respond(["status" => 1,"message"=>"toko berhasil dihapus","data" => []], 200); 
      }else{
        return $this->respond(["status" => 0,"message"=>"toko tidak ditemukan","data" => []], 400); 
      }
    }else{
      return $this->respond(["status" => 0,"message"=>"toko tidak ditemukan","data" => []], 400); 
    }
  }
    
}
