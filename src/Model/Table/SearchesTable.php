<?php
namespace App\Model\Table;

use App\Model\Entity\Search;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


class SearchesTable extends Table
{
    public function initialize(array $config){
        parent::initialize($config);


        $this->belongsTo('shoptypes',[
           'joinType' => 'LEFT',
           'foreignKey' => 'shoptype',
           'bindingKey' => 'id',
           'propertyName' => 'shoptypes'
       ]);
    }
}

?>