<?php ?>

<div class="contents regist">
    <article class="contain-white">
    <div class="contain_inner">
            <div class="form confirm">






    <div class=duplex_shops>
        <legend>類似するお店が見つかりました</legend>
        <p>重複しているかどうかをご確認の上、登録を続けてください。</p>
        <ul>
            <?php 
            foreach($duplex_shops as $id => $data):
                echo "<li>";
                echo $this->Html->link($data, 
                    ['controller' => 'shops',
                    'action' => $shop_id[$id]],
                    ['target' => '_blank']);
                echo "</li>";
            endforeach;
            ?>
        </ul>
        <?php
        echo $this->Form->create('',['url' => ['action' => 'confirm']]);
        echo $this->Form->button(__('登録を続ける'));
        echo $this->Form->end();
        ?>
        <?php
        echo $this->Form->create('',['class'=>'prev' , 'url' => ['action' => 'index']]);
        echo $this->Form->button('戻る',['class'=>'back']);
        echo $this->Form->end();
        ?>
    </div>

</div>
</div>
</article>
</div>
