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
      $this->Auth->allow();
	}


	public function deleteshoprating(){

		if ($this->request->is('ajax')) {

	        $FollowsTable = TableRegistry::get('follows');

			if($FollowsTable->deleteAll(['follow'=>$this->Auth->user('id'),'follower_shop'=>$this->request->getQuery('shop')])){
				$array = ['result'=>'success'];
			}else{
				$array = ['result'=>'fail'];
			}
	        $result = json_encode($array);
			$this->viewClass = 'Json';
			$this->set(compact('result'));
	        $this->set('_serialize', 'result');
		}
	}

	public function followuser(){

		if(!$this->request->is('ajax')) {
			throw new BadRequestException();
		}

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
	}

	//autocompleteのキーワード・ハッシュタグ
	public function acword(){

		if(!$this->request->is('ajax')) {
			throw new BadRequestException();
		}
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

		if(!$this->request->is('ajax')) {
			throw new BadRequestException();
		}
		$term = $this->request->getQuery('term');
		$UserTable = TableRegistry::get('users');
		$this->loadComponent('UserComp'); // コンポーネントの読み込み

		$userDatas = $UserTable->find()
		->where(['OR'=>[['username LIKE'=> '%'.$term.'%'],['nickname LIKE'=> '%'.$term.'%']]])
		->select([
			'username' => 'username',
			'nickname' => 'nickname',
			'user_id' => 'id'
		]);

		$result = [];

		foreach($userDatas as $userData){
			$user = [];
			$user['username'] = $userData->username;
			if(!is_null($userData->nickname)){
				$user['nickname'] = $userData->nickname;
			}else{
				$user['nickname'] = "";
			}
			$avatar = $this->UserComp->getAvatar($userData->user_id);
			$user['icon'] = $avatar;
			$user['userlink'] = '/users/'.$userData->username;
			array_push($result,$user);
		}

		$this->viewClass = 'Json';
		$this->set(compact('result'));
		$this->set('_serialize','result');
}

//ショップ画像をフォルダに保存する
    public function shopimage(){

		if(!$this->request->is('ajax')) {
			throw new BadRequestException();
		}

		$this->autoRender = false;
			//画像ファイルを取得
			$upload_file_path = $this->request->getData('file.tmp_name');


			//フォーマットチェック_
			$import_data = $this->checkFormat($upload_file_path);

				//jpegだった場合、処理を開始
				if($import_data === 2){

					//shopIDを取得
					$shop_id = $this->request->getData('id');

					//ファイル名の設定
					$file_name = $shop_id.time().".jpg";

					//正式な保存フォルダのパスを設定
					$shop_folder = PHOTO_UPLOADDIR.'shop_photos/'.$shop_id.'/';

					//ショップのディレクトリ有無を確認して、無ければ新規作成。併せてサムネイルもDBに登録
					if(!file_exists($shop_folder)){
						mkdir($shop_folder,0777);
						mkdir($shop_folder.'thumbnail/',0777);

						//サムネイル情報をshopテーブルに書き込み
						// $ShopTable = $this->loadModel('Shops');
						// $shopData = $ShopTable->get($shop_id);
						// $ShopsTable = TableRegistry::get('shops');
						// $addData['thumbnail'] = $file_name;
						// $shopEntity = $ShopsTable->patchEntity($shopData, $addData);
						// $ShopsTable->save($shopEntity);

					}

					$file_path = $shop_folder.$file_name;

					//オリジナルファイルの移動
					copy($upload_file_path , $file_path);

					$baseImage = imagecreatefromjpeg($file_path);

					// サイズを変更してサムネイルを保存（高さはインスタの画像保存サイズを参考にしているため変更しないこと。横は可変でも問題なし）
					$this->resize($baseImage , $shop_folder , $file_name,1440,1080,'large_');
					$this->resize($baseImage ,$shop_folder,$file_name,427,320,'medium_');
					$this->resize($baseImage ,$shop_folder,$file_name,200,150,'thumbnail_');

					//メモリの開放
					imagedestroy($baseImage);

					//ファイル情報をDBに書き込み
	 				$PhotosTable = TableRegistry::get('shop_photos');
			    	$photo = $PhotosTable->newEntity();
			        $photo->user_id =  $this->Auth->user('id');
			        $photo->shop_id = $shop_id;
			        $photo->file_name = $file_name;
			        $photo->created = date("Y-m-d H:i:s", strtotime('+9hour'));
			        $PhotosTable->save($photo);

			    }else{
			    	echo "error";
			    }
		// }
	return;
    }

    //アバターの登録
    public function avatar(){
		if ($this->request->is('ajax')) {
			$data = $this->decode($this->request->getData("image"));
			$imagename = $this->request->getData("id").'.jpg';
			$path = PHOTO_UPLOADDIR.'/user_photos/';
			file_put_contents($path.$imagename,$data);
			$this->resize($path,$imagename,800,800,'full_');
			$this->resize($path,$imagename,300,300,'max_');
			$this->resize($path,$imagename,150,150,'middle_');
			$this->resize($path,$imagename,75,75,'min_');
		}
    }

    private function decode($image){

		$data = $image;
		$image_array_1 = explode(";",$data);
		$image_array_2 = explode(",", $image_array_1[1]);

		$data = base64_decode($image_array_2[1]);

		return $data;
    }

    private function checkFormat($file){

		// 加工前の画像の情報を取得
		list($original_w, $original_h, $type) = getimagesize($file);

		return $type;

    }

	//GDを使用しているはず
	private function resize($image_data,$path,$image_name,$w,$h,$prefix){

		// 新しく描画するキャンバスを作成
		$canvas = imagecreatetruecolor($w, $h);

		//元のファイルサイズを取得
		$size = getimagesize($path.$image_name);
		imagecopyresampled($canvas, $image_data, 0,0,0,0, $w, $h, $size[0], $size[1]);

		// 保存先を指定
		$resize_path = $path.'thumbnail/'.$prefix.$image_name;

		//jpegに変換して保存（圧縮率60%）
		imagejpeg($canvas, $resize_path , 60);

		//メモリの開放
		imagedestroy($canvas);

	}




}