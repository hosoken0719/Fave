<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;


class ShopsController extends AppController {

	public $components = ['ShopRegist','Businesshour','Csrf'];

    public function initialize()
    {
      parent::initialize();

      $this->loadComponent('FollowComp'); // コンポーネントの読み込み
    }

	public function index(){
		
    $ShoptypeTable = TableRegistry::get('shoptypes');
    $FollowTable = TableRegistry::get('follows');
    $UserTagTable = TableRegistry::get('users_tags');
    $TagTable = TableRegistry::get('tags');
    $UsersTable = TableRegistry::get('users');
    $this->loadComponent('FollowComp'); // コンポーネントの読み込み

	//ショップ情報の抽出
    	$shopData = $this->Shops->find()
    	//非公開時の処理を追加すること！！！
    	->where([
    		'Shops.id' => $this->request->getParam('shop_id'),
			'Shops.status' => '1'
		])
		->contain(['shoptypes'])
		->select([
			'shopname' => 'shopname',
			'user_id' => 'Shops.user_id',
			'shop_id' => 'Shops.id',
			'shop_accountname' => 'Shops.accountname',
			'introduction' => 'Shops.introduction',
			'pref' => 'pref',
			'city' => 'city',
			'ward' => 'ward',
			'town' => 'town',
			'buolding' => 'building',
			'parking' => 'parking',
			'tel' => 'tel',
			'homepage' => 'homepage',
			// 'tag' => 'Users.tag',
			'lat' => 'Shops.lat',
			'lng' => 'Shops.lng',
			'typename' => 'shoptypes.typename'
		])
		->first();

		//ショップの写真パスを取得
		$dir = SHOPPHOTO_UPLOADDIR . '/photo_shop/' . $shopData->shop_id . '/';
		$photo_list = glob($dir . '*.png');
		if(!empty($photo_list)){
			$photoShop_fullpath = max($photo_list); //最新写真のみ抽出
			$photoShop_array = explode('/',$photoShop_fullpath); //サーバパスの取得となるため、最後のファイル名だけを取得
			$photoShop = "https://fave-jp.info/img/photo_shop/" . $shopData->shop_id . "/" . end($photoShop_array);
		}else{
			$photoShop = null;
		}
 	// ハッシュタグにリンクをつける
		// if(!is_null($shopData->tag)){
		//     $saved_hashtag_separates = explode(' ',$shopData->tag);
		// 	$hashtag = NULL;
		//     foreach ($saved_hashtag_separates as $saved_hashtag_separate) {
		// 	    $hashtag .= "<a href='#'>". $saved_hashtag_separate . "</a> ";
		//     }
		// }else{
		// 	$hashtag ='';
		// }

	// ショップの全フォロワー数を取得
		$this->set('countFollowShop',$this->FollowComp->getShopFollowUserCount($shopData->shop_id));
	// ショップの全フォロワーのレーティングを取得
		$this->set('avgFollowShop',$this->FollowComp->getShopRatingByShopId($shopData->shop_id));

	//ログインユーザが表示ショップをフォローしているか確認
		$isFollowId['follow'] =  $this->Auth->user('id');
		$isFollowId['follower_shop'] = $this->request->getParam('shop_id');
		$this->set('ShopFollowData',$this->FollowComp->isShopFollow($isFollowId));

	//ショップフォロワーのうち、ログインユーザがフォローしているユーザ数
		$FollowerUser['FollowedId'] = $this->FollowComp->getLoginUserFollowShopArray($this->Auth->user('id'));
		$FollowerUser['follower_shop'] = $this->request->getParam('shop_id');
		$this->set('countShopFollowMyUser',$this->FollowComp->getShopCountMyFollowUser($FollowerUser));
		$this->set('avgShopFollowMyUser',$this->FollowComp->getShopRatingMyFollowUser($FollowerUser));



        //フォローユーザを取得
        $followUserDatas = $this->FollowComp->getShopFollowUserDatas($shopData->shop_id);
	//営業時間
	//▽▼営業時間はbusiness_hoursから取得▽▼
    	//DBから取り出し
    	$BusinessHoursTable = TableRegistry::get('business_hours');
		$businessHoursDatas = $BusinessHoursTable->Find()
		->where(['business_hours.shop_id' =>  $this->request->getParam('shop_id')]);

		$bussiness_days_hours = $this->Businesshour->setBussinessDaysHoursFromDB($businessHoursDatas);

		$this->set('bussiness_hours',$bussiness_days_hours);
		$this->set('week_ja',$this->Businesshour->getWeekDayJa()); //日本語曜日名の取得




		// 	//$followedフラグの変更（0=未フォロー、1>フォロー済み）
		$checkFollow = ['follow'=>$this->Auth->user('id'),'follower_shop'=>$shopData->shop_id];
    	$myrating = $this->FollowComp->isShopFollow($checkFollow);
		$this->set(compact('FollowerUser','photoShop','shopData','hashtag','myrating','followShopDatas','followUserDatas','followerShopDatas','followerUserDatas','shop_data_summary','shop_icon','countFollower'));	//ショップ写真

        

//googlemap関連の処理
	// 該当ショップの位置を地図に代入
		$map_shops = []; //2次元配列にする
		$map_shop = [
			'lat' => $shopData->lat,
			'lng' => $shopData->lng,
			'shopname' => $shopData->shopname,
			'shoptype' => $shopData->typename,
			'shop_id' => $shopData->shop_id,
		];
		array_push($map_shops,$map_shop);

	//地図のセンターを設定
		$map_default_center = $shopData->lat. ',' . $shopData->lng;
		
		// $shops = array_merge($followList,$followerList);  //フォロー、フォローワーリストの結合
		// $shops = array_unique($shops, SORT_REGULAR);  //重複削除
		// $shops = array_values($shops);  //配列番号(index)を振り直し
		// $shops = array_filter($shops);  //空白の配列を削除
		// foreach($shops as $shop){ //follow/followeにはショップ以外が含まれているが、地図には不要のため削除する
		// 	if(!is_null($shop['Shop']['shopname'])){
		// 		$locate = array(
		// 			'lat' => $shop['Shop']['lat'],
		// 			'lng' => $shop['Shop']['lng'],
		// 			'shopname' => $shop['Shop']['shopname'] . "<br />" . $shop['Shoptype']['typename'],
		// 			'account' => $shop['Shop']['accountname'],
		// 		);
		// 		array_push($map_shops,$locate);
		// 	}
		// }

	//ズームの値を設定	
		$map_zoom = 14;
		$locate_json = json_encode($map_shops); 

		$title = $shopData->shopname . ' | Fave';
		$this->set(compact('title','map_zoom','map_default_center','locate_json'));//locate_jsonはmain.ctpで受け取り

	}

}