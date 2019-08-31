<?php
namespace App\Model\Table;

use CakeDC\Users\Model\Table\UsersTable as UsersTableParent;

class UsersTable extends UsersTableParent{
    //Emailアドレスの重複を禁止を上書き
    public $isValidateEmail = true;

    public function initialize(array $config)
    {

    //モデル名を入れる
       $this->belongsTo('sexs',[
           'joinType' => 'LEFT',
           'foreignKey' => 'sex',
           'bindingKey' => 'id',
           'propertyName' => 'Sexs'
       ]);


    }

}
?>