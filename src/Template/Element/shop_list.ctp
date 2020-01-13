<?php foreach($Query as $shopData) :?>

<div class="shop_list">
    <dl>
        <dt class="thumbnail">
            <?php
            if(!empty($shopData->thumbnail)){
                echo $this->Html->link(
                $this->Html->image('shop_photos/'.$shopData->shop_id.'/thumbnail/min_'.$shopData->thumbnail,array("class" => "trimming img-fluid")),
                array('controller' => 'shops', 'action' => '/'. $shopData->shop_id),
                array('escape' => false));
            }else{
                echo $this->Html->link(
                $this->Html->image('no_image.jpg',array("class" => "trimming img-fluid")),
                array('controller' => 'shops', 'action' => '/'. $shopData->shop_id),
                array('escape' => false));
            } ?>
        </dt>
        <dd>
            <div class="detail">
                <div class="category">
                    <?= h($shopData->typename) ?>
                </div>

                <h2 class="name">
                    <?php echo $this->Html->link(h($shopData->shopname) . ' ' . h($shopData->branch), array('controller' => 'shops', 'action' => '/'. $shopData->shop_id)); ?>
                </h2>
                <div class="address">
                    <?= h($shopData->pref.$shopData->address) ?>
                </div>
            </div>


    <?php //ログインユーザがフォローしているユーザの平均レーティングを表示 ?>
                <?php if($this->request->getSession()->read('Auth.User')): ?>
                <div class="rating d-inline">
                    <?php //フォロー済みのrating
                    echo $this->Html->link(
                    $this->Html->image('followed_users.svg'). $this->element('rating',['rating'=>round($shopData->avg_followed),'shop_id'=>$shopData->shop_id,'enable'=>0]),
                        [
                            'controller' => 'shops',
                            'action' => '/'.$shopData->shop_id.'/favorited_follow'
                        ],
                        ['escape' => false]);
                    if($shopData->cnt_followed > 0) echo number_format(round(h($shopData->avg_followed),1),1). " (".h($shopData->cnt_followed)."人)";
                    ?>
                </div>
                <br />
                <? endif; ?>
                <div class="rating d-inline all">
                <?php
                    echo $this->Html->link(
                    $this->Html->image('all_users.svg'). $this->element('rating',['rating'=>round($shopData->avg_all),'shop_id'=>$shopData->shop_id,'enable'=>0]),
                        [
                            'controller' => 'shops',
                            'action' => '/'.$shopData->shop_id.'/favorited_all'
                        ],
                        ['escape' => false]);
                    if($shopData->cnt_all > 0) echo number_format(round($shopData->avg_all,1),1)." (".h($shopData->cnt_all)."人)";
                ?>
                </div>
        </dd>
    </dl>
</div>

<?php endforeach; ?>