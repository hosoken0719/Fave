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
    }
	public function index(){

		//unFollowボタンを押した時にフォローを解除する
		if($this->request->is('post')){

			$option = 
				array('follow' => $this->Auth->user('id'),
					'follower' => $this->request->data['Follow']['follower']);
			
			$this->Follow->deleteALL($option ,false);
		}

	//フォロー一覧の抽出


	$followData = $this->Follows->find()
		->where([
			'follow' => $this->Auth->user('id')
			]
		)
		->join([
			'table' => 'shops',
			'alias' => 'Shop',
			'type' => 'inner',
			'conditions' => array(
				'AND' => array(
					'Follows.follower_shop = Shop.id',
					'Shop.status = 1',
				)
			),
			]
		)
		->join([
			'table' => 'shoptypes',
			'alias' => 'Shoptype',
			'type' => 'LEFT',
			'conditions'  => 'Shop.shoptype = Shoptype.id'
			]
		)
		->join([
			'table' => 'users',
			'alias' => 'User',
			'type' => 'LEFT',
			'conditions'  => 'Follows.follower_shop = User.id'
			]
		)
	    ->select([
			'id' => 'Follows.follower_shop',
			'shopname' => 'Shop.shopname',
			'shop_id' => 'Shop.id',
			'pref' => 'Shop.pref',
			'city' => 'Shop.city',
			'ward' => 'Shop.ward',
			'Shop_accountname' => 'Shop.accountname',
			'lat' => 'Shop.lat',
			'lng' => 'Shop.lng',
			'typename' => 'Shoptype.typename'
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
				'shopaddress' => $shop->pref.$shop->city.$shop->ward,
				'photo' => $this->FollowComp->getFollowShopPhotos($shop->shop_id)
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
	
	$this->set('title','フォロー一覧 | Fave');
	$this->set(compact('map_zoom'));
	$this->set(compact('gestureHandling'));
	$this->set(compact('map_default_center'));
	$this->set(compact('locate_json'));	//main.ctpで受け取り

	}



}

?>