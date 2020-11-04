<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use \Firebase\JWT\JWT;
use App\Models\KaryawanModel;
use App\Models\TokoModel;

class PemilikTokoAndKaryawanFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $response = service('response');
        helper('role');
        $cekAuth = isKaryawanOrPemilikToko($request->user->role_id);
        if(!$cekAuth){
          return $response->setStatusCode(401)->setBody(json_encode(["status" => 0,"message"=>"unauthorized","data" => []]))->setHeader('Content-type', 'application/json');
        }else{
          if($cekAuth == 'karyawan'){
            $karyawanModel = new KaryawanModel();
            $karyawan = $karyawanModel->where('user_id',$request->user->id)->first();
            $tokoModel = new TokoModel();
            $request->toko = $tokoModel->where('toko_id',$karyawan->toko_id)->first();
          }
          if($cekAuth == 'pemilik toko'){
            $tokoModel = new TokoModel();
            $request->toko = $tokoModel->where('user_id',$request->user->id)->first();
          }
        }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
