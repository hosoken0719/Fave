<?php
namespace App\Model\Table;

use App\Model\Entity\FollowUser;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;




    class FollowUsersTable extends Table
    {
    public function initialize(array $config)
    {
    	parent::initialize($config);

    	$this->setTable('follow_users');
        // $this->setDisplayField('shopname');
        $this->setPrimaryKey(['follow','follower_user']);


            //モデル名を入れる


    }

    
    //	public $primaryKey = 'follower';

//    	public $hasOne = array(
  //  		'User'=>array(
	//	      'foreignKey'=>'username',
	//	     )
	 // );

}

?>