<?php ?>
<?php 
echo $this->Form->create('Searches',['url' => ['action' => 'index'],'inputDefaults'=>['label'=>false,'div'=>false]]); 
?>

		<div class="element input">
<?php

//キーワード
echo $this->Form->control('word', array(
	'type' => 'text',
	'maxlength' => false,
	'placeholder' => 'Free word , #',
	'class' => 'search_box_line',
	'id' => 'ac_word',
	'label' => false
));
?>
		</div>
		<div class="element input">
<?php

//ショップタイプ
echo $this->Form->control('type', array(
	'options' => $typename,
    'empty' => 'Shop type',
    'class' => 'search_box_select',
	'id' => 'login_form_select',
	'label' => false
));

?>
		</div>
		<div class="element input">
<?php

//エリア
echo $this->Form->control('area' , array(
	'type' => 'text',
	'maxlength' => false,
	'placeholder' => 'Area',
    'class' => 'search_box_line',
	'id' => 'login_form_txt',
	'label' => false
));
?>

		</div>
		<div class="element button">
<?php 
echo $this->Form->submit('検索',array('class'=>'search_btn','name'=>'search_button'));
?>
	
		</div>

<?php 
// echo $this->Form->submit('地図',array('class'=>'btn btn-default','name'=>'map'));
echo $this->Form->end();
?>






