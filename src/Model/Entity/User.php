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

}
?>