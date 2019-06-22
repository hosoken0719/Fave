<?php
namespace App\Model\Table;

use App\Model\Entity\Tag;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;




    class TagsTable extends Table
    {
    public function initialize(array $config)
    {
    	parent::initialize($config);

    	$this->setTable('tags');
        $this->setDisplayField('tag');
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