<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class RegistsController extends AppController {

	    public $components = array(
        'ImgProcess' => array(),
    );


    public function initialize()
    {
      	parent::initialize();
		$this->set('title','投稿 | Fave');

   		$this->session = $this->getRequest()->getSession();

    }


	public function index(){
 
	    $ShopTable = $this->getTableLocator()->get('shops');
	    $shoptypes = $this->getTableLocator()->get('shoptypes');

	    $shopname = '';
	    $kana = '';
	    if($this->request->is('post')){
	    	$shopname = $this->session->read('shop.shopname');
	    	$kana = $this->session->read('shop.kana');

		}else{
			$this->session->delete('shop');
		}

		$this->set(compact('shopname'));
		$this->set(compact('kana'));
	}


	public function checkShopName()
	{

		if($this->request->is('post')){

			$this->writeSessionValueByPost();
			
		    $ShopTable = $this->getTableLocator()->get('shops');
		    $shoptypes = $this->getTableLocator()->get('shoptypes');

			$shopname_kana = $this->request->getdata('kana');

			$shopDatas = $ShopTable->find()
			->where(['kana LIKE'=> '%'.$shopname_kana.'%'])
			->join([
				'table' => 'shoptypes',
				'alias' => 'shoptypes',
				'type' => 'LEFT',
				'conditions'  => 'shoptype = shoptypes.id',
			])
			->select([
				'shopname' => 'shopname',
				'shop_accountname' => 'accountname',
				'pref' => 'pref',
				'city' => 'city',
				'ward' => 'ward',
				'town' => 'town',
				'buolding' => 'building',
				'typename' => 'shoptypes.typename'

			])
			->all();

			//重複がなければ次のページにredirect
			if($shopDatas->isempty()){
				return $this->redirect(['action'=>'option1']);
			}else{

			//重複があれば確認ページを表示
				$duplex_shops = array();	
	            foreach($shopDatas as $data){
					array_push($duplex_shops,
	                $data->shopname .' ( ' . $data->typename . ' / ' .$data->pref.$data->city.$data->ward.$data->town.$data->building . ')');
	           	}
				$this->set(compact('duplex_shops'));
	        }
     	}else{
			return $this->redirect(['action'=>'index']);
     	}

	}


	private function checkValueNull($value)
	{
		if(!empty($value)){
			return $value;
		}else{
			return null;
		}
	}

	public function option1()
	{

		$ShoptypeTable = TableRegistry::get('shoptypes');
		//*ショップタイプの取得
		$this->set('typename',$ShoptypeTable->find('list'));

		$shoptype = $this->readSessionValue('shoptype');
		$address = $this->readSessionValue('address');
		$building = $this->readSessionValue('building');

		$this->set(compact('shoptype','address','building'));

	}


	public function addresscheck()
	{

		//前のページから来た場合
		// if(empty($this->readSessionValue('pref'))){

			//既にsession関数に保存されていれば初期値として代入
			$pref = $this->readSessionValue('pref');
			$city = $this->readSessionValue('city');
			$town = $this->readSessionValue('town');
			$building = $this->readSessionValue('building');

			//Postデータの読み込み
			$this->writeSessionValueByPost();

			$address = $this->readSessionValue('address');

			//文字コードがUTF8のため.{2,3}県ではなく、.{6,9}県。
			preg_match('/(東京都|北海道|(?:京都|大阪)府|.{6,9}県)/', $address, $pref);
			$address = str_replace($pref, '', $address); //$addressから県名を削除

			preg_match('/((?:四日市|廿日市|野々市|かすみがうら|つくばみらい|いちき串木野)市|(?:杵島郡大町|余市郡余市|高市郡高取)町|.{3,12}市.{3,12}区|.{3,9}区|.{3,15}市(?=.*市)|.{3,15}市|.{6,27}郡(?=.*郡)|.{6,27}郡|.{6,27}町(?=.*町)|.{6,27}町|.{9,24}村(?=.*村)|.{9,24}村)/', $address, $city);
				// original
				// preg_match('/(東京都|北海道|(?:京都|大阪)府|.{6,9}県)((?:四日市|廿日市|野々市|かすみがうら|つくばみらい|いちき串木野)市|(?:杵島郡大町|余市郡余市|高市郡高取)町|.{3,12}市.{3,12}区|.{3,9}区|.{3,15}市(?=.*市)|.{3,15}市|.{6,27}郡(?=.*郡)|.{6,27}郡|.{6,27}町(?=.*町)|.{6,27}町|.{9,24}村(?=.*村)|.{9,24}村)(.*)/', $address, $city);

			$address = str_replace($city, '', $address); //$addressから市町村名を削除

			
			if(!empty($pref)){
				$pref = $pref[1];
			}
			if(!empty($city)){
				$city = $city[1];
			}
			$town = $this->checkValueNull($address); //県市町村名以外を町村・番地として$cityに代入
			$building = $this->checkValueNull($this->readSessionValue('building'));

		//戻るボタンで来た場合
		// }else{
		// 	$pref = $this->checkValueNull($this->readSessionValue('pref'));
		// 	$city = $this->checkValueNull($this->readSessionValue('city'));
		// 	$town = $this->checkValueNull($this->readSessionValue('town'));
		// 	$building = $this->checkValueNull($this->readSessionValue('building'));

		// }

		$this->set('address',$this->readSessionValue('address'));
		$this->set(compact('pref','city','town','building'));
		
	}



	public function mapcheck()
	{

		$this->writeSessionValueByPost();

		//geocoding用
		$pref = $this->readSessionValue('pref');
		$city = $this->readSessionValue('city');
		$town = $this->readSessionValue('town');
		// $building = $this->readSessionValue('building');

		//addresscheckで修正するの可能性があるため、改めて上書き
		$this->session->write(['shop.address' => $pref.$city.$town]);


		if(is_null($this->readSessionValue('lat'))){
			if(!$latlng = $this->geocoding($pref.$city.$town)){
				//Geocodingに失敗した場合、住所入力に戻す。
				return $this->redirect(['action'=>'option1','?'=>['error'=>'1']]);
			}
		}else{
			$latlng = [
				'lat' => $this->readSessionValue('lat'),
				'lng' => $this->readSessionValue('lng')
			];
		}

		$map_default_center = $latlng['lat'] . ',' .$latlng['lng'];

		$this->set('lat',$latlng['lat']);
		$this->set('lng',$latlng['lng']);

		$gestureHandling = "gestureHandling: 'greedy'";
		$map_zoom = 18;

		// $map_default_center = null;
		$this->set(compact('map_default_center','gestureHandling','map_zoom'));

	    
	}


//営業時間などの基本情報
	public function option2()
	{


//shop画面から編集リンクで来た場合は、引数にshop_idがつく
	//新規登録時（戻るボタンからの表示も含む）
		//latlngの保存
			$this->writeSessionValueByPost();
		
		//営業時間以外をViewに引き渡し
			$this->set('shopname',$this->readSessionValue('shopname'));
			$this->set('tel',$this->readSessionValue('tel'));
			$this->set('parking',$this->readSessionValue('parking'));
			$this->set('homepage',$this->readSessionValue('homepage'));
			$this->set('introduction',$this->readSessionValue('introduction'));

		//3パターンの営業時間に対して、他画面から来た場合に、既にチェックされていれば選択済みで表示する
		//営業時間を格納
	//曜日の初期値設定
		list($week_en,$week_ja) = $this->setWeekDay();
			for($pattern = 1; $pattern <= 3; $pattern++){
			//曜日のセット
				$flgDisplay[$pattern] = 0;
				for($count_week = 1; $count_week <= 7; $count_week++ ){
					$week_en_add = $week_en[$count_week]; //後ろの行を見やすくするために代入
					if($this->readSessionValue('week_'.$week_en_add.$pattern) === 'open'){
						$week[$week_en_add][$pattern] = 'open';
						$flgDisplay[$pattern] = 1;//2つ目、3つ目の営業時間を表示するか判定
					}
					else{
						$week[$week_en_add][$pattern] = 'close';
					}
				}

			//営業時間のセット
				$hour['open'][$pattern] = $this->readSessionValue("week".$pattern."_s");
				$hour['close'][$pattern] = $this->readSessionValue("week".$pattern."_e");

			}

		$this->setOption2ForView($week,$hour);
		$this->set(compact('week_en','week_ja','flgDisplay'));
	}



//登録済みの情報を更新（Option2の画面）
	public function edit(){

	//グローバル変数の初期化
	$this->session->delete('shop');

	//▽▼shopテーブルから営業時間以外の登録データを取得▽▼
    	$ShopTable = TableRegistry::get('shops');
		$shopDatas = $ShopTable->Find()
		->where(['shops.id' => $this->request->getQuery('id')])
		->select([
			'shopname' => 'shopname',
			'shoptype' => 'shoptype',
			'pref' => 'pref',
			'city' => 'city',
			'town' => 'town',
			'building' => 'building',
			'tel' => 'tel',
			'parking' => 'parking',
			'homepage' => 'homepage',
			'introduction' => 'introduction'
		])
		->first();
		$this->session->write(['shop.id' => $this->request->getQuery('id')]);
		$this->session->write(['shop.shopname' => $shopDatas->shopname]);

	//営業時間以外をViewに引き渡し
		$this->set('shopname',$shopDatas->shopname);
		$this->set('tel',$shopDatas->tel);
		$this->set('parking',$shopDatas->parking);
		$this->set('homepage',$shopDatas->homepage);
		$this->set('introduction',$shopDatas->introduction);


//▽▼営業時間はbusiness_hoursから取得▽▼
    	$BusinessHoursTable = TableRegistry::get('business_hours');
		$businessHoursDatas = $BusinessHoursTable->Find()
		->where(['business_hours.shop_id' => $this->request->getQuery('id')]);

	//配列の初期化（3パターンまで） → array(3) {[1]=>{["open"]=>"**:**",["close"]=>"**:**",["day"]=>{[1 - 7]=> 0 or 1 ..},[2],[3]}
		for($pattern=1; $pattern<=3; $pattern++){
			$businessHours_from_db[$pattern] = array('open'=>'','close'=>'','day'=>array());
		}
		//取り出したデータを配列に代入。
		foreach ($businessHoursDatas as $businessHoursData) {
			$day = $businessHoursData->day;
			$open = Time::parse($businessHoursData->open);
			$close = Time::parse($businessHoursData->close);

		//各配列のチェック（開始終了時間があり、DBの値と不一致の場合、次の配列をチェック）
			for($pattern=1; $pattern<=3; $pattern++){
			//開始終了時間がない場合、時間の代入と曜日フラグを立てる
				if(empty($businessHours_from_db[$pattern]['open'])){					
					$businessHours_from_db[$pattern]['open'] = $open->i18nFormat('HH:mm');
					$businessHours_from_db[$pattern]['close'] = $close->i18nFormat('HH:mm');
					$businessHours_from_db[$pattern]['day'][$this->changeDaytoNumber($day)] = 1;
					break;
				}
			//開始終了時間があり、DBの値と一致する場合、曜日フラグを立てる
				elseif($businessHours_from_db[$pattern]['open'] === $open->i18nFormat('HH:mm') and $businessHours_from_db[$pattern]['close'] === $close->i18nFormat('HH:mm')){
					$businessHours_from_db[$pattern]['day'][$this->changeDaytoNumber($day)] = 1;
					break;
				}
			}

		}

	//営業時間の3パターンをループ
		list($week_en,$week_ja) = $this->setWeekDay();
		for($pattern=1; $pattern<=3; $pattern++){
			//曜日のセット
			$flgDisplay[$pattern] = 0;
			for($count_week = 1; $count_week <= 7; $count_week++ ){
				$week_en_add = $week_en[$count_week];
				if(!empty($businessHours_from_db[$pattern]['day'][$count_week])){
					$week[$week_en_add][$pattern] = 'open';
					$flgDisplay[$pattern] = 1;
				}
				else{
					$week[$week_en_add][$pattern] = 'close';
				}
			}
			//営業時間のセット
			$hour['open'][$pattern] = $businessHours_from_db[$pattern]['open'];
			$hour['close'][$pattern] = $businessHours_from_db[$pattern]['close'];
		}

        
	//Viewで表示出来るように整える
		$this->setOption2ForView($week,$hour);
		$this->set(compact('week_en','week_ja','flgDisplay'));
		$this->render('/Regists/option2');
	}


//確認画面
	public function confirm()
	{

		$this->writeSessionValueByPost();	
		$registDatas = $this->readSessionValue();

		foreach ($registDatas as $key => $value) {
			$this->set($key,$value);
			echo $key . '--' . $value . '||||';
		}

	}

//登録処理（shop情報を営業時間を別のテーブルに保存）
	public function register(){

		$ShopTable = TableRegistry::get('shops');
		$BusinessHoursTable = TableRegistry::get('business_hours');

		$registDatas = $this->readSessionValue();

		//登録データをテーブル別に配列に格納
		$shopdata = [];
		$bussiness_hours = [];

		foreach ($registDatas as $key => $value) {
			//営業時間以外
			if(strpos($key,'week') === false) {
				$shopdata = array_merge($shopdata,[$key => $value]);
			}else{
			//営業時間は別のテーブルに保存
				$bussiness_hours = array_merge($bussiness_hours,[$key =>$value]);
			}
		}

		//不足情報を付加
		$shopdata = array_merge($shopdata,['status' => 1]);

		//ショップデータの保存
    	$shopEntity = $ShopTable->newEntity();
    	$shopEntity = $ShopTable->patchEntity($shopEntity, $shopdata);
    	$ShopTable->save($shopEntity);

		//ショップ情報の登録が成功した場合、続けて営業時間の登録
    	if($ShopTable->save($shopEntity)){
			
			//営業時間は3つみ登録可能	
			$hours = [];
			for($count = 1; $count <= 3; $count++){
				//営業時間の取得
				$hours[$count] = ['open' => $bussiness_hours['week'.$count.'_s'],'close' => $bussiness_hours['week'.$count.'_e']];

				//時間要素の削除
				unset($bussiness_hours['week'.$count.'_s']);
				unset($bussiness_hours['week'.$count.'_e']);

			}

			//営業日をDBに登録する
			foreach ($bussiness_hours as $day => $status) {
				if($status === 'open'){
					$registData = [];
					//"week_***#"の形式で来るため、無理やりだけど文字を分割して登録
					$week = substr($day, -1);
					$registData = array_merge($registData,$hours[$week],['day'=>substr($day, -4, 3),'shop_id' => $shopEntity->id]);
				  	$weekEntity = $BusinessHoursTable->newEntity();
				  	$weekEntity = $BusinessHoursTable->patchEntity($weekEntity, $registData);
				  	$BusinessHoursTable->save($weekEntity);
					
				}
			}
			//登録したショップページにリダイレクト
			return $this->redirect(['controller' => 'shops', 'action' => '/' ,$shopEntity->id]);
		}
	}






	private function writeSessionValueByPost(){

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


	private function readSessionValue($name=null){
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

	private function geocoding($address){
		if($address != null){
			$url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $address . "+CA&key=".GOOGLE_GEOCODING_KEY;  // 対象のURL
			$ch = curl_init(); // 初期化
			curl_setopt( $ch, CURLOPT_URL, $url );// URLの設定
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true ); // 出力内容を受け取る設定
			$result = curl_exec( $ch ); // データの取得
			$jsonData = json_decode($result,true);
			curl_close($ch); // cURLのクローズ

			//Geocodingに失敗するとstatusがZERO_RESULTSになるため、falseを返す
			if($jsonData["status"] !== "ZERO_RESULTS"){
				$latlng = ['lat'=>$jsonData["results"][0]["geometry"]["location"]["lat"],'lng'=>$jsonData["results"][0]["geometry"]["location"]["lng"]];
				return $latlng;
			}else{
				return false;
			}
		}
	}


	private function setOpenClose($value)
	{	
		if($value === 'open'){
			return 'checked';
		}elseif($value === 'close'){
			return null;
		}
	}

	//営業時間を作成。既に入力されていればselectedを付けて戻す
	private function setOpenHours($i,$time){
		if($i!== $time){
			return "<option value='".$time."'>".$time."</option>";
		}else{
			return "<option value='".$time."' selected>".$time."</option>";
		}
	}

	//option2とeditのデータをView用に加工
	private function setOption2ForView($week,$hour){

	//曜日の初期値設定
		list($week_en,$week_ja) = $this->setWeekDay();

		$week_value = [];
		for($pattern = 1; $pattern <= 3; $pattern++){
		//曜日のチェック。各曜日は$week_en[$count_week].$countで表示される				
			for($count_week = 1; $count_week <= 7; $count_week++ ){
				$week_en_add = $week_en[$count_week];
				//3つの表示分を$week_value[曜日][1から3]配列にcheckを入れる
				$week_value[$week_en_add][$pattern] = $this->setOpenClose($week[$week_en_add][$pattern]);
			}

		//営業時間の設定。00:00〜24:00の<option>タグを$select_time_*変数に設定する。既に設定されていればselectedを付加するために、setOpenHours関数を呼び出し。
			$select_time_s[$pattern] = [];
			$select_time_e[$pattern] = [];
			//終了時間が開始時間よりも早い場合は、終了時間を00:00とする
			if($hour['open'][$pattern] >= $hour['close'][$pattern]){
				$hour['close'][$pattern] = '00:00';
			}
			//23:30までをループで作成。
			for ($select_time = 0; $select_time <= 47; $select_time++) {
				$time = date("H:i", strtotime("00:00 +". $select_time * 30 ." minute")); //時間をセット
				array_push($select_time_s[$pattern] ,$this->setOpenHours($hour['open'][$pattern] ,$time)); //時間が一致する場合はタグにselectedを付加する
				array_push($select_time_e[$pattern] ,$this->setOpenHours($hour['close'][$pattern] ,$time));
			}
		}

		$this->set(compact('week_en','week_ja','week_value','select_time','select_time_s','select_time_e'));
	}

	//option2とeditの曜日をセット
	private function setWeekDay(){
		return [
			[1 => 'mon',2 => 'tue',	3 => 'wed',	4 => 'thu',	5 => 'fri', 6 => 'sat', 7 => 'sun'],
			[1 => '月',	2 => '火',	3 => '水',	4 => '木',	5 => '金',	6 => '土',	7 => '日']
		];
	}

	//option2とeditの曜日と配列要素番号の変換
	private function changeDaytoNumber($day){
		switch ($day){
			case 'mon':
				return 1;
				break;
			case 'tue':
				return 2;
				break;
			case 'wed':
				return 3;
				break;
			case 'thu':
				return 4;
				break;
			case 'fri':
				return 5;
				break;
			case 'sat':
				return 6;
				break;
			case 'sun':
				return 7;
				break;
		}
	}


}


