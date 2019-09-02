<?php
/**
 * Copyright 2010 - 2017, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2017, Cake Development Corporation (https://www.cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

use Cake\Core\Configure;

?>
<div class="contents users form">
    <article class="contain-small bg-white">
        <div class="contain_inner">
            <?= $this->Flash->render('auth') ?>
            <?= $this->Form->create('',['class'=>'submit']) ?>
            <fieldset>
                <legend><?= __d('CakeDC/Users', 'ログイン') ?></legend>
            <div class="text-center mt-4"><?= implode(' ', $this->User->socialLoginList()); ?></div>
            <p class="text-center mt-4">or</p>
                <?= $this->Form->control('username', ['label' => __d('CakeDC/Users', 'ユーザ名 もしくは Email'), 'required' => true]) ?>
                <?= $this->Form->control('password', ['label' => __d('CakeDC/Users', 'パスワード'), 'required' => true]) ?>
                <?php
                if (Configure::read('Users.reCaptcha.login')) {
                    echo $this->User->addReCaptcha();
                }
                ?>

            </fieldset>
<?= $this->Flash->render() ?>
            <?= $this->Form->button(__d('CakeDC/Users', 'ログイン')); ?>
            <div class="text-right">
                <?php
                if (Configure::read('Users.RememberMe.active')) {
                    echo $this->Form->control(Configure::read('Users.Key.Data.rememberMe'), [
                        'type' => 'checkbox',
                        'value' => '1',
                        'label' => __d('CakeDC/Users', 'ログイン情報を保存する'),
                        'checked' => Configure::read('Users.RememberMe.checked'),
                        'class' => 'w-auto'
                    ]);
                }
                ?>
            </div>

            <?= $this->Form->end() ?>

            <div class="link text-center">
            <?php
            $registrationActive = Configure::read('Users.Registration.active');
            if ($registrationActive) {
                echo $this->Html->link(__d('CakeDC/Users', '新規登録'), ['action' => 'register']);
            }
            if (Configure::read('Users.Email.required')) {
                if ($registrationActive) {
                    echo ' | ';
                }
                echo $this->Html->link(__d('CakeDC/Users', 'パスワードを忘れた場合'), ['action' => 'requestResetPassword']);
            }
            ?>
            </div>
        </div>
        </article>  
</div>
