<?php
namespace App\Model\Table;

use App\Model\Entity\Follow;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;




  class FollowsTable extends Table
  {

    public function initialize(array $config)
    {
    	parent::initialize($config);

      $this->addBehavior('Timestamp');

    	// $this->setTable('follows');
      // $this->setDisplayField('follow');
      $this->setPrimaryKey(['follow','follower_shop']);

      $this->belongsTo('shops',[
       'joinType' => 'inner',
       'foreignKey' => 'follower_shop',
       'bindingKey' => 'id',
       'propertyName' => 'shops'
      ]);

      $this->belongsTo('users',[
       'joinType' => 'inner',
       'foreignKey' => 'follow',
       'bindingKey' => 'id',
       'propertyName' => 'users'
      ]);

      $this->belongsTo('shoptypes',[
       'joinType' => 'LEFT',
       'foreignKey' => 'shoptype',
       'bindingKey' => 'id',
       'propertyName' => 'shoptypes'
      ]);


    }




}

?>