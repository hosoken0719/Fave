<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * User Entity
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property int $role
 * @property \Cake\I18n\FrozenTime $last_login_at
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Shop[] $shops
 * @property \App\Model\Entity\Tag[] $tags
 */
class Follow extends Entity
{
    protected $_accessible = [
        'shop' => true,
        'follower' => true,
        'created' => true,
    ];

    public function getPhotoShopThumbnail($id){
	    $dir = SHOPPHOTO_UPLOADDIR . '/photo_shop/' . $id . '/thumbnail/';
	    $photo_list = glob($dir . '*');
	    $photoShop = "";
	    if(!empty($photo_list)){
	        $photoShop_fullpath = max($photo_list); //最新写真のみ抽出
	        $photoShop_array = explode('/',$photoShop_fullpath); //サーバパスの取得となるため、最後のファイル名だけを取得
	        $photoShop = "https://fave-jp.info/img/photo_shop/" . $id . "/thumbnail/" . end($photoShop_array);
	    }else{
	        $photoShop = "https://fave-jp.info/img/no_image.png";
	    }
	    return $photoShop;
    }

    //Viewから変数に代入される
    public $LoginUserId;
	public $FollowerId;
    public $FollowerShopId;
	public $LoginUserFollow; //連想配列

	//フォローショップ数のカウント
    protected function _getFollowShopCount(){
        $this->setTables(); //テーブルのセット
 		$follow_shop_count = $this->TableEntity['follows']->find()->where(['follow' => $this->FollowerId])->count();
    	return $follow_shop_count;
    }
    //ログインユーザと共通のフォローショップ数のカウント
    protected function _getFollowShopCommonCount(){
        $this->setTables();
    	$follow_shop_common_count = $this->TableEntity['follows']->find()->where(['follow' => $this->FollowerId,'follower_shop IN' => $this->LoginUserFollow['follower_shop']])->count();
    	return $follow_shop_common_count;
    }

    //フォローユーザ数のカウント
    protected function _getFollowUserCount(){
        $this->setTables();
     	$follow_user_count = $this->TableEntity['follow_users']->find()->where(['follow' => $this->FollowerId])->count();
     	return $follow_user_count;
    }

    //ログインユーザと共通のフォローユーザー数のカウント
    protected function _getFollowUserCommonCount(){
        $this->setTables();
    	$follow_user_common_count = $this->TableEntity['follow_users']->find()->where(['follow' => $this->FollowerId,'follower_user IN' => $this->LoginUserFollow['follower_user']])->count();
     	return $follow_user_common_count;
    }

    //フォロワー数のカウント
    protected function _getFollowerUserCount(){
        $this->setTables();
	    $follower_count = $this->TableEntity['follow_users']->find()->where(['follower_user' => $this->FollowerId])->count();
	    return $follower_count;
	}

	//ログインユーザと共通のフォロワー数のカウント
	protected function _getFollowerUserCommonCount(){
        $this->setTables();
        $follower_user_common_count = $this->TableEntity['follow_users']->find()->where(['follower_user' => $this->FollowerId,'follow IN' => $this->LoginUserFollow['follower_user']])->count();
	    return $follower_user_common_count;
	}

    //ユーザのレビュー情報を取得
    protected function _getUserReview(){
        $this->setTables();
        $follower_shop_review = $this->TableEntity['follows']->find()->where(['follow' => $this->FollowerId,'follower_shop' => $this->FollowerShopId])->select(['review'=>'review','rating'=>'rating'])->first();
        return $follower_shop_review;
    }

    //フォローしているか
    protected function _getIsUserFollow(){
        $this->setTables();
        
        $result = $this->TableEntity['follow_users']->find()->where(['follow' => $this->LoginUserId,'follower_user' => $this->FollowerId])->first();
        if($result == null){ //未フォロー
            return 0;
        }else{ //フォロー済み
            return 1;
        }
    }
    //テーブルエンティティのセット
    private function setTables() {
        $TableEntity['follows'] = TableRegistry::get('follows');
        $TableEntity['follow_users'] = TableRegistry::get('follow_users');
        $this->TableEntity = $TableEntity;
    }

}