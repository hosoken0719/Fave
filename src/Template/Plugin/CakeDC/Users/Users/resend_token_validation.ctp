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
		    <?= $this->Form->create($user); ?>
		    <fieldset>
		        <legend><?= __d('CakeDC/Users', '認証メールの再送') ?></legend>
		        <?php
		        echo $this->Form->control('reference', ['label' => __d('CakeDC/Users', 'ユーザ名 もしくは Email')]);
		        ?>
		    </fieldset>
<?= $this->Flash->render() ?>
		    <?= $this->Form->button(__d('CakeDC/Users', 'メール送信')) ?>
		    <?= $this->Form->end() ?>
            <div class="link">
	            <?php
	            $registrationActive = Configure::read('Users.Registration.active');
	            if ($registrationActive) {
	                echo $this->Html->link(__d('CakeDC/Users', '新規登録'), ['action' => 'register']);
	            }
	            ?>
            </div>
		</article>
	</div>
</div>
