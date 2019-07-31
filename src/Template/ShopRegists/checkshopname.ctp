<?php ?>

<div class="contents regist">
	<div class="col-sm-12">
		<article class="content">
			<div class="shop_detail">


<?php


    echo "<div class=duplex_shops>";
    echo "<p>類似するお店が見つかりました。</p>";
    echo "<p>重複しているかどうかをご確認の上、登録を続けてください。</p>"; 
    echo "<ul>";
    foreach($duplex_shops as $data):
    echo "<li>{$data}</li>";
    endforeach;
    echo "</ul>";
    echo $this->Form->create('',['url' => ['controller' => 'ShopRegists', 'action' => 'mapcheck']]);
    echo $this->Form->button(__('登録を続ける'));
            echo $this->Form->end();

    echo    $this->Form->create('',['class'=>'prev' , 'url' => ['action' => 'index']]);
    echo     $this->Form->button(__('戻る'));
            echo $this->Form->end();
    echo $this->Html->link('キャンセル',['controller' => 'ShopRegists', 'action' => '/']);
    echo "</div><hr>";
?>
</div>
</article>
</div>
</div>