<?php
namespace App\Validations;

class MyRule{
    public function cek_pass(string $password,$id)
    {
        $userModel = new \App\Models\UserModel();
        $user = $userModel->where('id',$id)->first();
        if(password_verify($password,$user['password'])){
            return true;
        }
        return false;
    }
    public function cek_nik(string $nik)
    {
        $userModel = new \App\Models\UserModel();
        $user = $userModel->getUser($nik);
        if($user){
            return true;
        }
        return false;
    }
}