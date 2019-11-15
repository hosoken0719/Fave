<?php ?>
	<div class="contents shop">
		<article class="contain">
			<div class="contain_inner bg-white">
				<div class="review">
					<?php
					    echo $this->element('shop_header', ["type" => "review"]);

						if($this->request->getSession()->read('Auth.User')){//ログインしていない場合は切り替えヘッダーを表示しない
					    	echo $this->element('shop_favorite_header', ["type" => "all"]);
					    }

						foreach($favoriteDatasNotIn as $favoriteData){
						    echo $this->element('user_review', ["user_data" => $favoriteData]);
						}
						foreach($favoriteDatas as $favoriteData){
						    echo $this->element('user_review', ["user_data" => $favoriteData]);
						}
						foreach($favoriteDatasLoginUser as $favoriteData){
						    echo $this->element('user_review', ["user_data" => $favoriteData]);
						}
					?>
				</div>
			</div>
		</article>
	</div>