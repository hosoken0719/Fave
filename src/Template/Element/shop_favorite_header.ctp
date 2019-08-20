<?php ?>
	<div class="shop_header">
		<div class="name">
                <h2><?= $this->Html->link(
                    $shopData->shopname,
                    ['controller' => 'shops', 'action' => '/'. $shopData->shop_id],
                    ['escape' => false]);
                ?>
			</h2>
			<div class="type">
				<?php echo h($shopData->typename); ?> | <?= h($shopData->pref.$shopData->city.$shopData->ward)?>
			</div>
		</div>
	</div>
	<div class="select">
		<div class="switch">
			<ul class="search_tab clearfix">
			<?php if($count['followed']  > 0): //フォロー済みのユーザがいない場合はリンクを付けない?>
				<li <?php if($type === 'followed') echo "class='active'"; ?>>
					<?= $this->Html->link(__('フォロー済みのユーザ<br />'.$count['followed'].'人'), ['controller' => 'Shops','action' => '/favorited_follow',$shopData->shop_id],['escape' => false]); ?>
				</li>
			<?php else: ?>
				<li>
					フォロー済みのユーザ<br />0人
				</li>
			<?php endif; ?>

			<?php if($count['all']  > 0): ?>
				<li <?php if($type === 'all') echo "class='active'"; ?>>
					<?= $this->Html->link(__('全てのユーザ<br />'.$count['all'].'人'), ['controller' => 'Shops','action' => '/favorited_all',$shopData->shop_id],['escape' => false]); ?>
				</li>
			<?php else: ?>
				<li>
					全てのユーザ<br />0人
				</li>
			<?php endif; ?>
			</ul>
		</div>
	</div>