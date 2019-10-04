<?php echo $this->Html->script(['croppie.min.js','croppie_option.js'],['block' => true]) ?>


<div class="contents form account">
    <article class="contain-small">
       	<div class="contain_inner bg-white">
			<div class="form">
            <?php //$this->Form->create($userData,['class'=>'submit','url' => ['controller' => 'Accounts', 'action' => 'index']]) ?>
            <?= $this->Form->create($userData,['templates' => $template]) ?>

			<div class="containera">
				<div class="panel panel-default text-center">
					<div class="avatar"><?php if(!empty($avatar))echo $this->html->image($avatar,['class'=>'circle']); ?></div>
					<div class="panel-body file">
						<label>プロフィール写真を投稿
						<?= $this->Form->control('field', ['type' => 'file' , 'name' => 'upload_image' , 'id' =>'upload_image','label'=>false]); ?>
						</label>
						<div id="uploaded_image"></div>
					</div>
				</div>
			</div>
			<!-- <?= $avatar ?> -->
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
            <div class="link text-center mt-3">
			<?= $this->Html->link(__('ログアウト'), ['controller' => 'logout', 'action' => 'index']) ?>
		</div>
			</div>
		</div>
	</article>
</div>
<div id="uploadimageModal" class="modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-body">
				<div class="row">
						<div id="image-demo" style="width:100%; margin-top:30px";></div>
				</div>
				<div class="button form">
					<button class="btn vanilla-rotate">回転</button>
					<?= $this->Form->create(); ?>
					<?= $this->Form->button('登録', array('type' => 'button','class' => 'btn crop_image')); ?>
					<?= $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var src = '<?= $avatar ?>';
	$('.avatar').children('img').attr('src', src + '?' + new Date().getTime()); //画像を毎回読み込むため

	var id = <?= $userData->id ?>;var path = 'avatar';var type = 'circle';
</script>

