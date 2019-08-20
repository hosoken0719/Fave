<?php if(!isset($name)){$name = "rating";} ?>
	<form type="get" action="#">
		<div class="evaluation">
			<input type="radio" name="<?= $name ?>" value="5"  <?php  if($enable==0): echo ' disabled'; else: echo ' id=star1'; endif; if($rating==5) echo " checked"; ?> />
			<label for="star1">&#9829;</label>
			<input type="radio" name="<?= $name ?>" value="4"  <?php  if($enable==0): echo ' disabled'; else: echo ' id=star2'; endif; if($rating==4) echo " checked"; ?> />
			<label for="star2">&#9829;</label>
			<input type="radio" name="<?= $name ?>"value="3" <?php if($enable==0): echo ' disabled'; else: echo ' id=star3'; endif; if($rating==3) echo " checked"; ?> />
			<label for="star3">&#9829;</label>
			<input type="radio" name="<?= $name ?>" value="2"  <?php if($enable==0): echo ' disabled'; else: echo ' id=star4'; endif; if($rating==2) echo " checked"; ?> />
			<label for="star4">&#9829;</label>
			<input type="radio" name="<?= $name ?>" value="1" <?php if($enable==0): echo ' disabled'; else: echo ' id=star5'; endif; if($rating==1) echo " checked"; ?> />
			<label for="star5">&#9829;</label>
			<input type="hidden" name="shop_id" value=<?= $shop_id ?>>
			<input type="hidden" name="button" value="button" ?>
		</div>
	</form>
