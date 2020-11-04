<?php namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;

class KaryawanController extends ResourceController
{   
    
	protected $format       = 'json';
  protected $modelName    = 'App\Models\KaryawanModel';

  public function index()
  {
    $toko = $this->request->toko;
    $karyawans = $this->model->getKaryawans($toko['id']);
    return $this->respond(["status" => 1,"message"=>"berhasil mengambil data karyawan","data" => $karyawans], 200);
  }
  public function create()
  {
    $toko = $this->request->toko;
    $validation =  \Config\Services::validation();
    $createKaryawanRule = [
       'username' => [
          'label'  => 'Username Karyawan',
          'rules'  => 'required|is_unique[users.username]',
          'errors' => [
              'required' => '{field} tidak boleh kosong',
              'is_unique' => '{field} sudah digunakan'
          ]
      ],
      'email' => [
          'label'  => 'Email Karyawan',
          'rules'  => 'required|is_unique[users.email]',
          'errors' => [
              'required' => '{field} tidak boleh kosong',
              'is_unique' => '{field} sudah digunakan'
          ]
      ],
      'no_telp' => [
          'label'  => 'No Telepon Karyawan',
          'rules'  => 'required',
          'errors' => [
              'required' => '{field} tidak boleh kosong',
          ]
      ],
      'nama' => [
          'label'  => 'Nama Karyawan',
          'rules'  => 'required',
          'errors' => [
              'required' => '{field} tidak boleh kosong',
          ]
      ],
    ];
    $dataJson = $this->request->getJson();
    $data = [
        'username' => htmlspecialchars($dataJson->username ?? ''),
        'email' => htmlspecialchars($dataJson->email ?? ''),
        'nama' => htmlspecialchars($dataJson->nama ?? ''),
        'no_telp' => htmlspecialchars($dataJson->no_telp ?? ''),
        'alamat' => htmlspecialchars($dataJson->alamat ?? ''),
    ];
    $validation->setRules($createKaryawanRule);
    if(!$validation->run($data)){
        return $this->respond(["status" => 0,"message"=>"validasi error","data"=>$validation->getErrors()], 400);
    }
    $userModel = new UserModel();
    $data['password'] = "123456";
    $data['role_id'] = 3;
    $data['aktif'] = 1;
    $user = $userModel->insertUser($data);
    if($user){
      $user_id = $userModel->getLastId();
      $create = $this->model->save(['user_id' => $user_id,'toko_id' => $toko['id']]);
      if($create){
        return $this->respond(["status" => 1,"message"=>"karyawan berhasil ditambahkan","data" => []], 200); 
      }else{
        return $this->respond(["status" => 0,"message"=>"karyawan gagal ditambahkan","data" => []], 400); 
      }
    }else{
      return $this->respond(["status" => 0,"message"=>"karyawan gagal ditambahkan","data" => []], 400); 
    }
    $create = $this->model->save($data);
    if($create){
      return $this->respond(["status" => 1,"message"=>"karyawan berhasil ditambahkan","data" => []], 200); 
    }else{
      return $this->respond(["status" => 0,"message"=>"karyawan gagal ditambahkan","data" => []], 400); 
    }
  }

  public function delete($id = NULL)
  {
    $karyawan = $this->model->where('id',$id)->get()->getRow();
    if(!$karyawan){
      return $this->respond(["status" => 0,"message"=>"karyawan tidak ditemukkan","data" =>[]], 400);
    }
    $delete = $this->model->delete($id);
    if($delete){
      return $this->respond(["status" => 1,"message"=>"karyawan berhasil dihapus","data" => []], 200); 
    }else{
      return $this->respond(["status" => 0,"message"=>"karyawan gagal dihapus","data" => []], 400); 
    }
  }
    
}
