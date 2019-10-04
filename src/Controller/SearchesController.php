<?php

namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
// App::uses('AppController', 'Controller');

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

				$shopDatas = $ShopTable->find()
				->where(['shops.status' => '1'])
				->contain(['shoptypes','prefectures'])
		        ->select([
		        	'shopname' => 'shops.shopname',
		        	'user_id' => 'shops.user_id',
		        	'shop_id' => 'shops.id',
		        	'pref' => 'prefectures.name',
		        	'address' => 'shops.address',
		        	'lat' => 'shops.lat',
		        	'lng' => 'shops.lng',
		        	'typename' => 'shoptypes.typename',
		        	'thumbnail' => 'shops.thumbnail'
					]
				);

				//ショップタイプの指定がある場合は検索条件に追加する
				if(!empty($parameter['shoptype'])){
					$shoptype = $parameter['shoptype'];
					$shopDatas->where(['OR'=>['shops.shoptype' => $parameter['shoptype'],['shops.shoptype2' => $parameter['shoptype']]]]);
					$result_flg = 1; //検索結果を表示するためのフラグを代入
				}else{
					$shoptype = "";
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
				}else{
					$area = "";
				}

				$this->set(compact('shoptype','area'));
			    //自分がフォローしているユーザーを取得
			    $LoginUserFollow['follower_user'] = $this->FollowComp->getLoginUserFollowUserArray($this->Auth->user('id'));
			   $this->set('follower_user',$LoginUserFollow['follower_user']);

				// foreach ($shopDatas as $shopData) {
				// 	$this->ShopComp->rating_avg($shopData->shop_id,$LoginUserFollow['follower_user']);
				// }


			    //Follow済み($followed)
			    // $followers = $FollowTable->find()
			    // ->where(['follow' => $this->Auth->user('id')])
			    // ->select(['follower'=>'follower','rating'=>'rating']);

		// $checkFollow = ['follow'=>$this->Auth->user('id'),'follower'=>$shopData->shop_id];
  //   	$rating = $this->FollowComp->isFollow($checkFollow);
					//googlemap関連の処理
					$map_shops = array();
					$followed = array();
					foreach($shopDatas as $shopData): //follow/followeにはショップ以外が含まれているが、地図には不要のため削除する
						if(!is_null($shopData->shopname)){

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

						}
					endforeach;

					$map_default_center = '35.1770949,137.0165602';


				//ズームの値を設定	
					$map_zoom = 11;
					$locate_json = json_encode($map_shops);

				//スマホの2本指操作を解除
					$gestureHandling = "gestureHandling: 'greedy'";

					$this->set('title','検索結果 | Fave');
					$this->set(compact('title','rating','map_zoom','gestureHandling','map_default_center','locate_json','followed','followers','shopDatas','result_flg'));


					$this->set('title','検索 | Fave');

	//inputとselectboxのtemplate
 	$template = [
 		'label' => '<div{{attrs}}>{{text}}</div>',
 		'input' => '<div class="inputbox"><input type="{{type}}" name="{{name}}"{{attrs}}></div>',
 		'select' => "<div class='selectbox'><select name='{{name}}'{{attrs}}>{{content}}</select></div>",
 	];

	$this->set(compact('template'));
	}








// } else {
//     echo json_encode(array());
// }



// App::uses('View', 'View');
// 	    $field = 'tag';
// 	    $term = $this->params['url']['term'];
// var_dump($term);
// 	    $condition = array();
// 	    if(!empty($term)){
// 	        $condition = array('Tag.'.$field.' like' => "%".$term."%");
// 	    }

// 	    $query = array(
// 	        'fields' => array($field),
// 	        'conditions' => $condition,
// 	        'limit' => 10,
// 	        'order' => array('Tag.'.$field => 'ASC'),
// 	        'group' => $field,
// 	    );

// 	    $data = array();
// 	    $items = $this->Tag->find('all', $query);
// 	    foreach ($items as $item) {
// 	        $cnt = array_push($data, $item['Tag'][$field]);
// 	    }

// 		$this->viewClass = 'Pdf';
// 		$this->set(compact('data'));
// 		$this->set('_serialize', 'data');
    // 出力したいデータ
 //    $result = ['hoge', 'piyo', 'fuga'];
 //    $this->viewClass = 'Json';
 //    $this->set([
 //        'result' => $result,
 //        '_serialize' => ['result'],
 //        // 下記変数をセットすると出力がjsonpになる。
 //        '_jsonp' => true
 //    ]);
	// }


}


?>