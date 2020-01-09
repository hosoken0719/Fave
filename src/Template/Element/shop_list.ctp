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

    <?php if($this->name==='Users'): //ユーザページのお気に入りお店の場合は、表示ユーザのレーティングを表示 ?>
                <div class="rating d-inline">
                <?php if($this->request->getSession()->read('Auth.User')):
                    echo $this->Html->image('display_users.svg');
                    echo $this->element('rating',['rating'=>round($shopData->rating),'shop_id'=>$shopData->shop_id,'enable'=>0]);
                    echo number_format(round($shopData->rating,1),1);
                endif; ?>
                </div>
              <br />
    <?php endif; ?>

    <?php //ログインユーザがフォローしているユーザの平均レーティングを表示 ?>
                <div class="rating d-inline">
                <?php if($this->request->getSession()->read('Auth.User')):
                    echo $this->Html->image('followed_users.svg');
                    echo $this->element('rating',['rating'=>round($shopData->avg_followed),'shop_id'=>$shopData->shop_id,'enable'=>0]);
                    echo number_format(round($shopData->avg_followed,1),1);
                    if($shopData->cnt_followed > 0) echo " (".h($shopData->cnt_followed)."人)";
                ?>
                </div>
                <br />
               <?php endif; ?>
    <?php if($this->name==='Searches'): //検索結果ページの場合は、全員の平均レーティングを表示 ?>
                <div class="rating d-inline all">
                <?php
                    echo $this->Html->image('all_users.svg');
                    echo $this->element('rating',['rating'=>round($shopData->avg_all),'shop_id'=>$shopData->shop_id,'enable'=>0]);
                    echo number_format(round($shopData->avg_all,1),1);
                    if($shopData->cnt_all > 0) echo " (".h($shopData->cnt_all)."人)";
                ?>
                </div>
    <?php endif; ?>
        </dd>
    </dl>
</div>

<?php endforeach; ?>