<?php ?>
	<div class="contents shop">
		<article class="contain">
			<div class="contain_inner bg-white">
				<div class="review">
					<?php
					    echo $this->element('shop_header', ["type" => "review"]);
					    echo $this->element('shop_favorite_header', ["type" => "followed"]);
						foreach($favoriteDatas as $favoriteData){
						    echo $this->element('user_review', ["user_data" => $favoriteData]);
						}
					?>
				</div>
			</div>
		</article>
	</div>