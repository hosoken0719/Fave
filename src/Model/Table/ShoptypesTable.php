<?php
namespace App\Model\Table;

use App\Model\Entity\Shoptype;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;




    class ShoptypesTable extends Table
    {
    public function initialize(array $config)
    {
    	parent::initialize($config);

    	$this->setTable('shoptypes');
        $this->setPrimaryKey('id');
        $this->setDisplayField('typename');
    }

}

?>