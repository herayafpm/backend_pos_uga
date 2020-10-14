<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use \Firebase\JWT\JWT;

class AuthApiFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $response = service('response');
        if(!$request->hasHeader('Authorization')){
            $response->setStatusCode(401);
            $response->setBody(json_encode(["status" => false,"message"=> "Unauthorized"]));
            $response->setHeader('Content-type', 'text/html');
            return $response;
        }
        try {
            $config = config('App');
            $jwt = explode("Bearer ",$request->getHeader('Authorization')->getValue())[1];
            $decoded = JWT::decode($jwt, $config->appJWTKey, array('HS256'));
            $userModel = new \App\Models\UserModel();
            $user = $userModel->getUser($decoded->nik);
            if((bool) $user['status'] == false){
                throw new \Exception("Akun anda belum aktif, akan diproses 1 * 24jam");
            }
            $request->user = $user;
        } catch (\Exception $th) {
            $response->setStatusCode(401);
            $response->setBody(json_encode(["status" => false,"message"=> $th->getMessage()]));
            $response->setHeader('Content-type', 'text/html');
            return $response;
        }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
