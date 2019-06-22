<?php ?>
<div class="search_box">
	<div class="block">
<?php 
echo $this->Form->create('Searches',array('url' => '/searches','inputDefaults'=>array('label'=>false,'div'=>false))); 
?>

		<div class="element input">
<?php

//キーワード
echo $this->Form->input('word', array(
	'type' => 'text',
	'maxlength' => false,
	'placeholder' => 'キーワード , #',
	'class' => 'search_box_line',
	'id' => 'login_form_txt'
));
?>
		</div>
		<div class="element input">
<?php

//ショップタイプ
echo $this->Form->input('type', array(
	'type' => 'select',
	'options' => $shoptype,
    'empty' => 'Shop type',
    'class' => 'search_box_select',
	'id' => 'login_form_select'
));

?>
		</div>
		<div class="element input">
<?php

//エリア
echo $this->Form->input('area' , array(
	'type' => 'text',
	'maxlength' => false,
	'placeholder' => 'Area',
    'class' => 'search_box_line',
	'id' => 'login_form_txt'
));
?>

		</div>
		<div class="element button">
<?php 
echo $this->Form->submit('検索',array('class'=>'search_btn','name'=>'list'));
?>
	
		</div>
	</div>
</div>
<div>
<?php 
// echo $this->Form->submit('地図',array('class'=>'btn btn-default','name'=>'map'));
echo $this->Form->end();
?>
</div>



<!-- タブメニュー
 activeクラスに、クリック済のデザインを設定したcssを指定 -->




