<?php ?>

			<div class="shop_header mb-3 text-left">
				<div class="information">
					<div class="name">
						<div class="type">
							<?=  h($shopData->typename); ?>
							<?php if($shopData->typename2){
								echo "/" . $shopData->typename2;
							}
							?>
						</div>
						<h2>
							<?= $this->Html->link( h($shopData->shopname) . ' ' . h($shopData->branch), ['controller' => 'Shops','action' => '/',$shopData->shop_id],['escape' => false]); ?>
						</h2>
							(<?=  h($shopData->kana)?>)

					</div>
				<?php if($this->request->getSession()->read('Auth.User')): //ログイン時はフォロー済みratingも表示?>

		            <div class="rating d-inline">
		            <?php //フォロー済みのrating

		                 echo $this->Html->link(
		                $this->Html->image('followed_users.svg'). $this->element('rating',['rating'=>round($shopData->avg_followed),'shop_id'=>$shopData->shop_id,'enable'=>0]).
		                number_format(round(h($shopData->avg_followed),1),1).
		                " (".h($shopData->cnt_followed)."人)",
		                 [
								'controller' => 'shops',
							    'action' => '/'.$shopData->shop_id.'/favorited_follow'
							],
						['escape' => false]); ?>
		            </div>
		            <br />
		            <div class="rating d-inline all">
		            <?php //全員のrating
		            	echo $this->Html->link(
		                $this->Html->image('all_users.svg'). $this->element('rating',['rating'=>round($shopData->avg_all),'shop_id'=>$shopData->shop_id,'enable'=>0]).
		                number_format(round(h($shopData->avg_all),1),1).
		                " (".h($shopData->cnt_all)."人)",
		                 [
								'controller' => 'shops',
							    'action' => '/'.$shopData->shop_id.'/favorited_all'
							],
						['escape' => false]); ?>
		            </div>

				<?php else: //ログインしてない場合は全員のratingのみ表示?>
					<div class="rating d-inline all">
		            	<?php //全員のrating
		            	echo $this->Html->link(
		                $this->Html->image('all_users.svg'). $this->element('rating',['rating'=>round($shopData->avg_all),'shop_id'=>$shopData->shop_id,'enable'=>0]).
		                number_format(round(h($shopData->avg_all),1),1).
		                " (".h($shopData->cnt_all)."人)",
		                 [
								'controller' => 'shops',
							    'action' => '/favorited_all',$shopData->shop_id
							],
						['escape' => false]); ?>
		            </div>
				<?php endif; ?>
			    </div>

			<?php if($this->request->getSession()->read('Auth.User')): //ログイン時のみお気に入り登録ボタンを表示?>
			<div class="favorite_button">
				<div class="button">
					<?php if($ShopFollowData['rating'] === 0){
						echo $this->Html->link(__('お気に入りに登録'), ['controller' => 'Shops','action' => '/'.$shopData->shop_id.'/post_favorite'],['escape' => false]);
					}else{
						echo $this->Html->link(__('お気に入り登録済み'), ['controller' => 'Shops','action' => '/'.$shopData->shop_id.'/post_favorite'],['escape' => false]);
					}
					?>
				</div>
			</div>
			<?php endif; ?>

		</div>

		<div class="inner_link mb-5">
			<ul class="search_tab clearfix">
				<li <?php if($type === 'basic') echo "class='active'"; ?>>
					<?= $this->Html->link(__('基本情報'), ['controller' => 'Shops','action' => '/',$shopData->shop_id],['escape' => false]); ?>
				</li>
				<li <?php if($type === 'review') echo "class='active'"; ?>>
				<?php //ログインしていない場合は全てのユーザにリンクをする

				if($shopData->cnt_followed === 0 and $shopData->cnt_all === 0):
					echo "口コミ";
				elseif($this->request->getSession()->read('Auth.User'))://ログインしている場合
					if($shopData->cnt_followed > 0 ):
						echo $this->Html->link(__('口コミ'), ['controller' => 'Shops','action' => '/'.$shopData->shop_id.'/favorited_follow'],['escape' => false]);
					else: //ログインしてない場合は全員のratingのみ表示
						echo $this->Html->link(__('口コミ'), ['controller' => 'Shops','action' => '/'.$shopData->shop_id.'/favorited_all'],['escape' => false]);
					endif;
				else: //ログインしてない場合は全員のratingのみ表示
					echo $this->Html->link(__('口コミ'), ['controller' => 'Shops','action' => '/'.$shopData->shop_id.'/favorited_all'],['escape' => false]);
				endif;
				?>
				</li>
				<li <?php if($type === 'photo') echo "class='active'"; ?>>
					<?= $this->Html->link(__('写真'), ['controller' => 'Shops','action' => '/'.$shopData->shop_id.'/photo'],['escape' => false]); ?>
				</li>
				<li <?php if($type === 'map') echo "class='active'"; ?>>
					<?= $this->Html->link(__('地図'), ['controller' => 'Shops','action' => '/'.$shopData->shop_id.'/map'],['escape' => false]); ?>
				</li>
			</ul>
		</div>

		