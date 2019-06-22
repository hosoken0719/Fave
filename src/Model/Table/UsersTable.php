<?php
namespace App\Model\Table;

use CakeDC\Users\Model\Table\UsersTable as UsersTableParent;

// プラグインのファイルからコピペ
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Hash;
use Cake\Validation\Validator;

class UsersTable extends UsersTableParent
{

    //Emailアドレスの重複を禁止を上書き
    public $isValidateEmail = true;


    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('username');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->addBehavior('CakeDC/Users.Register');
        $this->addBehavior('CakeDC/Users.Password');
        $this->addBehavior('CakeDC/Users.Social');
        $this->addBehavior('CakeDC/Users.LinkSocial');
        $this->addBehavior('CakeDC/Users.AuthFinder');
        $this->hasMany('SocialAccounts', [
            'foreignKey' => 'user_id',
            'className' => 'CakeDC/Users.SocialAccounts'
        ]);
    
    //モデル名を入れる
       $this->belongsTo('sexs',[
           'joinType' => 'LEFT',
           'foreignKey' => 'sex',
           'bindingKey' => 'id',
           'propertyName' => 'Sexs'
       ]);


    }

    public function validationDefault(Validator $validator)
    {

        $validator = parent::validationDefault($validator);


        // バリデーションの例
        $validator
         ->lengthBetween('password', [1,20],
            	'パスワードは 8~20 文字でお願いします。'
        	);

        return $validator;

    }

}
?>