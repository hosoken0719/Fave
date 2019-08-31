<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \App\Model\Table\ShopsTable|\Cake\ORM\Association\HasMany $Shops
 * @property \App\Model\Table\TagsTable|\Cake\ORM\Association\BelongsToMany $Tags
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        // $this->hasMany('Shops', [
        //     'foreignKey' => 'user_id'
        // ]);
        // $this->belongsToMany('Tags', [
        //     'foreignKey' => 'user_id',
        //     'targetForeignKey' => 'tag_id',
        //     'joinTable' => 'users_tags'
        // ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        // $validator
        //     ->integer('id')
        //     ->allowEmpty('id', 'create');

        // $validator
        //     ->scalar('username')
        //     ->maxLength('username', 255)
        //     ->requirePresence('username', 'create')
        //     ->notEmpty('username');

        // $validator
        //     ->scalar('password')
        //     ->maxLength('password', 255)
        //     ->requirePresence('password', 'create')
        //     ->notEmpty('password');

        // $validator
        //     ->email('email')
        //     ->requirePresence('email', 'create')
        //     ->notEmpty('email')
        //     ->add('email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        // $validator
        //     ->integer('role')
        //     ->requirePresence('role', 'create')
        //     ->notEmpty('role');

        // $validator
        //     ->dateTime('last_login_at')
        //     ->requirePresence('last_login_at', 'create')
        //     ->notEmpty('last_login_at');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['accountname']));
        $rules->add($rules->isUnique(['email']));

        return $rules;
    }
}