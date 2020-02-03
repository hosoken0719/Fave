<?php ?>
	<article>
		<figure>
		<?php
			if($shopData->thumbnail):
				echo $this->Html->link(
                $this->Html->image('shop_photos/'.$shopData->shop_id.'/thumbnail/middle'.$shopData->thumbnail,["class" => ""]),
                ['controller' => 'shops', 'action' => '/'. $shopData->shop_id],
                ['escape' => false]);

			else:
				echo $this->Html->link(
                $this->Html->image('no_image.png',array("class" => "trimming img-fluid")),
                array('controller' => 'shops', 'action' => '/'. $shopData->shop_id),
                array('escape' => false));
			endif; ?>
		</figure>

		<div>
			<div class="category">
				<p>
				<?= $shopData->typename; ?></p>
			</div>
			<h1>
				<?= $this->Html->link($shopData->shopname,
                ['controller' => 'shops', 'action' => '/'. $shopData->shop_id],
                ['escape' => false]); ?>

			</h1>
			<p>
				<?= $shopData->pref.$shopData->address; ?>

			</p>
                <div class="rating d-inline">
		            <?php //フォロー済みのrating
		            echo $this->Html->link(
	                $this->Html->image('followed_users.svg'). $this->element('rating',['rating'=>round($shopData->avg_followed),'shop_id'=>$shopData->shop_id,'enable'=>0]),
		                [
							'controller' => 'shops',
						    'action' => '/'.$shopData->shop_id.'/favorited_follow'
						],
						['escape' => false]);
		            if($shopData->cnt_followed > 0) echo number_format(round(h($shopData->avg_followed),1),1). " (".h($shopData->cnt_followed)."人)";
					?>
		        </div>
		        <br />
		        <div class="rating d-inline all">
                <?php
		            echo $this->Html->link(
	                $this->Html->image('all_users.svg'). $this->element('rating',['rating'=>round($shopData->avg_all),'shop_id'=>$shopData->shop_id,'enable'=>0]),
		                [
							'controller' => 'shops',
						    'action' => '/'.$shopData->shop_id.'/favorited_all'
						],
						['escape' => false]);
                    if($shopData->cnt_all > 0) echo number_format(round($shopData->avg_all,1),1)." (".h($shopData->cnt_all)."人)";
                ?>
                </div>
		</div>
	</article>
</a>