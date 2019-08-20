<?php
// namespace App\Model\Table;
 
 
// use Cake\ORM\Query;
// use Cake\ORM\RulesChecker;
// use Cake\ORM\Table;
// use Cake\Utility\Hash;
// use Cake\Validation\Validator;
 
// /**
//  * Users Model
//  */
// class UsersTable extends Table
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
    public function validationDefault(Validator $validator)
    {
                $validator_new = parent::validationDefault($validator);

        $validator_new->add('username', 'custom', [
            'rule' => function ($value, $context) {
                return preg_match('/^[a-zA-Z][a-zA-Z0-9_\-]+$/', $value) === 1;
            },
            'message' => "英数字と記号（'-'または'_'）のみ利用できます。1文字目に数字・記号は利用できません。"
        ]);

        return $validator_new;
        // $validator
        //     ->allowEmpty('id', 'create');
 
        // $validator
        //     ->requirePresence('username', 'create')
        //     // ->notEmpty('username');
 
        // $validator
        //     ->requirePresence('password', 'create')
        //     ->notEmpty('password');
 
        // $validator
        //     ->allowEmpty('first_name');
 
        // $validator
        //     ->allowEmpty('last_name');
 
        // $validator
        //     ->allowEmpty('token');
 
        // $validator
        //     ->add('token_expires', 'valid', ['rule' => 'datetime'])
        //     ->allowEmpty('token_expires');
 
        // $validator
        //     ->allowEmpty('api_token');
 
        // $validator
        //     ->add('activation_date', 'valid', ['rule' => 'datetime'])
        //     ->allowEmpty('activation_date');
 
        // $validator
        //     ->add('tos_date', 'valid', ['rule' => 'datetime'])
        //     ->allowEmpty('tos_date');
 
        // return $validator;
    }
}