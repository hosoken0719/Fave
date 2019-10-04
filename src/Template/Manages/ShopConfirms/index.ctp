<?php ?>
<div class="contents regist">
    <article class="contain bg-white">
		<div class="contain_inner">
	    	<?php
				foreach($shopDatas as $shopData):
	    	?>
  	            <div class="name">
                    <?php echo $this->Html->link($shopData->shopname, ['action' => 'check', $shopData->shop_id]); ?>
				<?php endforeach; ?>

            </div>
	    </div>
	</article>
</div>