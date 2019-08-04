<?php ?>
<?= $this->Form->create('Searches',['type' => 'get','url' => ['action' => 'index'],'inputDefaults'=>['label'=>false,'div'=>false],'templates' => $template]);?>

		<!-- <div class="element input"> -->
<?php

//キーワード
//echo $this->Form->control('word', [
// 	'type' => 'text',
// 	'maxlength' => false,
// 	'placeholder' => 'キーワード',
// 	'class' => 'search_box_line',
// 	'id' => 'ac_word',
// 	'label' => false
// ]);
?>
		<!-- </div> -->
		<div class="element input">
<?php
//ショップタイプ
echo $this->Form->control('shoptype', [
	'options' => $typename,
    'empty' => '選択してください',
	'label' => 'ショップタイプ',
]);

?>
		</div>
		<div class="element input">
<?php
//エリア
echo $this->Form->control('area' , [
	'maxlength' => false,
	'placeholder' => '例)名古屋市',
	'label' => '住所'
]);
?>

		</div>
		<div class="element button">
<?php
	echo $this->Form->button('検索',['class'=>'search_btn']);
?>

		</div>

<?php
// echo $this->Form->submit('地図',array('class'=>'btn btn-default','name'=>'map'));
echo $this->Form->end();
?>






