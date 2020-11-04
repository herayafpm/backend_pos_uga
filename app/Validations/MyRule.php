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
    public function cek_username(string $username,$id = NULL)
    {
        $userModel = new \App\Models\UserModel();
        $user = $userModel->getUser($username);
        if($id == NULL){
            if($user){
                return true;
            }
            return false;
        }else{
            $useraktif = $userModel->getUserById($id);
            if(!$user || $username == $useraktif->username){
                return true;
            }
            return false;
        }
    }
    public function cek_email(string $email,$id = NULL)
    {
        $userModel = new \App\Models\UserModel();
        $user = $userModel->getUserByEmail($email);
        if($id == NULL){
            if($user){
                return true;
            }
            return false;
        }else{
            $useraktif = $userModel->getUserById($id);
            if(!$user || $email == $useraktif->email){
                return true;
            }
            return false;
        }
    }
    public function cek_pemilik_toko(string $userid)
    {
        $tokoModel = new \App\Models\TokoModel();
        $toko = $tokoModel->where('user_id',$userid)->get()->getRow();
        if(!$toko){
            return true;
        }
        return false;
    }
}