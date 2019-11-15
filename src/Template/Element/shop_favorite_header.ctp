<?php ?>
	<div class="inner_link_sub">
		<ul>
		<?php if($shopData->cnt_followed > 0): //フォロー済みのユーザがいない場合はリンクを付けない?>
			<li <?php if($type === 'followed') echo "class='active'"; ?>>
				<p>
				<?= $this->Html->link(__('フォロー済みのユーザ<br />'.$shopData->cnt_followed.'人'), ['controller' => 'Shops','action' => '/'.$shopData->shop_id.'/favorited_follow'],['escape' => false]); ?>
				</p>
			</li>
		<?php else: ?>
			<li>
				フォロー済みのユーザ<br />0人
			</li>
		<?php endif; ?>

		<?php if($shopData->cnt_all > 0): ?>
			<li <?php if($type === 'all') echo "class='active'"; ?>>
				<p>
				<?= $this->Html->link(__('全てのユーザ<br />'.$shopData->cnt_all.'人'), ['controller' => 'Shops','action' => '/'.$shopData->shop_id.'/favorited_all'],['escape' => false]); ?>
				</p>
			</li>
		<?php else: ?>
			<li>
				全てのユーザ<br />0人
			</li>
		<?php endif; ?>
		</ul>
	</div>