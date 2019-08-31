<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\TableRegistry;

/**
 * ShopComponent component
 */
class ShopCompComponent extends Component
{

  public function getShopData($shop_id,$type='first'){
        $ShopTable = TableRegistry::getTableLocator()->get('shops');
	//ショップ情報の抽出
    	$shopData = $ShopTable->find()
    	//非公開時の処理を追加すること！！！
    	->where([
    		'shops.id' => $shop_id,
			'shops.status' => '1'
		])
		->contain(['shoptypes','prefectures'])
		->select([
			'shopname' => 'shopname',
			'user_id' => 'shops.user_id',
			'shop_id' => 'shops.id',
			'shop_kana' => 'shops.kana',
			'shoptype' => 'shops.shoptype',
			'shoptype2' => 'shops.shoptype2',
			'shop_accountname' => 'shops.accountname',
			'shop_business_hour_detail' => 'shops.business_hour_detail',
			'shop_homepage' => 'shops.homepage',
			'introduction' => 'shops.introduction',
			'pref' => 'shops.pref',
			'address' => 'address',
			'buolding' => 'building',
			'parking' => 'parking',
			'phone_number' => 'phone_number',
			'homepage' => 'homepage',
			// 'tag' => 'Users.tag',
			'lat' => 'shops.lat',
			'lng' => 'shops.lng',
			'typename' => 'shoptypes.typename',
			'confirm' => 'shops.confirm',
			'instagram' => 'shops.instagram',
			'thumbnail' => 'shops.thumbnail',
		]);
		if($type === 'array'){
			$shopData = $shopData->toArray();
		}else{
			$shopData = $shopData->first();
		}

		return $shopData;
  }


	public function getShopPhotos($shop_id){
	    $ShopPhotoTable = TableRegistry::getTableLocator()->get('shop_photos');
	    $shop_photos = $ShopPhotoTable->find()->where(['shop_id'=>$shop_id]);
	    return $shop_photos;
	}

}