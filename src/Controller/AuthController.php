<?php
// namespace App\Controller;
 
use Cake\Controller\Controller;
use Cake\Event\Event;
 
// ソーシャルログイン用コントローラー
class AuthController extends AppController
{
 
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        // レイアウトなし
        $this->autoRender = FALSE;
    }

    // public function index(){
    // 	$url = "https://api.line.me/oauth2/v2.1/token";


    //     $postData = array(
    //       'grant_type'    => 'authorization_code',
    //       'code'          => $_GET['code'],
    //       'redirect_uri'  => 'https://fave-jp.info/auth/',
    //       'client_id'     => '1604848142',
    //       'client_secret' => 'f7751abbe570e66ed437e4f4655b6117'
    //     );


    //     $ch = curl_init();

    //     curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    //     curl_setopt($ch, CURLOPT_URL, 'https://api.line.me/oauth2/v2.1/token');
    //     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //     $response = curl_exec($ch);
    //     curl_close($ch);

    //     $json = json_decode($response);
    //     $accessToken = $json->access_token;

    //     $ch = curl_init();

    //     curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $accessToken));
    //     curl_setopt($ch, CURLOPT_URL, 'https://api.line.me/v2/profile');
    //     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //     $response = curl_exec($ch);
    //     curl_close($ch);

    //     $json = json_decode($response);

    //     var_dump($json);

    // }
 
    // /auth/yahoojp
    public function yahoojp()
    {
        $this->authFunction();
    }
 
    // /auth/facebook
    public function facebook()
    {
        $this->authFunction();
    }
 
    // /auth/google
    public function google()
    {
        $this->authFunction();
    }
 
    // /auth/twitter
    public function twitter()
    {
        $this->authFunction();
    }

    // /auth/line
    public function line()
    {
        $this->authFunction();
    }
 
    // 共通function
    private function authFunction()
    {
        // Opauth require_once
        $opauth_path = '/home/torch/www/fave/plugins/vendor/opauth/opauth/';
        require_once $opauth_path.'config.php';
        require_once $opauth_path.'lib/Opauth/Opauth.php';
 
        // ソーシャルログイン処理
        new \Opauth($config);
    }

    // ソーシャルログイン完了後のaction
    public function complete()
    {
        // session.auto_startオンやAuthなどでセッションスタート済みの場合不要
        if (!isset($_SESSION['opauth'])) {
            session_start();
        }
 
        // 取得データ表示
        if (isset($_SESSION['opauth']['auth'])) {
            // 成功
 
            // CakePHP ~3.4
            // $session = $this->request->session();
            // CakePHP 3.5~
            $session = $this->request->getSession();
 
            $session->write('opauth', $_SESSION['opauth']['auth']);
            var_dump($session->read('opauth'));
        } elseif (isset($_SESSION['opauth']['error'])) {
            // 失敗
            var_dump($_SESSION['opauth']['error']);
        } else {
            // その他失敗
            echo 'Opauth ERROR!';
        }
    }
}