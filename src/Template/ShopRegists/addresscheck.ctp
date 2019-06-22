<?php ?>

<div class="contents regist">
	<div class="col-sm-12">
		<article class="content">
			<div class="form">

<?= $this->Form->create('',['class'=>'next','url' => ['controller' => 'ShopRegists', 'action' => 'mapcheck']]) ?>

    <fieldset>
       <legend><?= '住所確認' ?></legend>

       <p class="notice">県名／市区郡／町村・番地／ビル名・階数が正しいか確認してください。</p>

        <?php
            echo "<div class='pref'><div class='require'></div></div>";
            echo $this->Form->control('pref', ['label' => '県名','name' => 'pref', 'value' => $pref, "autocomplete" => "off"]);
            echo "<div class='city'><div class='require'></div></div>";
            echo $this->Form->control('city', ['label' => '市区郡','name' => 'city', 'value' => $city, "autocomplete" => "off"]);
            echo "<div class='town'><div class='require'></div></div>";
            echo $this->Form->control('town', ['label' => '町村・番地','name' => 'town', 'value' => $town, "autocomplete" => "off"]);
            echo $this->Form->control('building', ['label' => 'ビル名・階数', 'value' => $building, "autocomplete" => "off"]);
            echo $this->Form->button(__('進む'));
       echo $this->Form->end();


?>
    </fieldset>

</div>
</article>
</div>
</div>

<script type="text/javascript">
    
$(".next").submit(function(){
  var chkForm = true;
  if ($("input[name='pref']").val() == ''){
      $('.pref .require').text("*必須項目です"); 
      chkForm = false;
  }else{
      $('.pref .require').text(''); 
  }

  if ($("input[name='city']").val() == ''){
      $('.city .require').text("*必須項目です"); 
      chkForm = false;
  }else{
      $('.city .require').text(''); 
  }

  if ($("input[name='town']").val() == ''){
      $('.town .require').text("*必須項目です"); 
      chkForm = false;
  }else{
      $('.town .require').text(''); 
  }

  return chkForm;
});


</script>