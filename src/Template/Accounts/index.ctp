<?= $this->Html->script(['croppie.min.js','croppie_option.js'],['block' => true]) ?>


<div class="contents form">
    <article class="contain-small bg-white">
       	<div class="contain_inner">
			<div class="form">
            <?php //$this->Form->create($userData,['class'=>'submit','url' => ['controller' => 'Accounts', 'action' => 'index']]) ?>
            <?= $this->Form->create($userData,['templates' => $template]) ?>

			<div class="containera">
				<div class="panel panel-default text-center">
					<div class="avatar mb-3"><?php if(!empty($avatar))echo $this->html->image($avatar); ?></div>
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
            <div class="link text-center">
			<?= $this->Html->link(__('ログアウト'), ['controller' => 'logout', 'action' => 'index']) ?>
		</div>
			</div>
		</div>
	</article>
</div>
<div id="uploadimageModal" class="modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
<!-- 			<div class="modal-header">
				<button tupe="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Upload & cCrop Image</h4>
			</div> -->
			<div class="modal-body">
				<div class="row">
						<div id="image-demo" style="width:100%; margin-top:30px";></div>
				</div>
				<div class="button">
					<button class="btn vanilla-rotate">回転</button>

					<?= $this->Form->create(); ?>
					<?= $this->Form->button('登録', array('type' => 'button','class' => 'btn btn-success crop_image')); ?>
					<?= $this->Form->end(); ?>
					<!-- <button class="btn btn-success crop_image">登録</button> -->
				</div>
			</div>
		</div>
		<div class="model-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
	</div>
</div>


<script type="text/javascript">var id = <?= $userData->id ?>;var path = 'avatar';var type = 'circle';</script>

