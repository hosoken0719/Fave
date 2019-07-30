<?php //①フォローショップ?>
<div class="contents user">
    <article class="contain">
        <div class="contain_inner">
			<?php echo $this->element('user_header' , ["type" => "shop"]); ?>
			<div class="shop_flame">
				<?php
				//ログインユーザと共通のフォローショップを表示
				echo $this->element('shop_list',['Query' => $FollowShopsIn,'isFollowed'=>1]);
				//残り全てのショップを表示
				echo $this->element('shop_list',['Query' => $FollowShopsNotIn,'isFollowed'=>0]);
				?>
	        </div>
    	</div>
	</article>
</div>
