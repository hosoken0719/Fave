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
    <div class="col-sm-12">
        <article class="content">
    <?= $this->Flash->render('auth') ?>
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __d('CakeDC/Users', 'パスワードの変更') ?></legend>
        <?php if ($validatePassword) : ?>
            <?= $this->Form->control('current_password', [
                'type' => 'password',
                'required' => true,
                'label' => __d('CakeDC/Users', '現在のパスワード')]);
            ?>
        <?php endif; ?>
        <?= $this->Form->control('password', [
            'type' => 'password',
            'required' => true,
            'label' => __d('CakeDC/Users', '新パスワード')]);
        ?>
        <?= $this->Form->control('password_confirm', [
            'type' => 'password',
            'required' => true,
            'label' => __d('CakeDC/Users', '新パスワード確認')]);
        ?>

    </fieldset>
<!-- <?= $this->Flash->render() ?> -->
    <?= $this->Form->button(__d('CakeDC/Users', '登録')); ?>
    <?= $this->Form->end() ?>
    </article>
</div>
</div>