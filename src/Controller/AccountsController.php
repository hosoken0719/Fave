<?php

namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;



class AccountsController extends AppController{

	public function index(){

		if (!$this->Auth->user()) {
			$this->redirect(['plugin' => 'CakeDC/Users', 'controller' => 'Users', 'action' => 'login']);
		}else{
			//ユーザ情報取得
			$UserTable = $this->loadModel('Users');
	        $userData = $UserTable->get($this->Auth->user('id'));

	        // $userData = $UserTable->find()
	        // ->where(['id' => $this->Auth->user('id')])
	        // ->select([
	        // 	'id'=>'id',
	        // 	'username'=>'username',
	        // 	'address'=>'address',
	        // 	'introduction'=>'introduction',
	        // 	'email'=>'email'
	        // ])
	        // ->first();
			//セレクトボックスのリストを取得
			$SexTable =  $this->loadModel('sexs');
			$sexList = $SexTable->find('list',['keyField'=>'id','valueField'=>'type'])->toArray();

			$this->set(compact('userData','sexList'));
	        if (!$this->request->is(['patch', 'post', 'put'])) {
	            return;
	        }
			//更新ボタンが押された場合
	        $userEntity = $UserTable->patchEntity($userData, $this->request->getData());
	        if ($UserTable->save($userEntity)) {
	            $this->Flash->success(__d('CakeDC/Users', 'The {0} has been saved'));
	        }else{
		        // $this->Flash->error(__d('CakeDC/Users', 'The {0} could not be saved', $singular));
			}
		}
	}



	private function index_old(){
		// 登録 or 更新 or 非表示リストの更新時の処理
	    if($this->request->is('post')) {


	    //アカウント情報の編集する場合(分岐で非表示リストから表示に変更ではない場合)
		    if(empty($this->request->data['User']['hide'])){


		    //ユーザ情報の更新（何も考えずに上書き保存）
		    	$userdata = array(
		    		'username' => "'" . $this->request->data['User']['username'] . "'",
		    		'shop' => "'" . $this->request->data['User']['shop'] . "'",
		    		'status' => "'" . $this->request->data['User']['status'] . "'",
		    	);
				$option =  array('id' => $this->Auth->user('id'));
		    	$this->User->updateALL($userdata ,$option);

		    //ハッシュタグの更新（ユーザテーブル、中間テーブル、タグテーブルの更新をする）
		    	//後からタグテーブルの削除に使うため、更新する前のユーザテーブルを抽出しておく
			    $saved_hashtag = $this->User->find('first', array('conditions' => array('id' => $this->Auth->user('id')),'fields'=>'tag'));	
			    if($saved_hashtag['User']['tag']){
			    	$saved_hashtag_separates = explode(' ',$saved_hashtag['User']['tag']);
			    }
				//ユーザテーブルはそのまま文字列で保存
		    	//入力された値から余分な空白を削除
				$input_hashtag = preg_replace('/　/', ' ', $this->request->data['User']['hashtag']);
				$input_hashtag = preg_replace('/\s+/', ' ', $input_hashtag);
				$input_hashtag = rtrim($input_hashtag);

				//ユーザテーブルの更新
				$this->User->updateALL(array('tag' => "'" . $input_hashtag . "'"), $option);

				//中間テーブルとタグテーブルの操作
			    //入力されたハッシュタグを代入
		    	$input_hashtag_separates = explode(' ',$input_hashtag);

		   		//一度全てのタグを削除し、再度登録をする（表示がユーザテーブルの値をそのまま表示していて、不整合があっても気づかないため、毎回中間テーブルをリセットすることにする）
		    	//中間テーブルからタグの削除
			    $this->Users_tag->deleteAll(array('user_id' => $this->Auth->user('id')));

		    	//中間テーブルにユーザIDとタグIDを登録する。ただし、タグテーブルに登録がなければ登録する。

		    	if(!empty($saved_hashtag_separates)){
			    	foreach ($input_hashtag_separates as $input_hashtag_separate){
			    		if(substr($input_hashtag_separate, 0, 1) == '#'){
				    		//タグテーブルにない場合はマスタ登録して、IDを取得する
				    		if(!$tag_id = $this->Tag->find('first', array('conditions' => array('tag' => $input_hashtag_separate),'fields' => 'id'))){		    		
								$this->Tag->create(false);  //ループ中にsaveする場合は必要
						    	$this->Tag->save(array('tag' => $input_hashtag_separate));
						    	$tag_id = $this->Tag->getLastInsertID();
					    	}
					    	else{
					    	//タグテーブルにある場合はIDを取得する
					    		$tag_id = $tag_id['Tag']['id'];
					    	}

				    		// 中間テーブルへの保存
							$this->Users_tag->create(false);  //ループ中にsaveする場合は必要
					    	$this->Users_tag->save(array('user_id' =>  $this->Auth->user('id'), 'tag_id' => $tag_id));
					    }
			    	}
			    }
		    	//タグテーブルから不要なタグを削除（手間だけどもう一度ループ）
//必ず排他制御すること！！！
		    	//更新前のハッシュタグを検索し、中間テーブルになければ削除する。
		    	if(!empty($saved_hashtag_separates)){
			    	foreach ($saved_hashtag_separates as $saved_hashtag_separate){
			    		//タグテーブルからIDを取得
			    		if($tag_id = $this->Tag->find('first', array('conditions' => array('tag' => $saved_hashtag_separate),'fields' => 'id'))){
				    		//中間テーブルの有無を確認
				    		if(!$this->Users_tag->find('first', array('conditions' => array('tag_id' => $tag_id['Tag']['id']),'fields' => 'id'))){
				    			//なければ削除する
				    			$this->Tag->deleteAll(array('id' => $tag_id['Tag']['id']));
				    		}
				    	}
		    		}
				}
		    //ショップアカウントの場合
		    	if($this->request->data['User']['shop']){


        		//初めての登録の場合はショップテーブルにuser_idとaccountnameを作成する
		    		$shopFlg = $this->Shop->Find('first', array('conditions' => array('user_id' => $this->Auth->user('id')),'fields'=>'user_id'));

        			if(is_null($shopFlg['Shop']['user_id'])){
        				$this->Shop->save(array('user_id'=>$this->Auth->user('id'),'accountname' => $this->Auth->user('accountname')));
        			}
        		//ショップテーブルにデータがある場合
//最後に必ず例外処理を追加する事！！！！
        			else{
				    //▽geocoding▽
        				//住所の作成
						$geocoding_address = $this->request->data['User']['pref'] . $this->request->data['User']['city'] . $this->request->data['User']['ward'] . $this->request->data['User']['town'];


		    			//geocodingの問い合わせ
						$geocode = json_decode(@file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . $geocoding_address . '&sensor=false'),true);
						//必要情報のみ取得
						$lat = $geocode['results'][0]['geometry']['location']['lat'];//緯度
						$lng = $geocode['results'][0]['geometry']['location']['lng'];//経度
					//△geocoding△

			    		$shopdata = array(
			    			'shopname' => "'" . $this->request->data['User']['shopname'] . "'",
			    			'shoptype' => "'" . $this->request->data['User']['shoptype'] . "'",
			    			'pref' => "'" . $this->request->data['User']['pref'] . "'",
				    		'city' => "'" . $this->request->data['User']['city'] . "'",
				    		'ward' => "'" . $this->request->data['User']['ward'] . "'",
				    		'town' => "'" . $this->request->data['User']['town'] . "'",	
				    		'building' => "'" . $this->request->data['User']['building'] . "'",
				    		'lat' => "'" . $lat . "'",
				    		'lng' => "'" . $lng . "'",
				    		'businesshour-s' => "'" . $this->request->data['User']['businesshour-s'] . "'",
				    		'businesshour-e' => "'" . $this->request->data['User']['businesshour-e'] . "'",
				    		'holiday' => "'" . $this->request->data['User']['holiday'] . "'",
				    		'tel' => "'" . $this->request->data['User']['tel'] . "'",
				    		'parking' => "'" . $this->request->data['User']['parking'] . "'",
				    		'url' => "'" . $this->request->data['User']['url'] . "'",
				    		'status' => "'" . $this->request->data['User']['status'] . "'",
				    		'photo' => "'" . $this->request->data['User']['photo'] . "'",
				    		'introduction' => "'" . $this->request->data['User']['introduction'] . "'",
				    	);

        				$option2 =  array('user_id' => $this->Auth->user('id'));
			    		$this->Shop->updateALL($shopdata ,$option2);
			    	}
		    	}
		    	else{
		    		$this->Shop->deleteALL(array('user_id'=>$this->Auth->user('id')) ,false);
		    	}
		   }
		//非表示リストから表示に変更する場合
		   else{

			//現在の非表示リストの取り出し
				$hide = $this->User->Find('all', array('conditions' => array('id' => $this->Auth->user('id')),'fields'=>'hide'));
				$hide = array_shift($hide); //配列の階層が一つ深いため取り出し
			//非表示リストから削除するが、カンマが残りため処理をする
				$delete_hide = str_replace($this->request->data['User']['hide'], '' , $hide['User']['hide']);
			//削除対象が真ん中の場合（2つ並びのカンマを1つにする
				if(strpos($delete_hide,',,')){
					$delete_hide = str_replace(',,', ',', $delete_hide);
				}
			//削除対象が先頭の場合
				elseif(substr($delete_hide, 0 , 1) == ',') {
					$delete_hide = ltrim($delete_hide, ',');
				}
			//削除対象が末尾の場合
				elseif(mb_substr($delete_hide,-1) == ','){
					$delete_hide = rtrim($delete_hide, ',');
				}

			//更新の条件を作成
				$data = array(
					'hide' => "'" . $delete_hide . "'",
				);
			//クエリの発行
				$option =  array('id' => $this->Auth->user('id'));      
				$this->User->updateALL($data ,$option);
		   }
	    }

	// //ユーザ情報の抽出
	//     $userInfor = $this->User->find('first', array('conditions' => array('id' => $this->Auth->user('id'))));
	//     $this->set(compact('userInfor'));

	// //ショップ情報の抽出
	//     $shopInfor = $this->Shop->find('first', array('conditions' => array('user_id' => $this->Auth->user('id'))));
	//     $this->set(compact('shopInfor'));

	// // ショップタイプの抽出
	// 	$this->set( 'shoptype', $this->Shoptype->find( 'list', array( 
	// 	    'fields' => array( 'id', 'typename')
	// 	)));

	// //非表示リストの抽出
	// 	$hideArray = explode(',',$userInfor['User']['hide']);
	// 	$option2 = array(
	// 		'conditions' => array(
	// 			'id' => $hideArray
	// 		)
	// 	);
	// 	$hideList = $this->User->find('all', $option2);
	// 	$this->set(compact('hideList'));
	}
}
