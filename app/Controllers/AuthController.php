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
            'username' => [
                'label'  => 'Username',
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
            'username' => htmlspecialchars($dataJson->username ?? ''),
            'password' => htmlspecialchars($dataJson->password ?? ''),
        ];
        $validation->setRules($loginRule);
        if(!$validation->run($data)){
            return $this->respond(["status" => 0,"message"=>"validasi error","data"=>$validation->getErrors()], 400);
        }
        $user = $this->model->authenticate($data['username'],$data['password']);
        if($user){
            if((bool) $user->aktif){
                $config = config('App');
                $jwt = JWT::encode($user,$config->appJWTKey);
                $user->token = $jwt;
                return $this->respond(["status" => 1,"message"=>"login berhasil","data"=>$user], 200);  
            }else{
                return $this->respond(["status" => 0,"message"=>"akun anda belum aktif, akan diproses 1 * 24jam","data" => []], 500); 
            }
        }else{
            return $this->respond(["status" => 0,"message"=>"username dan / atau password salah","data" => []], 400); 
        }
    }
    public function profile()
    {
        return $this->respond(['status' => 1,"message"=>"berhasil mengambil profile",'data' => $this->request->user], 200);  
    }
    public function update_profile()
    {
        $validation =  \Config\Services::validation();
        $id = $this->request->user->id;
        $updateProfileRule = [
            'username' => [
                'label'  => 'Username',
                'rules'  => 'required|cek_username['.$id.']',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'cek_username' => '{field} sudah digunakan',
                ]
            ],
            'nama' => [
                'label'  => 'nama',
                'rules'  => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],
            'email' => [
                'label'  => 'Email',
                'rules'  => 'required|cek_email['.$id.']',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'cek_email' => '{field} sudah digunakan',
                ]
            ],
            'alamat' => [
                'label'  => 'Alamat',
                'rules'  => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],
            'no_telp' => [
                'label'  => 'No Telephone',
                'rules'  => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],
        ];
        $dataJson = $this->request->getJson();
        $data = [
            'username' => htmlspecialchars(trim(strtolower($dataJson->username ?? ''))),
            'nama' => htmlspecialchars(trim(strtolower($dataJson->nama ?? ''))),
            'email' => htmlspecialchars(trim($dataJson->email ?? '')),
            'alamat' => htmlspecialchars(trim($dataJson->alamat ?? '')),
            'no_telp' => htmlspecialchars(trim($dataJson->no_telp ?? '')),
        ];
        $validation->setRules($updateProfileRule);
        if(!$validation->run($data)){
            return $this->respond(["status" => 0,"message"=>"validasi error","data"=>$validation->getErrors()], 400);
        }
        $update = $this->model->update($id,$data);
        if($update){
            return $this->respond(["status" => 1,"message"=> "Berhasil mengupdate profile","data" => []], 200);
        }else{
            return $this->respond(["status" => 0,"message"=>"Gagal mengupdate profile","data" => []], 400); 
        }
    }
    public function change_pass()
    {
        $validation =  \Config\Services::validation();
        $id = $this->request->user->id;
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
            return $this->respond(["status" => 0,"message"=>"validasi error","data"=>$validation->getErrors()], 400);
        }
        $res = $this->model->update($id,['password' => $data['new_password']]);
        if($res){
            return $this->respond(["status" => 1,"message"=> "Berhasil mengubah password","data" => []], 200);
        }else{
            return $this->respond(["status" => 0,"message"=>"Gagal mengubah password","data" => []], 400); 
        }
    }
    public function forgot_pass()
    {
        $validation =  \Config\Services::validation();
        $dataJson = $this->request->getJson();
        $data = [
            'username' => htmlspecialchars($dataJson->username ?? ''),
            'password' => htmlspecialchars($dataJson->password ?? ''),
        ];
        $forgotPassRule = [
            'username' => [
                'label'  => 'Username',
                'rules'  => 'required|cek_username',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'cek_username' => '{field} tidak ditemukkan',
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
            return $this->respond(["status" => 0,"message"=>"validasi error","data"=>$validation->getErrors()], 400);
        }
        $user = $this->model->getUser($data['username']);
        $UserForgotModel = new \App\Models\UserForgotModel();
        $cekTabel = $UserForgotModel->where(['username' => $user->username])->get()->getRow();
        if($cekTabel){
           $UserForgotModel->where(['username' => $user->username])->delete();
        }
        $insert = $UserForgotModel->save(['token' => uniqid(time()."_".rand(0,200)."_"),'username' => $user->username,'email' => $user->email,'password' => $data['password']]);
        if($insert){
            return $this->respond(["status" => 1,"message"=> "silahkan cek email ".$user->email." untuk verifikasi, batas waktunya 60 menit","data" => []], 200);
        }else{
            return $this->respond(["status" => 0,"message"=>"gagal mengirim email, coba sekali lagi","data" => []], 400); 
        }
    }
    public function verif_forgot_pass($token)
    {
        $token = urldecode($token);
        $UserForgotModel = new \App\Models\UserForgotModel();
        $cek = $UserForgotModel->where('token',$token)->get()->getRow();
        if($cek){
            $now = date_create(date('Y-m-d H:i:s'));
            $tokenTime = date_create($cek->created_at);
            $diff=date_diff($now,$tokenTime);
            if($diff->i < 60){
                $this->model->where('username',$cek->username)->set(['password' => $cek->password])->update();
                $UserForgotModel->where('token',$token)->delete();
                echo "verifikasi lupa kata sandi berhasil";
            }else{
                $UserForgotModel->where('token',$token)->delete();
                echo "Token Kadaluwarsa";
            }
        }else{
            echo "Token tidak ditemukan";
        }
    }
    
}
