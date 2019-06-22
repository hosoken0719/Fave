<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class AjaxController extends AppController
{
    public function initialize()
    {
      parent::initialize();
      $this->loadComponent('FollowComp'); // コンポーネントの読み込み
	}


	public function shoprating(){

		// if ($this->request->is('ajax')) {

	        $FollowsTable = TableRegistry::get('follows');

 		    //新規にフォローする場合
			// if($this->FollowComp->isShopFollow($id) > 0){
		    	$follow = $FollowsTable->newEntity();
		        $follow->follow =  $this->Auth->user('id');
		        $follow->follower_shop = $this->request->getQuery('shop');
		        $follow->rating = $this->request->getQuery('rating');
		        $follow->review = $this->request->getQuery('review');
		        $time = Time::now();
		        $follow->created = $time->format('Y/m/d H:i:s');
		        if($FollowsTable->save($follow)){
		    		$array = ['result'=>'follow'];
		        }else{
		        	$array = array('result'=>'fail');
		        }
			// }else{
	  //   		$FollowsTable->deleteAll(['follow'=>$id['follow'],'follower_shop'=>$id['follower_shop']]);
	  //   		$array = ['result'=>'followed'];
			// }
	        $result = json_encode($array);
			$this->viewClass = 'Json';
			$this->set(compact('result'));
	        $this->set('_serialize', 'result');
		// }
	}


	public function followuser(){

		// if ($this->request->is('ajax')) {

	        $FollowUsersTable = TableRegistry::get('follow_users');

	    //新規にフォローする場合
	        $id['follower_user'] = $this->request->getQuery('user');
	        $id['follow'] = $this->Auth->user('id');
	        if($id['follow'] <> $id['follower_user']){
			    if($this->FollowComp->isUserFollow($id) == 0){
			    	$follow = $FollowUsersTable->newEntity();
			        $follow->follow = $id['follow'];
			        $follow->follower_user = $id['follower_user'];
			        $time = Time::now();
			        $follow->created = $time->format('Y/m/d H:i:s');
			        if($FollowUsersTable->save($follow)){
			    		$array = ['result'=>'follow','button'=>$id['follower_user']];
			        }
				//フォローをやめる場合
		    	}else{
		    		$FollowUsersTable->deleteAll(['follow'=>$id['follow'],'follower_user'=>$id['follower_user']]);
			    	$array = ['result'=>'followed','button'=>$id['follower_user']];
		    	}

			}
			else{
				$array =['result'=>'fail'];
			}

	        $result = json_encode($array);
			$this->viewClass = 'Json';
			$this->set(compact('result'));
	        $this->set('_serialize', 'result');
		// }
	}

	//autocompleteのキーワード・ハッシュタグ
	public function acword(){

		$term = $this->request->getQuery('term');
	    $TagTable = TableRegistry::get('tags');
		$query_tag = $TagTable->find('list')
		->where(['tag LIKE'=> '%'.$term.'%']);


	    $ShopTable = TableRegistry::get('shops');
		$query_shop = $ShopTable->find('list')
		->where(['shopname LIKE'=> '%'.$term.'%']);
		// ->select(['shopname' => 'shopname']);

		$tag = $query_tag->toArray();
		$shop = $query_shop->toArray();

		$result = array_merge($tag,$shop);
		$this->viewClass = 'Json';
		$this->set(compact('result'));
		$this->set('_serialize','result');
    }

	//autocompleteのユーザ
    public function acuser(){

		$term = $this->request->getQuery('term');
	    $UserTable = TableRegistry::get('users');

		// $query = $UserTable->find('list',
		// 	['keyField' => 'id',
		// 	'valueField' => 'username',
		// 	]
		// )
		// ->where(['username LIKE'=> '%'.$term.'%']);

		// foreach($results as $result){
		// 	$user = [];
		// 	$user['label'] = $result;
		// 	$user['value'] = $result;
		// 	$user['mylink'] = '/users/'.$result;
		// 	array_push($resultss,$user);
		// } 

		// $results = $query->toArray();
		// $resultss = array();

		$userDatas = $UserTable->find()
		->where(['username LIKE'=> '%'.$term.'%'])
		->select([
			'username' => 'username',
			// 'icon' => 'icon'
		]);

		$result = [];

		foreach($userDatas as $userData){
			$user = [];
			$user['username'] = $userData->username;
			$user['icon'] = $userData->icon;
			$user['userlink'] = '/users/'.$userData->username;
			array_push($result,$user);
		} 

		$this->viewClass = 'Json';
		$this->set(compact('result'));
		$this->set('_serialize','result');
    }


    public function shopimage(){
		 if ($this->request->is('ajax')) {

				$this->autoRender = false;
				$data = $this->request->getData("image");
				$image_array_1 = explode(";",$data);
				$image_array_2 = explode(",", $image_array_1[1]);

				$data = base64_decode($image_array_2[1]);

				$imagename = time().'.jpeg';
				$shop_id = $this->request->getData("shop_id");

				$path = SHOPPHOTO_UPLOADDIR.'/photo_shop/'.$shop_id.'/';
				//ショップのディレクトリ有無を確認して、無ければ新規作成
				if(!file_exists($path)){
					mkdir($path,0755);
					mkdir($path.'thumbnail/',0755);
				}
				file_put_contents($path.$imagename,$data);

				$this->resize($shop_id,$path,$imagename,360,480);
		}
    }



private function resize($shop_id,$path,$imagename,$h,$w){


	// 加工したいファイルを指定
	$file = $path.$imagename;

	// 加工前の画像の情報を取得
	list($original_w, $original_h, $type) = getimagesize($file);

	// 加工前のファイルをフォーマット別に読み出す（この他にも対応可能なフォーマット有り）
	switch ($type) {
	    case IMAGETYPE_JPEG:
	        $original_image = imagecreatefromjpeg($file);
	        break;
	    case IMAGETYPE_PNG:
	        $original_image = imagecreatefrompng($file);
	        break;
	    case IMAGETYPE_GIF:
	        $original_image = imagecreatefromgif($file);
	        break;
	    default:
	        throw new RuntimeException('対応していないファイル形式です。: ', $type);
	}

	// 新しく描画するキャンバスを作成
	$canvas = imagecreatetruecolor($w, $h);
	imagecopyresampled($canvas, $original_image, 0,0,0,0, $w, $h, $original_w, $original_h);

	$resize_path = $path.'thumbnail/min_'.$imagename; // 保存先を指定

	switch ($type) {
	    case IMAGETYPE_JPEG:
	        imagejpeg($canvas, $resize_path);
	        break;
	    case IMAGETYPE_PNG:
	        imagepng($canvas, $resize_path, 9);
	        break;
	    case IMAGETYPE_GIF:
	        imagegif($canvas, $resize_path);
	        break;
	}

	// 読み出したファイルは消去
	imagedestroy($original_image);
	imagedestroy($canvas);


}




}