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
             if(file_exists(PHOTO_UPLOADDIR.'/user_photos/thumbnail/large_'.$user_id.'.jpg')){
                     $avatar = '/img/user_photos/thumbnail/large_'.$user_id.'.jpg';
              }else{
                  $UsersTable = TableRegistry::get('Users');
                  $query = $UsersTable->find()->contain(['social_accounts'])->where(['Users.id' => $user_id])->select(['avatars' => 'social_accounts.avatar'])->first();
                  if(!Empty($query->avatars)){
                      $avatar = $query->avatars;
                  }else{
                      $avatar = '/img/avatar.png';
                  }
              }
              return $avatar;
	}
}