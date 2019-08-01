<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class ShopRegistsController extends AppController {

	public $components = ['ShopRegist','Businesshour'];


    public function initialize()
    {
      	parent::initialize();
		$this->set('title','投稿 | Fave');

   		$this->session = $this->getRequest()->getSession();

    }


	public function index(){
 
	    $ShopTable = $this->getTableLocator()->get('shops');
	    $shoptypes = $this->getTableLocator()->get('shoptypes');

	//初期値設定
	    //他のページから戻った場合
	    if($this->request->is('post')){
	    	$shopname = $this->session->read('shop.shopname');
	    	$branch = $this->session->read('shop.branch');
	    	$kana = $this->session->read('shop.kana');
	    	$shoptype = $this->session->read('shop.shoptype');
	    	$shoptype2 = $this->session->read('shop.shoptype2');
	    	$pref = $this->session->read('shop.pref');
	    	$address = $this->session->read('shop.address');
	    	$building = $this->session->read('shop.building');
	    	$phone_number = $this->session->read('shop.phone_number');
	    	$business_hour_detail = $this->session->read('shop.business_hour_detail');
	    	$homepage = $this->session->read('shop.homepage');
	    	$introduction = $this->session->read('shop.introduction');
	    	$parking = $this->session->read('shop.parking');
	    //初めてアクセスした場合は、前回のセッションを削除する
		}else{
			$this->session->delete('shop');
			$shopname = '';
		    $kana = '';
	    	$shopname = '';
	    	$branch = '';
	    	$kana = '';
	    	$shoptype = '';
	    	$shoptype2 = '';
	    	$pref = '';
	    	$address = '';
	    	$building = '';
	    	$business_hour_detail = '';
	    	$phone_number = '';
	    	$parking = '';
	    	$homepage = '';
	    	$introduction = '';
		}
		$this->set(compact('shopname','branch','kana','shoptype','shoptype2','pref','address','building','phone_number','business_hour_detail','parking','homepage','introduction'));


	//selectbox表示設定
		//*ショップタイプ一覧の取得
		$ShoptypeTable = TableRegistry::get('shoptypes');
		$this->set('typename',$ShoptypeTable->find('list'));

		//*県一覧の取得
		$PrefTable = TableRegistry::get('prefecture');
		$this->set('pref_list',$PrefTable->find('list')->where(['enable' => 1]));

		$week_en = $this->Businesshour->getWeekDayEn();
		$week_ja = $this->Businesshour->getWeekDayJa();
	
	//曜日と時間の設定
		for($pattern = 1; $pattern <= 3; $pattern++){
			//openの曜日は$week[$week_en_add]にopenを代入する
			//曜日が一つでもopenの場合は、フラグを立てる
			$flgDisplay[$pattern]['button'] = '';//営業時間を追加するボタンの表示
			$flgDisplay[$pattern]['open_hour'] = 'hide';//2つ目、3つ目の営業時間をデフォルト表示するか判定
			for($count_week = 0; $count_week <= 6; $count_week++ ){
				$week_en_add = $week_en[$count_week]; //後ろの行を見やすくするために代入
				if($this->readSessionValue('week'.$pattern.'_'.$week_en_add) === 'open'){
					$week[$week_en_add][$pattern] = 'open'; //営業曜日にopenを代入する
					$flgDisplay[$pattern-1]['button'] = 'hide';//2つ目、3つ目の営業時間を表示するか判定
					$flgDisplay[$pattern]['open_hour'] = '';//2つ目、3つ目の営業時間をデフォルト表示するか判定
				}
				else{
					$week[$week_en_add][$pattern] = 'close';//営業曜日にcloseを代入する
				}
			}
			//営業時間を代入する
			$default_hour['open'][$pattern] = $this->readSessionValue("week".$pattern."_start");
			$default_hour['close'][$pattern] = $this->readSessionValue("week".$pattern."_end");
		}
	//営業時間のセット
		for ($select_time = 0; $select_time <= 47; $select_time++) {
			$open_hour_tmp = date("H:i", strtotime("00:00 +". $select_time * 30 ." minute"));
			$open_hour_list[$open_hour_tmp] = $open_hour_tmp; //時間をセット
		}

	//曜日のセット
		$week_en = $this->Businesshour->getWeekDayEn();
		$week_ja = $this->Businesshour->getWeekDayJa();

		$week_value = [];
		$week_value_tmp = [];
		for($pattern = 1; $pattern <= 3; $pattern++){
		//曜日のチェック。各曜日は$week_en[$count_week].$countで表示される				
			for($count_week = 0; $count_week <= 6; $count_week++ ){
				$week_en_add = $week_en[$count_week];
				//3つの表示分を$week_value[曜日][1から3]配列にcheckを入れる
				$week_value[$week_en_add][$pattern] = $this->ShopRegist->setOpenClose($week[$week_en_add][$pattern]);
			}
		}

		$this->set(compact('week_en','week_ja','week_value','select_time','select_time_s','select_time_e','flgDisplay','open_hour_list','default_hour','week_value_tmp'));


	//inputとselectboxのtemplate
 	$template = [
 		'label' => '<dt{{attrs}}>{{text}}</dt>',
 		'input' => '<dd><input type="{{type}}" name="{{name}}"{{attrs}}></dd>',
 		'select' => "<dd class='selectbox'><select name='{{name}}'{{attrs}}>{{content}}</select></dd>",
 	];

	$this->set(compact('template'));

	}



//登録が重複していないか、カタカタでチェック
	public function checkShopName()
	{

		if($this->request->is('post')){

			$this->writeSessionValueByPost();
			
		    $ShopTable = $this->getTableLocator()->get('shops');
		    $shoptypes = $this->getTableLocator()->get('shoptypes');
			$shopname_kana = $this->request->getdata('kana');

			//カタカナで登録済み重複チェック
			$shopDatas = $ShopTable->find()
			->where(['kana LIKE'=> '%'.$shopname_kana.'%'])
			->contain(['shoptypes'])
			->select([
				'shopname' => 'shopname',
				'shop_id' => 'shops.id',
				'shop_accountname' => 'accountname',
				'pref' => 'pref',
				'address' => 'address',
				'buolding' => 'building',
				'typename' => 'shoptypes.typename'
			])
			->all();

			//重複がなければ次のページにredirect
			if($shopDatas->isempty()){
				return $this->redirect(['action'=>'confirm']);
			}else{

			//重複があれば確認ページを表示
				$duplex_shops = array();	
	            foreach($shopDatas as $data){
					$duplex_shops[] =  $data->shopname .' ( ' . $data->typename . ' / ' .$data->pref.$data->address.$data->building . ')';
					$shop_id[] = $data->shop_id;
	           	}
				$this->set(compact('duplex_shops','shop_id'));
	        }
     	}else{
			return $this->redirect(['action'=>'index']);
     	}

	}

//確認画面
	public function confirm()
	{
	//登録後にブラウザで戻ってきた場合に、二重登録になってしまうため、リダイレクトする。
		if (!$this->session->read('shop.shopname')) {
			return $this->redirect(['controller' => 'ShopRegists', 'action' => '/']);
		}

		$this->writeSessionValueByPost();	
		$registDatas = $this->readSessionValue();

	//Postデータを営業時間・営業日・それ以外に分別して配列に格納
		list($basic_information,$bussiness_hours,$bussiness_days) = $this->ShopRegist->getPostData($registDatas);

	//shoptypeをidから名前に変換
		$ShoptypeTable = TableRegistry::get('shoptypes');
		$basic_information['shoptype'] = $ShoptypeTable->find('list')->where(['id'=>$basic_information['shoptype']])->first();
		$basic_information['shoptype2'] = $ShoptypeTable->find('list')->where(['id'=>$basic_information['shoptype2']])->first();

	//基本情報をViewにデータを送る
		foreach ($basic_information as $key => $value) {
			$this->set($key,$value);
		}

	//営業時間をセット
		$bussiness_days_hours = $this->Businesshour->setBussinessDaysHours($bussiness_hours,$bussiness_days);
		$this->set('bussiness_hours',$bussiness_days_hours);
		$this->set('week_ja',$this->Businesshour->getWeekDayJa()); //日本語曜日名の取得

	//住所からgeocodingで緯度経度を取得
		//準備
	    $PrefTable = $this->getTableLocator()->get('prefecture');
	    $pref_name = $PrefTable->find()->where(['id' => $this->readSessionValue('pref')])->first(); //都道府県名はidで受け取るため、DBから名前を取得する。(変数名は$pref_name->name)
		$address = $this->readSessionValue('address');
		$this->set('address_full',$pref_name->name.$address);

		//取得
		if($latlng = $this->ShopRegist->geocoding($pref_name->name.$address)){
			//成功した場合はセッション変数に書き込む
			$this->writeSessionValue('lat',$latlng['lat']);
			$this->writeSessionValue('lng',$latlng['lng']);
		}else{
			//Geocodingに失敗した場合、登録ページに戻す。
			return $this->redirect(['action'=>'index','?'=>['error'=>'1']]);
		}

	//java_scriptに渡す変数
		$map_default_center = $latlng['lat'] . ',' .$latlng['lng'];
		$this->set('lat',$latlng['lat']);
		$this->set('lng',$latlng['lng']);
		$gestureHandling = "gestureHandling: 'greedy'";
		$map_zoom = 18;

		$this->set(compact('map_default_center','gestureHandling','map_zoom'));   
	}


//登録処理（shop情報と営業時間は別のテーブルに保存）
	public function register(){

		if($this->request->is('post')){

			$ShopTable = TableRegistry::get('shops');
			$BusinessHoursTable = TableRegistry::get('business_hours');

			$registDatas = $this->readSessionValue();

		//Postデータを営業時間・営業日・それ以外に分別して配列に格納
			list($basic_information,$bussiness_hours,$bussiness_days) = $this->ShopRegist->getPostData($registDatas);

		//不足情報を付加
			$basic_information["status"] = 1;
			$bussiness_days_hours = $this->Businesshour->setBussinessDaysHours($bussiness_hours,$bussiness_days);

		//ショップデータの保存
	    	$shopEntity = $ShopTable->newEntity();
	    	$shopEntity = $ShopTable->patchEntity($shopEntity, $basic_information);

			//ショップ情報の登録が成功した場合、続けて営業時間の登録
	    	if($ShopTable->save($shopEntity)){	
				//営業日をDBに登録する
				foreach ($bussiness_days_hours as $day => $hour) {
					foreach ($hour as $key => $value) {
						$value['day']  = $this->Businesshour->changeNumbertoDay($day);
						$value['shop_id'] = $shopEntity->id;
			 					
					  	$weekEntity = $BusinessHoursTable->newEntity();
					  	$weekEntity = $BusinessHoursTable->patchEntity($weekEntity, $value);
					  	$BusinessHoursTable->save($weekEntity);
					}
				}
			}
		//セッション変数の削除
			$this->session->delete('shop');
		
		//登録したショップページにリダイレクト
			return $this->redirect(['controller' => 'shops', 'action' => '/' ,$shopEntity->id]);
		}else{
			return $this->redirect(['action'=>'index']);
     	}
		
	}

//Postで取得した値を、先頭にshopを付けてセッション変数に登録
	public function writeSessionValueByPost(){

		$postData = $this->request->getdata();

		foreach ($postData as $key => $value) {
			$key = 'shop.'.$key;
			if(!empty($value)){
				$this->session->write([$key => $value]);
			}else{
				$this->session->write([$key => '']);
			}
		}
	}

//仮引数で取得した値を、先頭にshopを付けてセッション変数に登録
	public function writeSessionValue($key,$value){
		$key = 'shop.'.$key;
		$this->session->write([$key => $value]);
	}


//セッション変数を取得
	public function readSessionValue($name=null){
		if(!empty($name)){
			$value = 'shop.'.$name;
		}else{
			$value = 'shop';
		}

		if(!empty($this->session->read($value))){
			return $this->session->read($value);
		}else{
			return null;
		}
	}


}


