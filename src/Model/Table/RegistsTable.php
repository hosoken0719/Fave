<?php
namespace App\Model\Table;

use App\Model\Entity\Regist;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;




    class RegistsTable extends Table
    {

    public function initialize(array $config)
    {
        parent::initialize($config);

    }

    public function validationDefault(Validator $validator)
    {

        $validator
            ->allowEmpty('img_ext')
            ->add('img_ext', ['list' => [
                'rule' => ['inList', ['jpg', 'png', 'gif']],
                'message' => 'jpg, png, gif のみアップロード可能です.',
            ]]);

        $validator
            ->integer('img_size')
            ->allowEmpty('img_size')
            ->add('img_size', 'comparison', [
                'rule' => ['comparison', '<', 10485760],
                'message' => 'ファイルサイズが超過しています(MaxSize:10M)',
            ]);
    }