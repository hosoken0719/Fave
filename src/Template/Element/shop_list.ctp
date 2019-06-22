<?php foreach($Query as $shop) : ?>

        <div class="shop_infor">
            <div class="shop_thumbnail">
                <?= $this->Html->link(
                    $this->Html->image($shop->getPhotoShopThumbnail($shop->shop_id),array("class" => "trimming img-fluid")),
                    ['controller' => 'shops', 'action' => '/'. $shop->shop_id],
                    ['escape' => false]);
                ?>
            </div>
            <div class="shop_detail">
                <div class="name">
                    <h2><?= $this->Html->link($shop['shopname'], ['controller' => 'shops', 'action' => '/'. $shop->shop_id]); ?></h2>
                    <?= h($shop['typename']) ?>
                    <div> <?= h($shop->pref.$shop->city.$shop->ward) ?></div>
                </div>
                <div class="rating">
                    <?= $this->element('rating',['rating'=>$shop->rating,'shop_id'=>$shop->shop_id,'enable'=>0]); ?>
                </div>
            </div>
            <?php if($isFollowed === 1){
                echo 'フォロー済み';
                }else{
                    echo 'NG';
                }
            ?>
        </div>
<?php endforeach; ?>