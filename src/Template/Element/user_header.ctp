<?php ?>
<div class="content header">
	<div class="icon_area">
		<div class="avatar"><?php echo $this->Html->image($avatar,['alt'=> 'User','class'=>'circle']); ?></div>
	</div>
	<div class="detail pt-2">
		<div class="user_name">
			<h1>
				<?php
				if(!empty($nickname)){
					echo $nickname;
				}else{
					echo $user_name;
				}
				?>
			</h1>
			<?php if($this->request->getSession()->read('Auth.User.id')): //ログイン時のみ表示?>
				<?php if($login_id <> $user_id): //ログインユーザ自身の場合はフォローボタンを非表示 ?>
					<span class='follow'>
					<?php echo $this->Form->create('Follow');
					echo $this->Form->submit($follow_status['text'],['id'=>'button'.$user_id ,'class'=>'follow_button inline '.$follow_status['tag'],'data-user'=>$user_id , 'data-button'=>'button'.$user_id,'div'=>false ]);
					echo $this->Form->end(); ?>
					</span>
				<?php endif; ?>
			<?php else: ?>
				<?= $this->Html->link(__('フォローするにはログインが必要です'), ['plugin'=>'CakeDC/Users','controller' => 'users', 'action' => 'login']) ?>
			
			<?php endif; ?>

        </div>
        <div>
			<?php if($sex) echo "<div class='sex'>{$sex}</div>"; ?>
			<?php if($address) echo "<div class='address'>{$address}</div>"; ?>
		</div>
	</div>
</div>

<div class="loading hide"></div>
<div class="type">
	<div class="switch">
		<ul class="search_tab clearfix">
			<li <?php if($type === 'shop') echo "class='active'"; ?>>
				<?= $this->Html->link(__('お気に入り<br />'.$count['follow_shops'].'店'), ['controller' => 'Users', 'action' => '/',$user_name],['escape' => false]); ?>
			</li>
			<?php if($count['follow_users'] > 0) : ?>
				<li <?php if($type === 'user') echo "class='active'"; ?>>
					<?= $this->Html->link(__('フォロー<br />'.$count['follow_users'].'人'), ['controller' => 'Users', 'action' => 'followUsers',$user_name],['escape' => false]); ?>
			<?php else: ?>
				<li>フォローユーザ<br />0人
			<?php endif; ?>

				</li>
			<?php if($count['followers'] > 0) : ?>
				<li <?php if($type === 'follower') echo "class='active'"; ?>>
					<?= $this->Html->link(__('フォロワー<br />'.$count['followers'].'人'), ['controller' => 'Users', 'action' => 'followers',$user_name],['escape' => false]); ?>
			<?php else: ?>
				<li>フォロワー<br />0人
			<?php endif; ?>
			</li>
		</ul>
	</div>
</div>