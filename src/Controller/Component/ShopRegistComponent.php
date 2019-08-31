<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;

/**
 * ShopRegistsComponent component
 */
class ShopRegistComponent extends Component
{

	public $components = ['Businesshour'];


	public function geocoding($address){
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


	public function setOpenClose($value)
	{
		if($value === 'open'){
			return 'checked';
		}elseif($value === 'close'){
			return null;
		}
	}

	//営業時間を作成。既に入力されていればselectedを付けて戻す
	public function setOpenHours($i,$time){
		if($i!== $time){
			return "<option value='".$time."'>".$time."</option>";
		}else{
			return "<option value='".$time."' selected>".$time."</option>";
		}
	}

	//option2とeditのデータをView用に加工
	public function setOption2ForView($week,$hour){

	//曜日の初期値設定
		$week_en = $this->Businesshour->getWeekDayEn();
		$week_ja = $this->Businesshour->getWeekDayJa();

		$week_value = [];
		for($pattern = 1; $pattern <= 3; $pattern++){
		//曜日のチェック。各曜日は$week_en[$count_week].$countで表示される
			for($count_week = 0; $count_week <= 6; $count_week++ ){
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

		return [$week_value,$select_time,$select_time_s,$select_time_e];

	}


	public function setOption(){
		
		//新規登録時（戻るボタンからの表示も含む）
		
		//3パターンの営業時間に対して、他画面から来た場合に、既にチェックされていれば選択済みで表示する
		//営業時間を格納
	//曜日の初期値設定
		list($week_en,$week_ja) = $this->getWeekDay();
			for($pattern = 1; $pattern <= 3; $pattern++){
			//曜日のセット
				$flgDisplay[$pattern] = 0;
				for($count_week = 0; $count_week <= 6; $count_week++ ){
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

		list($week_value ,$select_time , $select_time_s , $select_time_e) = $this->setOption2ForView($week,$hour);


		return[$week_e,$week_ja,$week_value,$select_time,$select_time_s,$select_time_e,$flgDisplay];
		// return['week_en'=>$week_en,'week_ja'=>$week_ja,'week_value'=>$week_value,'select_time'=>$select_time,'select_time_s'=>$select_time_s,'select_time_e'=>$select_time_e,'flgDisplay'=>$flgDisplay];
	}

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

	//Postデータを営業時間・営業日・それ以外に分別して配列に格納
	public function getPostData($registDatas){
		$basic_information = [];
		$bussiness_hours_tmp = [];
		$bussiness_days = [];
		foreach ($registDatas as $key => $value) {
			if(strpos($key,'week') === false) {
				$basic_information[$key] = $value;
			//営業時間の場合
			}elseif(strpos($key,'start') or strpos($key,'end') ){
				$bussiness_hours_tmp = array_merge($bussiness_hours_tmp,[$key =>$value]);
			//営業日の場合
			}else{
				$bussiness_days = array_merge($bussiness_days,[$key =>$value]);
			}
		}

		return [$basic_information,$bussiness_hours_tmp,$bussiness_days];

	}

//registとupdateのconfirmより呼び出し


}