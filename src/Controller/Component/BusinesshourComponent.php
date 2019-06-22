<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\I18n\Time;

/**
 * BussinesshourComponent
 */
class BusinesshourComponent extends Component{

	public $components = ['ShopRegist'];

	public function setBussinessDaysHours($bussiness_hours_tmp,$bussiness_days){

		// 営業時間をopen/closeの添字の配列に代入
		$bussiness_hours = [];
		for($count = 1; $count <= 3; $count++){
			$bussiness_hours[$count] = ['open' => $bussiness_hours_tmp['week'.$count.'_start'],'close' => $bussiness_hours_tmp['week'.$count.'_end']];
		}

		//営業曜日(bussiness_hours)に対して、営業時間($hours[$count])を月曜日(1)〜日曜日(7)順に配列(bussiness_hours_oder)へ代入
		//配列の初期化
		$bussiness_days_hours = [];
		for($i = 0 ; $i <= 6 ; $i++){
			$bussiness_days_hours[$i] = [];
		}

		//営業曜日と営業時間のマージ
		foreach ($bussiness_days as $day => $status) {
			//開店日のみ取り出し
			if($status === 'open'){
				array_push(
					$bussiness_days_hours[$this->changeDaytoNumber(substr($day, 6))], //ループで使えるように、曜日を数字に変換して、各曜日に営業時間([open][close])を配列で代入する。
					["open"=>$bussiness_hours[substr($day,4,-4)]["open"],	//"week#_***"の形式で来るため、#を取得（#は営業時間で取得した$bussiness_hours[$count]の$countと同じになる）
					"close"=>$bussiness_hours[substr($day,4,-4)]["close"]]
				);
			}
		}
		//各曜日毎の時間を早い順に並び替え
		$bussiness_days_hours = $this->sortHoursForEachDay($bussiness_days_hours);
		return $bussiness_days_hours;
	}



	//各曜日毎の時間を早い順に並び替え
	public function sortHoursForEachDay($hours){


		//各曜日で営業開始時間が早い順に並び替え
		for($count_week = 0 ; $count_week <= 6 ; $count_week++){
			//営業時間が複数ある場合
			if(count($hours[$count_week]) > 1){
				//各曜日に設定されている時間の取り出し
				foreach ($hours[$count_week] as $index_current => $hour) {
					//開店時間の取り出し
					$hour_compare[$index_current] = $hour['open'];
					//既出の営業時間が無くなるまで比較
					for($index_past = $index_current -1; $index_past >= 0 ; $index_past--){
						//既出の営業時間の方が開始時間が遅い場合は入れ替える
						if($hour_compare[$index_past] > $hour_compare[$index_current]){
							//入れ替え
							$change_array = [];
							$change_array = $hours[$count_week][$index_current];
							$hours[$count_week][$index_current] = $hours[$count_week][$index_past];
							$hours[$count_week][$index_past] = $change_array;
							//配列の順番を一つ前に移動したため、検索対象のindexも一つ前にずらす
							$index_current--;
						}
					}
				}
			}
		}
		return $hours;
	}


	//searchControllerとshopControllerからの呼び出し
	public function setBussinessDaysHoursFromDB($businessHoursDatas){
			//曜日配列の初期化
			$bussiness_days_hours = [];
			for($i = 0 ; $i <= 6 ; $i++){
				$bussiness_days_hours[$i] = [];
			}
			//曜日配列に営業時間を代入
			foreach ($businessHoursDatas as $businessHoursData) {
				$day = $businessHoursData->day;
				$open = Time::parse($businessHoursData->open);
				$close = Time::parse($businessHoursData->close);

				$count = count($bussiness_days_hours[$this->changeDaytoNumber($day)]);
				$bussiness_days_hours[$this->changeDaytoNumber($day)][$count]["open"] = $open->i18nFormat('HH:mm');
				$bussiness_days_hours[$this->changeDaytoNumber($day)][$count]["close"] = $close->i18nFormat('HH:mm');
			}
			//各曜日で開店時間の早い順番に並び替え
			$bussiness_days_hours = $this->sortHoursForEachDay($bussiness_days_hours);
			return $bussiness_days_hours;
	}


	//option2とeditの曜日と配列要素番号の変換
	public function changeDaytoNumber($day){
		switch ($day){
			case 'sun':
				return 0;
				break;
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
		}
	}

	//option2とeditの曜日と配列要素番号の変換
	public function changeNumbertoDay($day){
		switch ($day){
			case 0:
				return 'sun';
				break;
			case 1:
				return 'mon';
				break;
			case 2:
				return 'tue';
				break;
			case 3:
				return 'wed';
				break;
			case 4:
				return 'thu';
				break;
			case 5:
				return 'fri';
				break;
			case 6:
				return 'sat';
				break;
		}
	}

	public function getWeekDayEn(){
		return 	[0 => 'sun', 1 => 'mon',2 => 'tue',	3 => 'wed',	4 => 'thu',	5 => 'fri', 6 => 'sat'];
	}

	public function getWeekDayJa(){
		return 	[0 => '日' ,1 => '月',	2 => '火',	3 => '水',	4 => '木',	5 => '金',	6 => '土'];
	}
}


