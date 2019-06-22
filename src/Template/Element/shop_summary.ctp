<?php ?>
<div class='summary'>
	<?= $this->Html->link("
	<div class='icon'>
		".$this->Html->image($photoShop,array("class" => "img-fluid"))."
	</div>
	<div class='infor'>
		<div class='name'>
			<p>".h($shopname)."
			</p>
		</div>
		<span class='other'>
			<p>
				".h($typename)."
			</p>
			<p>
				".h($address)."
			</p>
		</span>",
		array('controller' => 'shops', 'action' => '/'. $shop_id), array('escape' => false)); ?>
		<div class="rating">
			<?= $this->element('rating',['rating'=>$myrating[$shopData->shop_id],'shop_id'=>$shopData->shop_id,'enable'=>0]); ?>
		</div>
	</div>
</div>