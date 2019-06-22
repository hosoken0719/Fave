<?php
namespace App\Model\Table;

use App\Model\Entity\Sex;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


    class SexsTable extends Table
    {
	    public function initialize(array $config)
	    {
	    	parent::initialize($config);
	    	$this->setTable('sexs');
	        $this->setDisplayField('id');
	        $this->setPrimaryKey('id');



        // //　モデル名を入れる
        // $this->belongsTo('users', [
        //     'foreignKey' => 'sex',
        // ]);
    }



	    }


?>