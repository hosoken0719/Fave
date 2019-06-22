<?php ?>


<?php // var_dump($Users); ?>
<div class="contents users form">
    <div class="col-sm-12">
        <article class="content">
            <?php //$this->Form->create($userData,['class'=>'submit','url' => ['controller' => 'Accounts', 'action' => 'index']]) ?>
            <?= $this->Form->create($userData) ?>
			<fieldset>
				<legend><?= __d('CakeDC/Users', 'アカウント') ?></legend>
				
				<?= $this->Flash->render() ?>
				<?= $this->Form->control('username', ['label' => 'アカウント名' , 'value'=> $userData->username]) ?>
				<?= $this->Form->control('nickname', ['label' => 'ニックネーム' , 'value'=> $userData->nickname]) ?>
				<?= $this->Form->control('sex', ['type' => 'select' , 'label' => '性別' , 'options' => $sexList , 'default' => $userData->sex]) ?>
				<?= $this->Form->control('address', ['label' => '住まい' , 'value'=> $userData->address]) ?>
				<?= $this->Form->control('introduction', ['label' => '自己紹介' , 'value'=> $userData->introduction]) ?>
				<?= $this->Form->control('email', ['label' => __d('CakeDC/Users', 'Email') , 'value'=> $userData->email]); ?>
			</fieldset>
			<?= $this->Form->button('更新'); ?>
			<?= $this->Form->end() ?>
		</article>
            
	<?= $this->Html->link(__('ログアウト'), ['controller' => 'logout', 'action' => 'index']) ?>
</div>

</div>
