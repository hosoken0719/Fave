<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

//新規と編集を同時に実行すると、グローバル変数を新規登録時(shop.***)が重複して不整合を起こすため、更新はshop-update.を使用して分ける
//その他、諸々エラーが発生しないように、安全のため分ける
class ShopUpdatesController extends AppController {

	public $components = ['ShopRegist','Businesshour'];

    public function initialize()
    {
      	parent::initialize();
		$this->set('title','お店情報編集 | Fave');

   		$this->session = $this->getRequest()->getSession();

    }


//編集トップページ
	public function edit(){


	//編集リンクで表示された場合（DBの値から表示）
		if($this->request->getQuery('id')){

			//グローバル変数の初期化
				$this->session->delete('shop-update');

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
				$this->session->write(['shop-update.id' => $this->request->getQuery('id')]);
				$this->session->write(['shop-update.shopname' => $shopDatas->shopname]);

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
					$businessHours_from_db[$pattern] = ['open'=>'','close'=>'','day'=>[]];
				}
			//取り出したデータを取り出した順番に配列に代入。後から曜日毎に整頓する。
				foreach ($businessHoursDatas as $businessHoursData) {
					$day = $businessHoursData->day;
					$open = Time::parse($businessHoursData->open);
					$close = Time::parse($businessHoursData->close);

				//各配列のチェック（開始終了時間があり、DBの値と不一致の場合、次の配列をチェック。ある場合は曜日フラグを立てる）
					for($pattern=1; $pattern<=3; $pattern++){
					//開始終了時間がない場合、時間の代入と曜日フラグを立てる
						if(empty($businessHours_from_db[$pattern]['open'])){					
							$businessHours_from_db[$pattern]['open'] = $open->i18nFormat('HH:mm');
							$businessHours_from_db[$pattern]['close'] = $close->i18nFormat('HH:mm');
							$businessHours_from_db[$pattern]['day'][$this->Businesshour->changeDaytoNumber($day)] = 1;
							break;
						}
					//開始終了時間があり、DBの値と一致する場合、曜日フラグを立てる
						elseif($businessHours_from_db[$pattern]['open'] === $open->i18nFormat('HH:mm') and $businessHours_from_db[$pattern]['close'] === $close->i18nFormat('HH:mm')){
							$businessHours_from_db[$pattern]['day'][$this->Businesshour->changeDaytoNumber($day)] = 1;
							break;
						}
					}

				}

			//営業時間の3パターンをループして、曜日毎に配列にセットする。
				$week_en = $this->Businesshour->getWeekDayEn();
				$week_ja = $this->Businesshour->getWeekDayJa();
				for($pattern=1; $pattern<=3; $pattern++){
					//曜日のセットsetOption2ForView
					$flgDisplay[$pattern] = 0;
					for($count_week = 0; $count_week <= 6; $count_week++ ){
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

		        

				// list($week_value ,$select_time , $select_time_s , $select_time_e) = $this->ShopRegist->setOption2ForView($week,$hour);
	//戻るボタンで表示する場合（Postデータの値を表示）
		}else{
			//営業時間以外をViewに引き渡し
				$this->set('shopname',$this->readSessionValue('shopname'));
				$this->set('tel',$this->readSessionValue('tel'));
				$this->set('parking',$this->readSessionValue('parking'));
				$this->set('homepage',$this->readSessionValue('homepage'));
				$this->set('introduction',$this->readSessionValue('introduction'));

			//3パターンの営業時間に対して、他画面から来た場合に、既にチェックされていれば選択済みで表示する
			//営業時間を格納
			//曜日の初期値設定
				$week_en = $this->Businesshour->getWeekDayEn();
				$week_ja = $this->Businesshour->getWeekDayJa();
					for($pattern = 1; $pattern <= 3; $pattern++){
					//曜日のセット
						$flgDisplay[$pattern] = 0;
						for($count_week = 1; $count_week <= 7; $count_week++ ){
							$week_en_add = $week_en[$count_week]; //後ろの行を見やすくするために代入
							if($this->readSessionValue('week'.$pattern.'_'.$week_en_add) === 'open'){
								$week[$week_en_add][$pattern] = 'open';
								$flgDisplay[$pattern] = 1;//2つ目、3つ目の営業時間を表示するか判定
							}
							else{
								$week[$week_en_add][$pattern] = 'close';
							}
						}

					//営業時間のセット
						$hour['open'][$pattern] = $this->readSessionValue("week".$pattern."_start");
						$hour['close'][$pattern] = $this->readSessionValue("week".$pattern."_end");

					}

				// list($week_value ,$select_time , $select_time_s , $select_time_e) = $this->ShopRegist->setOption2ForView($week,$hour);

		}
		//Viewで表示出来るように整える
		list($week_value ,$select_time , $select_time_s , $select_time_e) = $this->ShopRegist->setOption2ForView($week,$hour);
		$this->set(compact('week_en','week_ja','week_value','select_time','select_time_s','select_time_e','flgDisplay'));
	}


	public function confirm(){
		$this->writeSessionValueByPost();	
		$registDatas = $this->readSessionValue();

		//営業時間以外はsession変数の値を引き渡し
		// week**は以下の形式となるため、営業曜日と営業時間を分けて保存する
		// ["week_mon1"]=>string(4) "open"
		// ["week_tue1"]=>string(4) "close" ..
		// ["week1_s"]=>string(5) "11:00"
		// ["week1_e"]=>string(5) "19:00"

		//Postデータを営業時間・営業日・それ以外に分別して配列に格納
		list($basic_information,$bussiness_hours,$bussiness_days) = $this->ShopRegist->getPostData($registDatas);

		foreach ($basic_information as $key => $value) {
			$this->set($key,$value);
		}

		$bussiness_days_hours = $this->Businesshour->setBussinessDaysHours($bussiness_hours,$bussiness_days);

		$this->set('bussiness_hours',$bussiness_days_hours);
		$this->set('week_ja',$this->Businesshour->getWeekDayJa()); //日本語曜日名の取得

	}


	public function register(){

		$ShopTable = TableRegistry::get('shops');
		$BusinessHoursTable = TableRegistry::get('business_hours');

		$registDatas = $this->readSessionValue();

		//Postデータを営業時間・営業日・それ以外に分別して配列に格納
		list($basic_information,$bussiness_hours_tmp,$bussiness_days) = $this->ShopRegist->getPostData($registDatas);

		//営業時間は3つみ登録可能	
		$bussiness_hours = [];
		for($count = 1; $count <= 3; $count++){
			$bussiness_hours[$count] = ['open' => $bussiness_hours_tmp['week'.$count.'_start'],'close' => $bussiness_hours_tmp['week'.$count.'_end']];
		}

		// //ショップデータの保存
    	$shopEntity = $ShopTable->newEntity();
    	$shopEntity = $ShopTable->patchEntity($shopEntity, $basic_information);

		// //ショップ情報の登録が成功した場合、続けて営業時間の登録
    	if($ShopTable->save($shopEntity)){

    		//DBの既存の営業日時を全て削除する。
			$BusinessHoursTable->deleteALL(['shop_id' => $shopEntity->id]);

		 	//営業日をDBに登録する
			foreach ($bussiness_days as $day => $status) {
				if($status === 'open'){
					$registData = [];
		 			//"week#_***"の形式で来るため、文字を分割して登録
					$week = substr($day, 4,-4);
					$registData = array_merge($registData,$bussiness_hours[$week],['day'=>substr($day, 6),'shop_id' => $shopEntity->id]);
				  	$weekEntity = $BusinessHoursTable->newEntity();
				  	$weekEntity = $BusinessHoursTable->patchEntity($weekEntity, $registData);
					$BusinessHoursTable->save($weekEntity);
				}
			}
		}

		// 	//登録したショップページにリダイレクト
		return $this->redirect(['controller' => 'shops', 'action' => '/' ,$shopEntity->id]);

	}



	public function writeSessionValueByPost(){

		$postData = $this->request->getdata();

		foreach ($postData as $key => $value) {
			$key = 'shop-update.'.$key;
			if(!empty($value)){
				$this->session->write([$key => $value]);
			}else{
				$this->session->write([$key => '']);
			}
		}
	}


	public function readSessionValue($name=null){
		if(!empty($name)){
			$value = 'shop-update.'.$name;
		}else{
			$value = 'shop-update';
		}

		if(!empty($this->session->read($value))){
			return $this->session->read($value);
		}else{
			return null;
		}
	}





}
