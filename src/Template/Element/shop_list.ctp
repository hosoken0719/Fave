<?php
foreach($Query as $shopData) :
    //ショップの写真パスを取得
    $dir = SHOPPHOTO_UPLOADDIR . '/photo_shop/' . $shopData->shop_id .'/';
    $photo_list = glob($dir . '*.png');
    $photoShop = null;
    if(!empty($photo_list)){
        $photoShop_fullpath = max($photo_list); //最新写真のみ抽出
        $photoShop_array = explode('/',$photoShop_fullpath); //サーバパスの取得となるため、最後のファイル名だけを取得
        $photoShop = "https://fave-jp.info/img/photo_shop/" . $shopData->shop_id . "/thumbnail/min_" . end($photoShop_array);
    }
?>

<div class="shop_infor">
    <dl>
        <dt>
            <?php if(!empty($photoShop)): ?>
            <?= $this->Html->link(
                $this->Html->image($photoShop,array("class" => "trimming img-fluid")),
                array('controller' => 'shops', 'action' => '/'. $shopData->shop_id),
                array('escape' => false));
            ?>
            <?php else: ?>
            <?php endif; ?>

        </dt>
        <dd>
            <div class="name">
                <h2>
                    <?php echo $this->Html->link($shopData->shopname, array('controller' => 'shops', 'action' => '/'. $shopData->shop_id)); ?>
                </h2>
                <?= h($shopData->typename) ?>
                <div>
                    <?= h($shopData->pref.$shopData->address) ?>
                </div>
            </div>
            <div class="rating">
                <?= $this->element('rating',['rating'=>$shopData->shop_id,'shop_id'=>$shopData->shop_id,'enable'=>0]); ?>
            </div>
        </dd>
    </dl>
</div>
<?php endforeach; ?>