<?php if(!isset($name)){$name = "rating";} ?>
	<form type="get" action="#">
		<div class="evaluation">
 			<input type="radio" name="<?= $name ?>" value="5" disabled='disabled' <?php if($enable<>0): echo ' id=star1'; endif; if($rating==5) echo " checked"; ?> />
			<label for="star1">★</label>
			<input type="radio" name="<?= $name ?>" value="4" disabled='disabled' <?php if($enable<>0): echo ' id=star2'; endif; if($rating==4) echo " checked"; ?> />
			<label for="star2">★</label>
			<input type="radio" name="<?= $name ?>" value="3" disabled='disabled' <?php if($enable<>0): echo ' id=star3'; endif; if($rating==3) echo " checked"; ?> />
			<label for="star3">★</label>
			<input type="radio" name="<?= $name ?>" value="2" disabled='disabled' <?php if($enable<>0): echo ' id=star4'; endif; if($rating==2) echo " checked"; ?> />
			<label for="star4">★</label>
			<input type="radio" name="<?= $name ?>" value="1" disabled='disabled' <?php if($enable<>0): echo ' id=star5'; endif; if($rating==1 or $rating ==0) echo " checked";?> />
			<label for="star5">★</label>
			<input class="rating" type="hidden" name="shop_id" value=<?= $shop_id ?>>
			<input type="hidden" name="button" value="button" ?>
		</div>
	</form>
