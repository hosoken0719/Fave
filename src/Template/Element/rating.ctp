<?php if(!isset($name)){$name = "rating";} ?>
	<form type="get" action="#">
		<div class="evaluation">
			<input type="radio" name="<?= $name ?>" value="5"  <?php  if($rating==5) echo " checked"; ?> />
			<label>&#9829;</label>
			<input type="radio" name="<?= $name ?>" value="4"  <?php if($rating==4) echo " checked"; ?> />
			<label>&#9829;</label>
			<input type="radio" name="<?= $name ?>"value="3" <?php  if($rating==3) echo " checked"; ?> />
			<label>&#9829;</label>
			<input type="radio" name="<?= $name ?>" value="2"  <?php  if($rating==2) echo " checked"; ?> />
			<label>&#9829;</label>
			<input type="radio" name="<?= $name ?>" value="1" <?php if($rating==1) echo " checked"; ?> />
			<label>&#9829;</label>
			<input type="hidden" name="shop_id" value=<?= $shop_id ?>>
			<input type="hidden" name="button" value="button" ?>
		</div>
	</form>
