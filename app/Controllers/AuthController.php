<?php namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use \Firebase\JWT\JWT;

class AuthController extends ResourceController
{   
    
	protected $format       = 'json';
    protected $modelName    = 'App\Models\UserModel';
 
    public function login()
    {
        $validation =  \Config\Services::validation();
        $loginRule = [
            'nik' => [
                'label'  => 'NIK',
                'rules'  => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'password' => [
                'label'  => 'Password',
                'rules'  => 'required|min_length[6]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'min_length' => '{field} harus lebih dari 6 karakter'
                ]
            ],
        ];
        $dataJson = $this->request->getJson();
        $data = [
            'nik' => htmlspecialchars($dataJson->nik ?? ''),
            'password' => htmlspecialchars($dataJson->password ?? ''),
        ];
        $validation->setRules($loginRule);
        if(!$validation->run($data)){
            return $this->respond($validation->getErrors(), 400);
        }
        $user = $this->model->authenticate($data['nik'],$data['password']);
        if($user){
            if((bool) $user['status']){
                $config = config('App');
                $jwt = JWT::encode($user,$config->appJWTKey);
                return $this->respond(["status" => true,"data"=>$user,"token"=> $jwt], 200);  
            }else{
                return $this->respond(["status" => false,"message"=>"Akun anda belum aktif, akan diproses 1 * 24jam"], 500); 
            }
        }else{
            return $this->respond(["status" => false,"message"=>"nik dan / atau password salah"], 400); 
        }
    }
    public function profile()
    {
        return $this->respond(['status' => true,'data' => $this->request->user], 200);  
    }

    public function register()
    {
        $validation =  \Config\Services::validation();
        $registerRule = [
            'nik' => [
                'label'  => 'NIK',
                'rules'  => 'required|is_unique[users.nik]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'is_unique' => '{field} sudah digunakan akun lain',
                ]
            ],
            'nama' => [
                'label'  => 'Nama',
                'rules'  => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],
            'tempat' => [
                'label'  => 'Tempat Lahir',
                'rules'  => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],
            'tanggal' => [
                'label'  => 'Tanggal Lahir',
                'rules'  => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],
            'jenis' => [
                'label'  => 'Jenis Kelamin',
                'rules'  => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],
            'no_telp' => [
                'label'  => 'No Telepon (WA)',
                'rules'  => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],
            'no_telp' => [
                'label'  => 'No Telp',
                'rules'  => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],
            'desa' => [
                'label'  => 'Desa',
                'rules'  => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],
            'rt' => [
                'label'  => 'RT',
                'rules'  => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],
            'rw' => [
                'label'  => 'RW',
                'rules'  => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],
            'kecamatan' => [
                'label'  => 'Kecamatan',
                'rules'  => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],
            'kabupaten' => [
                'label'  => 'Kabupaten',
                'rules'  => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],
            'provinsi' => [
                'label'  => 'Provinsi',
                'rules'  => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],
            'password' => [
                'label'  => 'Password',
                'rules'  => 'required|min_length[6]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'min_length' => '{field} harus lebih dari 6 karakter'
                ]
            ],
        ];
        $dataJson = $this->request->getJson();
        $data = [
            'nik' => htmlspecialchars($dataJson->nik ?? ''),
            'nama' => htmlspecialchars($dataJson->nama ?? ''),
            'tempat' => htmlspecialchars($dataJson->tempat ?? ''),
            'tanggal' => htmlspecialchars($dataJson->tanggal ?? ''),
            'jenis' => htmlspecialchars($dataJson->jenis ?? ''),
            'no_telp' => htmlspecialchars($dataJson->no_telp ?? ''),
            'desa' => htmlspecialchars($dataJson->desa ?? ''),
            'rt' => htmlspecialchars($dataJson->rt ?? ''),
            'rw' => htmlspecialchars($dataJson->rw ?? ''),
            'kecamatan' => htmlspecialchars($dataJson->kecamatan ?? ''),
            'kabupaten' => htmlspecialchars($dataJson->kabupaten ?? ''),
            'provinsi' => htmlspecialchars($dataJson->provinsi ?? ''),
            'password' => htmlspecialchars($dataJson->password ?? ''),
        ];
        $validation->setRules($registerRule);
        if(!$validation->run($data)){
            return $this->respond($validation->getErrors(), 400);
        }
        $save = $this->model->insertUser($data);
        if($save){
            return $this->respond(["status" => true], 200);  
        }else{
            return $this->respond(["status" => false], 400); 
        } 
       
    }
    public function change_pass()
    {
        $validation =  \Config\Services::validation();
        $id = $this->request->user['id'];
        $changePassRule = [
            'old_password' => [
                'label'  => 'Password Lama',
                'rules'  => 'required|cek_pass['.$id.']',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'cek_pass' => '{field} salah',
                ]
            ],
            'new_password' => [
                'label'  => 'Password Baru',
                'rules'  => 'required|min_length[6]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'min_length' => '{field} harus lebih dari 6 karakter'
                ]
            ],
        ];
        $dataJson = $this->request->getJson();
        $data = [
            'old_password' => htmlspecialchars($dataJson->old_password ?? ''),
            'new_password' => htmlspecialchars($dataJson->new_password ?? ''),
        ];
        $validation->setRules($changePassRule);
        if(!$validation->run($data)){
            return $this->respond($validation->getErrors(), 400);
        }
        $res = $this->model->change_pass($id,$data['new_password']);
        if($res){
            return $this->respond(["status" => true,"message"=> "Berhasil mengubah password"], 200);
        }else{
            return $this->respond(["status" => false,"message"=>"Gagal mengubah password"], 400); 
        }
    }
    public function forgot_pass()
    {
        $validation =  \Config\Services::validation();
        $dataJson = $this->request->getJson();
        $data = [
            'nik' => htmlspecialchars($dataJson->nik ?? ''),
            'password' => htmlspecialchars($dataJson->password ?? ''),
        ];
        $forgotPassRule = [
            'nik' => [
                'label'  => 'NIK',
                'rules'  => 'required|cek_nik',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'cek_nik' => '{field} tidak ditemukkan',
                ]
            ],
            'password' => [
                'label'  => 'Password',
                'rules'  => 'required|min_length[6]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'min_length' => '{field} harus lebih dari 6 karakter'
                ]
            ],
        ];
        
        $validation->setRules($forgotPassRule);
        if(!$validation->run($data)){
            return $this->respond($validation->getErrors(), 400);
        }
        $user = $this->model->getUser($data['nik']);
        $res = $this->model->change_pass($user['id'],$data['password']);
        if($res){
            return $this->respond(["status" => true,"message"=> "Berhasil mengubah password"], 200);
        }else{
            return $this->respond(["status" => false,"message"=>"Gagal mengubah password"], 400); 
        }
    }

    public function cek_status()
    {
        $validation =  \Config\Services::validation();
        $dataJson = $this->request->getJson();
        $data = [
            'nik' => htmlspecialchars($dataJson->nik ?? ''),
        ];
        $cekStatusRule = [
            'nik' => [
                'label'  => 'NIK',
                'rules'  => 'required|cek_nik',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'cek_nik' => '{field} tidak ditemukkan',
                ]
            ],
        ];
        $validation->setRules($cekStatusRule);
        if(!$validation->run($data)){
            return $this->respond($validation->getErrors(), 400);
        }
        $user = $this->model->getUser($data['nik']);
        if((bool) $user['status']){
            return $this->respond(["status" => true,"message"=> "Sudah diaktivasi"], 200);
        }else{
            $this->model->update($user['id'],['status' => 1]);
            return $this->respond(["status" => false,"message"=>"Belum diaktivasi, silahkan tunggu akan kami aktivasi dalam 1 detik"], 400); 
        }
    }
    
}
