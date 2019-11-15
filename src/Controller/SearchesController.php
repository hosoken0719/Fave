<?php

namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;

class SearchesController extends AppController{

	public $components = ['ShopRegist','Businesshour'];

    public function initialize()
    {
	    parent::initialize();
	    $this->loadComponent('FollowComp'); // コンポーネントの読み込み
	    $this->loadComponent('ShopComp'); // コンポーネントの読み込み
	    $this->set('header_link','search');
      	$this->Auth->allow();
    }

	public function index(){

	    $ShopTable = TableRegistry::get('shops');
	    $ShoptypeTable = TableRegistry::get('shoptypes');
	    $FollowTable = TableRegistry::get('follows');
	    $UserTagTable = TableRegistry::get('users_tags');
	    $TagTable = TableRegistry::get('tags');

	//*ショップタイプの取得
		$this->set('typename',$ShoptypeTable->find('list'));

	//URLの全パラメータを取得
		$parameter = $this->request->getQueryParams();

	//検索窓か検索結果を表示する判定フラグ
		$result_flg = 0; //0は検索窓を表示

	//Queryの作成
		$shopDatas = $ShopTable->find('all')
		->where(['shops.status' => '1'])
		->contain(['shoptypes','prefectures','follows','shop_photos'])
		->group(['shops.id'])
		->select([
        	'shopname' => 'shops.shopname',
        	'branch' => 'shops.branch',
        	'user_id' => 'shops.user_id',
        	'shop_id' => 'shops.id',
        	'pref' => 'prefectures.name',
        	'address' => 'shops.address',
        	'lat' => 'shops.lat',
        	'lng' => 'shops.lng',
        	'typename' => 'shoptypes.typename',
        	'thumbnail' => 'shops.thumbnail',
			]
		)
        ->order(['avg_followed' => 'DESC']); //フォロー平均順

		//フォロー平均の条件設定
	    //ログインユーザがフォローしているユーザーを取得
		$LoginUserFollow['follower_user'] = $this->FollowComp->getLoginUserFollowUserArray($this->Auth->user('id'));
		$this->set('follower_user',$LoginUserFollow['follower_user']);

		//ショップタイプの指定がある場合は検索条件に追加する
		if(!empty($parameter['shoptype'])){
			$shoptype = $parameter['shoptype'];
			$shopDatas->where(['OR'=>['shops.shoptype' => $parameter['shoptype'],['shops.shoptype2' => $parameter['shoptype']]]]);
			$result_flg = 1; //検索結果を表示するためのフラグを代入

			$title = $ShoptypeTable->find()->where(['id'=>$parameter['shoptype']])->select(['shoptype'=>'typename'])->first();
		}else{
			$shoptype = "";
			$title['shoptype'] = null;
		}

		//エリアの指定がある場合は検索条件に追加する
		if(!empty($parameter['area'])){

			$area = $parameter['area'];
		    $shopDatas
			->where([
				'OR' => [
					['prefectures.name LIKE' => '%'.$area.'%'],
					['shops.address LIKE' => '%'.$area.'%'],
					['shops.building LIKE' => '%'.$area.'%']
				]
			]);
			$result_flg = 1; //検索結果を表示するためのフラグを代入
			$title['area'] = $parameter['area'];
		}else{
			$area = "";
			$title['area'] = null;
		}

    	//平均をqueryに追加
        $shopDatas = $this->ShopComp->rating_avg($shopDatas,$LoginUserFollow['follower_user']);
		$this->set(compact('shoptype','area'));

	//googlemap関連の処理
		$map_shops = array();
		$followed = array();
		foreach($shopDatas as $shopData): //follow/followeにはショップ以外が含まれているが、地図には不要のため削除する
			// if(!is_null($shopData->shopname)){

				$locate = array(
					'lat' => $shopData->lat,
					'lng' => $shopData->lng,
					'shopname' => $shopData->shopname,
					'shoptype' => $shopData->typename,
					'shop_id' => $shopData->shop_id,
					'shopaddress' => $shopData->pref.$shopData->city.$shopData->ward,
				);
				array_push($map_shops,$locate);

				$checkRating = ['follow'=>$this->Auth->user('id'),'follower_shop'=>$shopData->shop_id];
				$rating[$shopData->shop_id] = $this->FollowComp->isShopFollow($checkRating);

			// }
		endforeach;

	//検索結果が1件以上あった場合は地図の設定
		if(!empty($map_shops)){
		//最初に表示するお店を地図のセンターにする
			$map_default_center = $map_shops[0]['lat'].",".$map_shops[0]['lng'];

		//ズームの値を設定
			$map_zoom = 11;
			$locate_json = json_encode($map_shops);

		//スマホの2本指操作を解除
			$gestureHandling = "gestureHandling: 'greedy'";

		}
		$this->set(compact('rating','map_zoom','gestureHandling','map_default_center','locate_json','followed','followers','shopDatas','result_flg'));


 	//タイトルの設定
		if(!empty($title['area']) & !empty($title['shoptype'])) {
			$this->set('title',$title['area'] . 'エリアにある' . $title['shoptype'] . 'のお店を検索 - Fave');
		}
		elseif(!empty($title['area']) & empty($title['shoptype'])) {
			$this->set('title',$title['area'] . 'エリアにあるお店を検索 - Fave');
		}
		elseif(empty($title['area']) & empty(!$title['shoptype'])) {
			$this->set('title',$title['shoptype'] . 'のお店を検索 - Fave');
		}else{
			$this->set('title','お店を検索 - Fave');
		}

		//inputとselectboxのtemplate
	 	$template = [
	 		'label' => '<div{{attrs}}>{{text}}</div>',
	 		'input' => '<div class="inputbox"><input type="{{type}}" name="{{name}}"{{attrs}}></div>',
	 		'select' => "<div class='selectbox'><select name='{{name}}'{{attrs}}>{{content}}</select></div>",
	 	];

		$this->set(compact('template'));
	}

}


?>