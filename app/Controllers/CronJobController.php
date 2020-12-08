<?php

namespace App\Controllers;

use App\Models\TransaksiPenjualanDistributorModel;
use App\Models\TokoModel;
use GuzzleHttp\Client;

class CronJobController extends BaseController
{
  public function sendMail()
  {
    $userForgotModel = new \App\Models\UserForgotModel();
    $userforgots = $userForgotModel->where(['status' => 0])->get()->getResultArray();
    helper('send_email');
    foreach ($userforgots as $forgot) {
      $send = sendEmail("Lupa Kata Sandi", [$forgot['email']], view('email/forgot_pass_mail', $forgot));
      if ($send) {
        $userForgotModel->update($forgot['id'], ['status' => 1]);
      }
    }
    // helper('url');
    // return redirect('/', 'refresh');
  }
  public function sendNotifUtang()
  {
    // %242y%2410%24QIps4sfEgXAwadAsHFPh5OWEiwRMSVQ4IqWmiBi93tClIxkeCftdS
    $transaksiModel = new TransaksiPenjualanDistributorModel();
    $tokoModel = new TokoModel();
    $transaksis = $transaksiModel->where(['status' => 0])->get()->getResultArray();
    $now = date_create(date('Y-m-d H:i:s'));
    $client = new Client();
    foreach ($transaksis as $transaksi) {
      $createdAt = date_create(date('Y-m-d H:i:s', strtotime('+1 month', strtotime($transaksi['created_at']))));
      $diff = date_diff($createdAt, $now);
      $notif_diff = (int) env('diff_notif_days');
      if ($diff->days <= $notif_diff) {
        $toko = $tokoModel->select('token')->where(['id' => $transaksi['toko_id']])->get()->getRowArray();
        if ($toko['token'] != null) {
          $response = $client->post('https://onesignal.com/api/v1/notifications', [
            \GuzzleHttp\RequestOptions::JSON => [
              "app_id" => "246fbd22-450f-4ffb-b5b6-5bc03fbf6046", "include_player_ids" => [$toko['token']],
              "data" => ["foo" => "bar"],
              "headings" => ["en" => "Pelunasan Transaksi"],
              "contents" => ["en" => "Anda memiliki transaksi yang harus dilunasi"],
              "subtitle" => ["en" => "Anda memiliki transaksi yang harus dilunasi"],
              "large_icon" => base_url('') . "/assets/images/icon.png",
              "small_icon" => "ic_stat_onesignal_default",
              "android_channel_id" => "f40acc7d-4380-41d3-be6e-3779e8ce04d5"
            ]
          ]);
        }
      }
    }
  }

  //--------------------------------------------------------------------

}
