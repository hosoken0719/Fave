<?php ?>

<div class="contents regist">
	<div class="col-sm-12">
		<article class="content">
			<div class="form">

<?= $this->Form->create('',['class'=>'next','url' => ['controller' => 'ShopRegists', 'action' => 'addresscheck']]) ?>

    <fieldset>
        <legend><?= 'STEP2/3' ?></legend>




        <?php

            //ショップタイプ
            echo $this->Form->control('shoptype', array(
                'options' => $typename,
                'empty' => 'Shop type',
                'class' => 'search_box_select',
                'id' => 'login_form_select',
                'label' => false
            ));

            echo "<div class='require'>"; 
            if($this->request->getQuery('error') == 1){
                echo "住所が間違っています。";
            }
            echo "</div>";
            echo $this->Form->control('address', ['label' => '県市区町村・番地(必須)','name'=>'address','value'=>$address]);
            echo $this->Form->control('building', ['label' => 'ビル名・階数','value'=>$building]);

            echo $this->Form->button('進む');
        ?>
        <?= $this->Form->end() ?>

        <?= $this->Form->create('',['class'=>'prev' , 'url' => ['action' => 'index']]) ?>
        <?= $this->Form->button('戻る',['class'=>'back']); ?>

    <?= $this->Form->end() ?>
    </fieldset>

</div>
</article>
</div>
</div>

<script type="text/javascript">
    
$(".next").submit(function(){
    var chkSubmit = true;
    if ($("input[name='address']").val() == ''){
        $('.require').text("*必須項目です"); 
        chkSubmit = false;
    }
    // });
    return chkSubmit;
});
</script>