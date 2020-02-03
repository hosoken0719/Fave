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
		$this->loadComponent('ShopComp'); // コンポーネントの読み込み
		$this->Auth->allow();
    }

	public function index(){


	    $ShoptypeTable = TableRegistry::get('shoptypes');
	    $FollowTable = TableRegistry::get('follows');
	    $UserTagTable = TableRegistry::get('users_tags');
	    $UsersTable = TableRegistry::get('users');


	//ショップ情報の抽出
    	$shopData = $this->getShopData($this->request->getParam('shop_id'));
    	$LoginUserFollow['follower_user'] = $this->FollowComp->getLoginUserFollowUserArray($this->Auth->user('id'));
    	$shopData = $this->ShopComp->rating_avg($shopData,$LoginUserFollow['follower_user']);
    	$shopData = $shopData->first();

    	$user_infor = $this->setCommonValue($shopData);



	//写真情報取得
		$shop_photos = $this->ShopComp->getShopPhotos($shopData->shop_id,6);
		$shop_photo_dir = $this->ShopComp->getShopPhotoDir($shopData->shop_id).'/thumbnail/large_';
		// $photo_list = glob($dir . '*.png');
		// $shop_photos = [];
		// if(!empty($photo_list)){
		// 	for($i=count($photo_list);$i>count($photo_list)-7;$i--){
		// 		if(!empty($photo_list[$i])){
		// 			$photoShop_fullpath = $photo_list[$i]; //最新写真のみ抽出
		// 			$photoShop_array = explode('/',$photoShop_fullpath); //サーバパスの取得となるため、最後のファイル名だけを取得
		// 			array_push($shop_photos,"https://fave-jp.info/img/shop_photos/" . $shopData->shop_id . "/thumbnail/middle_" . end($photoShop_array));

		// 		}
		// 	}
		// }else{
		// 	$shop_photos = null;
		// }

        $ShopPhotoSnsTable = TableRegistry::getTableLocator()->get('shop_photos_sns');
        $instagram_photos = $ShopPhotoSnsTable->find()->where(['shop_id'=>$shopData->shop_id]);
		$instagram_photos_count = $instagram_photos->count();
		$this->set(compact('shop_photos','shop_photo_dir','instagram_photos','instagram_photos_count'));



	//営業時間
	//▽▼営業時間はbusiness_hoursから取得▽▼
    	//DBから取り出し
    	$BusinessHoursTable = TableRegistry::get('business_hours');
		$businessHoursDatas = $BusinessHoursTable->Find()
		->where(['business_hours.shop_id' =>  $this->request->getParam('shop_id')]);

		list($bussiness_hours,$bussiness_hours_flg) = $this->Businesshour->setBussinessDaysHoursFromDB($businessHoursDatas);


		$this->set(compact('bussiness_hours','bussiness_hours_flg'));
		$this->set('week_ja',$this->Businesshour->getWeekDayJa()); //日本語曜日名の取得

	// 	//$followedフラグの変更（0=未フォロー、1>フォロー済み）
		$checkFollow = ['follow'=>$this->Auth->user('id'),'follower_shop'=>$shopData->shop_id];
    	$myrating = $this->FollowComp->isShopFollow($checkFollow);

		$this->set(compact('FollowerUser','shopData','hashtag','myrating','followShopDatas','followUserDatas','followerShopDatas','followerUserDatas','shop_data_summary','shop_icon','countFollower'));	//ショップ写真

	//タイトルの設定
		$this->set('title',$shopData->shopname.'('.$shopData->kana.')のお店情報 - Fave');

	}

//お店をフォローしている人一覧（自分がフォローしている人のみ表示）
	public function favoritedFollow(){

		//ログインしていない場合は全ユーザへリダイレクト
		if(!$this->request->getSession()->read('Auth.User')){
   			$this->redirect(['action' => $this->request->getParam('shop_id').'/favoritedAll']);
		}

	    $this->loadComponent('FollowComp'); // コンポーネントの読み込み

	//ショップ情報の抽出
    	$shopData = $this->getShopData($this->request->getParam('shop_id'));
    	$LoginUserFollow['follower_user'] = $this->FollowComp->getLoginUserFollowUserArray($this->Auth->user('id'));
    	$shopData = $this->ShopComp->rating_avg($shopData,$LoginUserFollow['follower_user']);
    	$shopData = $shopData->first();

	//ショップ情報の抽出
    	$user_infor = $this->setCommonValue($shopData);
    	$favoriteDatas= $this->FollowComp->getShopFollowUserDatas($shopData['shop_id'])->where(['follow IN' => $user_infor['LoginUserFollowerUser']]);

    	$this->set(compact('favoriteDatas','shopData'));

	//タイトルの設定
		$this->set('title',$shopData->shopname.'('.$shopData->kana.')の口コミ（フォローユーザ） - Fave');
	}

//お店をフォローしている人一覧（全員表示）
	public function favoritedAll(){
	    $this->loadComponent('FollowComp'); // コンポーネントの読み込み

	//ショップ情報の抽出
    	$shopData = $this->getShopData($this->request->getParam('shop_id'));
    	$LoginUserFollow['follower_user'] = $this->FollowComp->getLoginUserFollowUserArray($this->Auth->user('id'));
    	$shopData = $this->ShopComp->rating_avg($shopData,$LoginUserFollow['follower_user']);
    	$shopData = $shopData->first();

	//ログインユーザ情報の抽出
    	$user_infor = $this->setCommonValue($shopData);

   	//ログインユーザのフォローユーザ情報を取得
    	$favoriteDatasLoginUser = $this->FollowComp->getShopFollowUserDatas($shopData['shop_id'])->where(['follow' => $user_infor['user_id']]);

   	//フォローユーザを取得
    	if(!empty($user_infor['LoginUserFollowerUser'])){
	    	$favoriteDatas = $this->FollowComp->getShopFollowUserDatas($shopData['shop_id'])->where(['follow IN' => $user_infor['LoginUserFollowerUser']]);

   		// //フォローしていないユーザを取得するが、ログインユーザは別のSQLで取得しているため対象外とする
    		$LoginUserFollowerUserIncOwn = array_merge($user_infor['LoginUserFollowerUser'],[$user_infor['user_id']]); //ログインユーザを追加
    		$favoriteDatasNotIn = $this->FollowComp->getShopFollowUserDatas($this->request->getParam('shop_id'))->where(['NOT' => ['follow IN' => $LoginUserFollowerUserIncOwn]]);

    	}else{//ログインユーザが誰もフォローしていない場合
    		$favoriteDatasNotIn = [];
    		$favoriteDatas = $this->FollowComp->getShopFollowUserDatas($shopData['shop_id'])->all();
    	}

    	$this->set(compact('favoriteDatas','favoriteDatasLoginUser','favoriteDatasNotIn','shopData'));

	//タイトルの設定
		$this->set('title',$shopData->shopname.'('.$shopData->kana.')の口コミ（全てのユーザ） - Fave');
	}

//写真
	public function photo(){
	    $this->loadComponent('FollowComp'); // コンポーネントの読み込み

	//ショップ情報の抽出
    	$shopData = $this->getShopData($this->request->getParam('shop_id'));
    	$LoginUserFollow['follower_user'] = $this->FollowComp->getLoginUserFollowUserArray($this->Auth->user('id'));
    	$shopData = $this->ShopComp->rating_avg($shopData,$LoginUserFollow['follower_user']);
    	$shopData = $shopData->first();
    	$this->set(compact('shopData'));

	//ログインユーザが表示ショップをフォローしているか確認
		$isFollowId['follow'] =  $this->Auth->user('id');
		$isFollowId['follower_shop'] = $this->request->getParam('shop_id');
		$this->set('ShopFollowData',$this->FollowComp->isShopFollow($isFollowId));


	//写真情報取得
		$shop_photos = $this->ShopComp->getShopPhotos($shopData->shop_id);
		$shop_photo_dir = $this->ShopComp->getShopPhotoDir($shopData->shop_id).'/thumbnail/midium_';

        $ShopPhotoTable = TableRegistry::getTableLocator()->get('shop_photos');
        $instagram_photos = $ShopPhotoTable->find()->where(['shop_id'=>$shopData->shop_id]);
		$instagram_photos_count = $instagram_photos->count();
		$this->set(compact('shop_photos','shop_photo_dir','instagram_photos','instagram_photos_count'));

	//タイトルの設定
		$this->set('title',$shopData->shopname.'('.$shopData->kana.')の写真 - Fave');
	}

//お気に入り登録
	public function postFavorite(){
	    $this->loadComponent('FollowComp'); // コンポーネントの読み込み

	//ショップ情報の抽出
    	$shopData = $this->getShopData($this->request->getParam('shop_id'));
    	$LoginUserFollow['follower_user'] = $this->FollowComp->getLoginUserFollowUserArray($this->Auth->user('id'));
    	$shopData = $this->ShopComp->rating_avg($shopData,$LoginUserFollow['follower_user']);
    	$shopData = $shopData->first();
    	$this->set(compact('shopData'));

	//ログインユーザが表示ショップをフォローしているか確認
		$isFollowId['follow'] =  $this->Auth->user('id');
		$isFollowId['follower_shop'] = $this->request->getParam('shop_id');
		$this->set('ShopFollowData',$this->FollowComp->isShopFollow($isFollowId));


	//タイトルの設定
		$this->set('title',$shopData->shopname.'('.$shopData->kana.')をお気に入りに登録 - Fave');
	}


	//お気に入り登録のDB登録処理
	public function register(){

		if ($this->request->is('post')) {

	        $FollowsTable = TableRegistry::get('follows');

 		    //新規にフォローする場合
	        if($this->request->getData('method') === 'add'){
		    	$follow = $FollowsTable->newEntity();
		        $follow->follow =  $this->Auth->user('id');
		        $follow->follower_shop = $this->request->getData('shop_id');
		        $follow->rating = $this->request->getData('rating');
		        $follow->review = $this->request->getData('review');
		        $time = Time::now();
		        $follow->created = $time->format('Y/m/d H:i:s');

		        if($FollowsTable->save($follow)){
				// 	return $this->redirect(['controller' => 'shops', 'action' => '/' ,$this->request->getData('shop_id')]);
				// }else{
					return $this->redirect(['controller' => 'shops', 'action' => $this->request->getData('shop_id'),'post-favorite']);
				}
			// }elseif($this->request->getData('method') === 'update'){
	  //       	$shopEntity = $FollowsTable->patchEntity($shopData, $basic_information);

		    // }elseif($this->request->getData('method') === 'delete'){
			}else{
				$FollowsTable->deleteAll(['follow'=>$this->Auth->user('id'),'follower_shop'=>$this->request->getData('shop_id')]);
				return $this->redirect(['controller' => 'shops', 'action' => $this->request->getData('shop_id'),'post-favorite']);
			}

		$this->autoRender = false;
		}
	}

//地図
	public function map(){

 	// コンポーネントの読み込み
		$this->loadComponent('FollowComp');

	//ショップ情報の抽出
    	$shopData = $this->getShopData($this->request->getParam('shop_id'));
    	$LoginUserFollow['follower_user'] = $this->FollowComp->getLoginUserFollowUserArray($this->Auth->user('id'));
    	$shopData = $this->ShopComp->rating_avg($shopData,$LoginUserFollow['follower_user']);
    	$shopData = $shopData->first();
    	$this->set(compact('shopData'));

	//ログインユーザが表示ショップをフォローしているか確認
		$isFollowId['follow'] =  $this->Auth->user('id');
		$isFollowId['follower_shop'] = $this->request->getParam('shop_id');
		$this->set('ShopFollowData',$this->FollowComp->isShopFollow($isFollowId));



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

	//ズームの値を設定	
		$map_zoom = 14;
		$locate_json = json_encode($map_shops);

	//スマホの2本指操作を解除
		$gestureHandling = "gestureHandling: 'greedy'";
		$title = $shopData->shopname . ' | Fave';
		$this->set(compact('title','map_zoom','map_default_center','locate_json','gestureHandling'));//locate_jsonはmain.ctpで受け取り

	//タイトルの設定
		$this->set('title',$shopData->shopname.'('.$shopData->kana.')の地図 - Fave');
	}

//favoritedFollowとfavoritedAllの共通処理
	private function setCommonValue($shopData){

	    $this->loadComponent('FollowComp'); // コンポーネントの読み込み

	    // ユーザIDを取得
	    $login_user_id = $this->Auth->user('id');

	    //ログインユーザがフォローしているショップを取得
	    $LoginUserFollow['follower_shop'] = $this->FollowComp->getLoginUserFollowShopArray($login_user_id);
	    //誰もフォローしていない場合、ダミーを代入
	    if(empty($LoginUserFollow['follower_shop'])){
	      $LoginUserFollow['follower_shop'] = null;
	    }
	    $LoginUserFollow['follower_user'] = $this->FollowComp->getLoginUserFollowUserArray($login_user_id);
	    $this->set(compact('LoginUserFollow','login_user_id'));

	// ショップの全フォロワー数を取得
		$this->set('countFollowShop',$this->FollowComp->getShopFollowUserCount($shopData->shop_id));
	// ショップの全フォロワーのレーティングを取得
		$this->set('avgFollowShop',$this->FollowComp->getShopRatingByShopId($shopData->shop_id));

	//ログインユーザが表示ショップをフォローしているか確認
		$isFollowId['follow'] =  $this->Auth->user('id');
		$isFollowId['follower_shop'] = $this->request->getParam('shop_id');
		$this->set('ShopFollowData',$this->FollowComp->isShopFollow($isFollowId));

	    return ['user_id'=>$login_user_id,'LoginUserFollowShop'=>$LoginUserFollow['follower_shop'],'LoginUserFollowerUser'=>$LoginUserFollow['follower_user']];
	}

//仮引数のショップ情報を取得する
  private function getShopData($shop_id,$type=null){

	//ショップ情報の抽出
    	$shopData = $this->Shops->find()
    	//非公開時の処理を追加すること！！！
    	->where([
    		'Shops.id' => $shop_id,
			'Shops.status' => '1'
		])
		// ->group(['Shops.id'])
		->contain(['shoptypes','shoptypes2','prefectures','follows'])
		->select([
			'shopname' => 'shopname',
			'user_id' => 'Shops.user_id',
			'kana' => 'Shops.kana',
			'shop_id' => 'Shops.id',
			'branch' => 'Shops.branch',
			'shop_accountname' => 'Shops.accountname',
			'shop_business_hour_detail' => 'Shops.business_hour_detail',
			'introduction' => 'Shops.introduction',
			'pref' => 'prefectures.name',
			'city' => 'address',
			'buolding' => 'building',
			'parking' => 'parking',
			'phone_number' => 'phone_number',
			'homepage' => 'homepage',
			// 'tag' => 'Users.tag',
			'lat' => 'Shops.lat',
			'lng' => 'Shops.lng',
			'typename' => 'shoptypes.typename',
			// 'typename2' => 'shoptypes2.typename',
			'instagram' => 'Shops.instagram',
			'thumbnail' => 'Shops.thumbnail',
		]);
		return $shopData;
  }




}