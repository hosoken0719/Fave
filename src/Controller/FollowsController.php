<?php

namespace App\Controller;
use App\Controller\AppController;
// App::uses('AppController', 'Controller');

class FollowsController extends AppController{


	// public function beforeFilter()
	// {
	// 	// parent::beforeFilter();
 //    	// $this->Auth->deny();

	// }
    public function initialize()
    {
    	parent::initialize();
    	$this->loadComponent('FollowComp'); // コンポーネントの読み込み
	    $this->set('header_link','follow');
    }
	public function index(){

		//unFollowボタンを押した時にフォローを解除する
		// if($this->request->is('post')){

		// 	$option =
		// 		array('follow' => $this->Auth->user('id'),
		// 			'follower' => $this->request->data['Follow']['follower']);

		// 	$this->Follow->deleteALL($option ,false);
		// }

		if (!$this->Auth->user()) {
			$this->redirect(['plugin' => 'CakeDC/Users', 'controller' => 'Users', 'action' => 'login']);
		}else{

			//フォロー一覧の抽出
			$followData = $this->Follows->find()
				->where([
					'follow' => $this->Auth->user('id')
				])
		        ->contain(['shops' => ['shoptypes','prefectures']])
			    ->select([
					'id' => 'Follows.follower_shop',
					'shopname' => 'shops.shopname',
					'shop_id' => 'shops.id',
					'pref' => 'prefectures.name',
					'address' => 'shops.address',
					'Shop_accountname' => 'shops.accountname',
					'lat' => 'shops.lat',
					'lng' => 'shops.lng',
					'thumbnail' => 'shops.thumbnail',
					'typename' => 'shoptypes.typename'
			]);


			$this->set(compact('followData'));

		//googlemap関連の処理
			$map_shops = array();
			foreach($followData as $shop){ //follow/followeにはショップ以外が含まれているが、地図には不要のため削除する

				if(!is_null($shop->shopname)){
					$locate = array(
						'lat' => $shop->lat,
						'lng' => $shop->lng,
						// 'shopname' => $shop['Shop']['shopname'] . "<br />" . $shop['Shoptype']['typename'],
						'shopname' => $shop->shopname,
						'shoptype' => $shop->typename,
						'shop_id' => $shop->shop_id,
						'account' => $shop->Shop_accountname,
						'shopaddress' => $shop->pref.$shop->address,
						'photo' => $shop->thumbnail.'media?size=t',
						// 'photo' => $this->FollowComp->getFollowShopPhotos($shop->shop_id)
					);
					array_push($map_shops,$locate);
				}
			}

			$locate_json = json_encode($map_shops);
			$map_default_center = null;


		//ズームの値を設定	
			$map_zoom = 11;

		//スマホの2本指操作を解除
			$gestureHandling = "gestureHandling: 'greedy'";

			$this->set('title','お気に入りのお店　- Fave');
			$this->set(compact('map_zoom'));
			$this->set(compact('gestureHandling'));
			$this->set(compact('map_default_center'));
			$this->set(compact('locate_json'));	//main.ctpで受け取り

			}

		}

	}

?>