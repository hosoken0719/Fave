<?php
namespace App\Model\Table;

use App\Model\Entity\Shop;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class ShopsTable extends Table
{

    public function initialize(array $config){
    	parent::initialize($config);

    	$this->setTable('shops');
        $this->setDisplayField('shopname');
        $this->setPrimaryKey('id');

       $this->belongsTo('shoptypes',[
           'joinType' => 'LEFT',
           'foreignKey' => 'shoptype',
           'bindingKey' => 'id',
           'propertyName' => 'shoptypes'
       ]);

        $this->belongsTo('shoptypes2',[
          'className' => 'shoptypes',
           'joinType' => 'LEFT',
           'foreignKey' => 'shoptype2',
           'bindingKey' => 'id',
           'propertyName' => 'shoptypes'
       ]);

      $this->belongsTo('prefectures',[
           'joinType' => 'LEFT',
           'foreignKey' => 'pref',
           'bindingKey' => 'id',
           // 'propertyName' => 'pref'
       ]);

      $this->belongsTo('follows',[
           'joinType' => 'LEFT',
           'foreignKey' => 'id',
           'bindingKey' => 'follower_shop',
       ]);

      $this->hasMany('shop_photos',[
         'joinType' => 'LEFT',
         'foreignKey' => 'id',
         'bindingKey' => 'shop_id',
      ]);
    }

}

?>