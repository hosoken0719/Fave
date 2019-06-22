<?php
namespace App\Model\Table;

use App\Model\Entity\Users_tag;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;




    class Users_tagsTable extends Table
    {
    public function initialize(array $config)
    {
    	parent::initialize($config);

    	$this->setTable('users_tags');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

    }

    
    //	public $primaryKey = 'follower';

//    	public $hasOne = array(
  //  		'User'=>array(
	//	      'foreignKey'=>'username',
	//	     )
	 // );

}

?>