<?php ?>
<script type="text/javascript">var id = "<?= $userData->id ?>";</script>
<?= $this->Html->script(['upload_user_photo.js']); ?>


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

			<!-- 写真投稿時のモーダルウィンドウ -->
			<div class="modal js-modal">
		        <div class="modal__bg js-modal-close"></div>
		        <div class="modal__content">
					<!-- アップロード開始ボタン -->
					<!-- サムネイル表示領域 -->
					<canvas id="canvas" width="0" height="0"></canvas>

					<!-- アップロード開始ボタン -->
					<button class="btn" id="upload">投稿</button>
					<button class="btn closes js-modal-close">閉じる</button>
		        </div><!--modal__inner-->
		    </div><!--modal-->

<script type="text/javascript">
	var src = '<?= $avatar ?>';
	$('.avatar').children('img').attr('src', src + '?' + new Date().getTime()); //画像を毎回読み込むため

	var id = <?= $userData->id ?>;var path = 'avatar';var type = 'circle';
</script>

