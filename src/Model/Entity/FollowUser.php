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
class FollowUser extends Entity
{
    //Viewから変数に代入される
	public $FollowerId;
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
        if(!empty($this->LoginUserFollow['follower_shop'])){
    	   $follow_shop_common_count = $this->TableEntity['follows']->find()->where(['follow' => $this->FollowerId,'follower_shop IN' => $this->LoginUserFollow['follower_shop']])->count();
        }else{
            $follow_shop_common_count = 0;
        }
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
        if(!empty($this->LoginUserFollow['follower_user'])){
    	   $follow_user_common_count = $this->TableEntity['follow_users']->find()->where(['follow' => $this->FollowerId,'follower_user IN' => $this->LoginUserFollow['follower_user']])->count();
        }else{
            $follow_user_common_count = 0;
        }
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
        if(!empty($this->LoginUserFollow['follower_user'])){
            $follower_user_common_count = $this->TableEntity['follow_users']->find()->where(['follower_user' => $this->FollowerId,'follow IN' => $this->LoginUserFollow['follower_user']])->count();
        }else{
            $follower_user_common_count = 0;
        }
	    return $follower_user_common_count;
	}

    protected function setUserID($user_id){
        return $user_id;
    }

    protected function _getAvatar(){

        $UsersTable = TableRegistry::get('Users');
        $result = $UsersTable->find()->contain(['social_accounts'])->where(['Users.id' => $this->FollowerId])->select(['avatars' => 'social_accounts.avatar'])->first();
        //avatarにすると、なぜかnullになるためavatars変更
        return $result->avatars;
    }

    private function setTables() {
        $TableEntity['follows'] = TableRegistry::get('follows');
        $TableEntity['follow_users'] = TableRegistry::get('follow_users');
        $this->TableEntity = $TableEntity;
    }


}