<?php ?>
	<div class="contents shop">
		<article class="contain">
			<div class="contain_inner">
				<div class="review">
					<?php
					    echo $this->element('shop_favorite_header', ["type" => "all"]);

						foreach($favoriteDatasLoginUser as $favoriteData){
						    echo $this->element('user_review', ["user_data" => $favoriteData]);
						}
						foreach($favoriteDatasNotIn as $favoriteData){
						    echo $this->element('user_review', ["user_data" => $favoriteData]);
						}
						foreach($favoriteDatas as $favoriteData){
						    echo $this->element('user_review', ["user_data" => $favoriteData]);
						}
					?>
				</div>
			</div>
		</article>
	</div>