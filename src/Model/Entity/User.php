<?php
// namespace App\Model\Entity;

// use CakeDC\Users\Model\Entity\User as baseUser;

// use Cake\Core\Configure;
// use Cake\I18n\Time;
// use Cake\ORM\Entity;
// use Cake\Utility\Security;

// class User extends baseUser
// {
// }
namespace App\Model\Entity;

use CakeDC\Users\Model\Entity\User as UserParent;

// プラグインのファイルからコピペ
use Cake\Core\Configure;
use Cake\I18n\Time;
use Cake\ORM\Entity;
use Cake\Utility\Text;
use DateTime;

class User extends UserParent
{

	// protected function _getSex($sex){
	// 	switch($sex){
	// 		case 1:
	// 			return '男性';
	// 			break;
	// 		case 2:
	// 			return '女性';
	// 			break;
	// 		case 3:
	// 			return '指定なし';
	// 			break;
	// 	}
	// }
}
?>