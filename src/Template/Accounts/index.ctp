<?php ?>


<?php // var_dump($Users); ?>
<div class="contents users form">
    <article class="contain-small bg-white">
       	<div class="contain_inner">
			<div class="form">
            <?php //$this->Form->create($userData,['class'=>'submit','url' => ['controller' => 'Accounts', 'action' => 'index']]) ?>
            <?= $this->Form->create($userData,['templates' => $template]) ?>
			<fieldset>
				<!-- <legend><?= __d('CakeDC/Users', 'アカウント') ?></legend> -->
				<?= $this->Flash->render() ?>
				<dl><?= $this->Form->control('username', ['label' => 'ユーザ名' , 'value'=> $userData->username]) ?></dl>
				<dl><?= $this->Form->control('nickname', ['label' => 'ニックネーム' , 'value'=> $userData->nickname]) ?></dl>
				<dl><?= $this->Form->control('email', ['label' => __d('CakeDC/Users', 'Email') , 'value'=> $userData->email]); ?></dl>
				<dl><?= $this->Form->control('sex', ['type' => 'select' , 'label' => '性別' , 'options' => $sexList , 'default' => $userData->sex]) ?></dl>
				<dl><?= $this->Form->control('address', ['label' => '好きなエリア' , 'value'=> $userData->address]) ?></dl>
				<dl>
					<dt>自己紹介</dt>
                	<dd><?= $this->Form->control('introduction', ['label' => '自己紹介' , 'value'=> $userData->introduction,'rows' => 3,'label'=>false]) ?></dd>
                </dl>

				<?= $this->Form->button('更新'); ?>
			</fieldset>
			<?= $this->Form->end() ?>
            <div class="link text-center">
			<?= $this->Html->link(__('ログアウト'), ['controller' => 'logout', 'action' => 'index']) ?>
		</div>
			</div>
		</div>
	</article>
</div>


