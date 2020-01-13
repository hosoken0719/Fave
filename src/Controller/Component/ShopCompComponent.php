<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\TableRegistry;
use Cake\Database\Expression\IdentifierExpression;
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
		->contain(['shoptypes','shoptypes2','prefectures'])
		->select([
			'shopname' => 'shopname',
			'user_id' => 'shops.user_id',
			'shop_id' => 'shops.id',
			'shop_kana' => 'shops.kana',
			'shoptype' => 'shops.shoptype',
			'shoptype2' => 'shops.shoptype2',
			'branch' => 'shops.branch',
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


	public function getShopPhotos($shop_id,$limit=0){

	    $ShopPhotoTable = TableRegistry::getTableLocator()->get('shop_photos');
	    $shop_photos = $ShopPhotoTable->find()->where(['shop_id'=>$shop_id])->order(['created'=>'DESC']);
	    if($limit>0){
	    	$shop_photos = $shop_photos->limit($limit);
	    }
	    return $shop_photos;
	}

	public function getShopPhotoDir($shop_id){
		return '/img/shop_photos/'.$shop_id;
	}

	public function setThumbnail($shop_id,$file_name){

	}

	public function getThumbnail($shop_id){
	    $ShopTable = TableRegistry::getTableLocator()->get('shops');
	    $query = $ShopTable->find()
	    ->where(['id'=>$shop_id])
	    ->select(['file_name' => 'thumbnail'])
	    ->first();

		return $query->file_name;
	}

	public function hasThumbnail($shop_id){
		return $result;
	}

    // public function rating_avg($shop_id,$follower_user=null){

    //     $FollowsTable = TableRegistry::get('follows');

    //     $query = $FollowsTable->find()->where(['follower_shop'=>$shop_id]);
    //     if($follower_user<>null){
    //     	$query = $query->where(['follow IN'=>$follower_user]);
    //     }
    //     $result = $query->select(['avg' => $query->func()->avg('rating')])->first();
    //     if($result->avg > 0){
    //     	return round($result->avg,1);
    //     }else{
    //     	return 0;
    //     }

    // }

    public function rating_avg($query,$follower_user){
		$avgFolloweCase = $query->newExpr()->addCase(
		    $query->newExpr()->add(['follows.follow IN'=>$follower_user]),
		    $query->newExpr(new IdentifierExpression('follows.rating')), //'follows.rating'だとstring型になってしまう。構文の意味は不明
		    'integer'
		);

		$cntFolloweCase = $query->newExpr()->addCase(
		    $query->newExpr()->add(['follows.follow IN'=>$follower_user]),
		    1,
		    'integer'
		);

 	    // $query = $query->group(['shops.id']);
		$query = $query->select([
		'avg_followed' => $query->func()->avg($avgFolloweCase), //フォロー平均レート
		'cnt_followed' => $query->func()->count($cntFolloweCase), //フォロー人数
		'avg_all' => $query->func()->avg('follows.rating'), //全員平均レート
		'cnt_all' => $query->func()->count('follows.rating') //全員人数
		]);

		return $query;
	}


    public function getAvgFollowed($query,$follower_user){
		$avgFolloweCase = $query->newExpr()->addCase(
		    $query->newExpr()->add(['sub_follows.follow IN'=>$follower_user]),
		    $query->newExpr(new IdentifierExpression('sub_follows.rating')), //'follows.rating'だとstring型になってしまう。構文の意味は不明
		    'integer'
		);
		$query = $query->select([
		'avg_followed' => $query->func()->avg($avgFolloweCase), //フォロー平均レート
		]);
		// ->group(['follows.follower_shop']);
		return $query;
	}

    public function getCntFollowed($query,$follower_user){
		$avgFolloweCase = $query->newExpr()->addCase(
		    $query->newExpr()->add(['sub_follows.follow IN'=>$follower_user]),
		    $query->newExpr(new IdentifierExpression('sub_follows.rating')), //'follows.rating'だとstring型になってしまう。構文の意味は不明
		    'integer'
		);

		$cntFolloweCase = $query->newExpr()->addCase(
		    $query->newExpr()->add(['sub_follows.follow IN'=>$follower_user]),
		    1,
		    'integer'
		);

 	    // $query = $query->group(['shops.id']);
		$query = $query->select([
		// 'avg_followed' => $query->func()->avg($avgFolloweCase), //フォロー平均レート
		'cnt_followed' => $query->func()->count($cntFolloweCase), //フォロー人数
		// 'avg_all' => $query->func()->avg('follows.rating'), //全員平均レート
		// 'cnt_all' => $query->func()->count('follows.rating') //全員人数
		]);
		return $query;
	}


}