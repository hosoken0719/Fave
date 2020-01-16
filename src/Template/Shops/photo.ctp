<?php echo $this->Html->script(['croppie.min.js','croppie_option.js'],['block' => true]) ?>
<div class="contents shop">
    <article class="contain">
		<div class="contain_inner bg-white">
			<?= $this->element('shop_header', ["type" => "photo"]); ?>
			<div class="photo_list mt-5 mb-5">

				<?php if($shop_photos <> null): ?>
					<?php foreach ($shop_photos as $value) :?>
						<figure class="photo"><?= $this->Html->image($shop_photo_dir.$value->file_name,array("class" => "img-fluid thumbs")); ?> </figure>
					<?php endforeach; ?>
				<?php endif; ?>
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


<script type="text/javascript">var id = "<?= $shopData->shop_id ?>";var path = 'shopimage';var type = 'square';</script>