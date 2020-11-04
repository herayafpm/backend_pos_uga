<?php namespace App\Controllers;

class CronJobController extends BaseController
{
	public function sendMail()
	{
    $userForgotModel = new \App\Models\UserForgotModel();
    $userforgots = $userForgotModel->where(['status' => 0])->get()->getResultArray();
    helper('send_email');
    foreach ($userforgots as $forgot) {
      $send = sendEmail("Lupa Kata Sandi",[$forgot['email']],view('email/forgot_pass_mail',$forgot));
      if($send){
        $userForgotModel->update($forgot['id'],['status'=>1]);
      }
    }
    helper('url');
    return redirect('/', 'refresh');
	}

	//--------------------------------------------------------------------

}
