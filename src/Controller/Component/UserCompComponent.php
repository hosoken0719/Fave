<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\TableRegistry;

/**
 * UserCompComponent component
 */
class UserCompComponent extends Component{

	public function getAvatar($user_id){
		if(file_exists(PHOTO_UPLOADDIR.'/user_photos/'.$user_id.'.png')){
        	return '/img/user_photos/thumbnail/max_'.$user_id.'.png';
        }else{
        	$UsersTable = TableRegistry::get('Users');
        	$avatar = $UsersTable->find()->contain(['social_accounts'])->where(['Users.id' => $user_id])->select(['avatars' => 'social_accounts.avatar'])->first();
        	if(!Empty($avatar->avatars)){
        		return $avatar->avatars;
        	}else{
	        	return null;
        	}
        }
	}

}