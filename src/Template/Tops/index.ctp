<?php
// 未使用ファイル
?>

<div class="contents top shop">

    <div class="col-sm-12">
    <?php 
    //重複するお店は表示させないための処理。本当はcontrollerで処理したかったけど断念してviewにて実行
    $duplex_check = array();
    $i = 0;
    foreach ($favoriteDatas as $favoriteData):
        if(! in_array($favoriteData->user_id,$duplex_check)):
            array_push($duplex_check,$favoriteData->user_id);
            $i = $i + 1;

            //ショップの写真パスを取得
            $dir = SHOPPHOTO_UPLOADDIR . '/photo_shop/' . $favoriteData->shop_id . '/';
            $photo_list = glob($dir . '*');
            $photoShop = null;
            if(!empty($photo_list)){
                $photoShop_fullpath = max($photo_list); //最新写真のみ抽出
                $photoShop_array = explode('/',$photoShop_fullpath); //サーバパスの取得となるため、最後のファイル名だけを取得
                $photoShop = "https://fave-jp.info/img/photo_shop/" . $favoriteData->shop_id . "/" . end($photoShop_array);
            }
    ?>
            <article class="content">
                <div class="shop_detail">
                    <div class="name">
                        <h2><?php echo $this->Html->link($favoriteData->shopname, array('controller' => 'shops', 'action' => '/'. $favoriteData->shop_id)); ?>
                        </h2>

                        <div class="type">
                            <?php echo h($favoriteData->typename); ?> | <?php echo h($favoriteData->pref.$favoriteData->city.$favoriteData->ward);?>
                        </div>
                    </div>
                    <?php if(!empty($photoShop)): ?>
                        <div class="photo">
                        <?=  $this->Html->link(
                            $this->Html->image($photoShop,array("class" => "img-fluid")),
                            array('controller' => 'shops', 'action' => '/'. $favoriteData->shop_id),
                            array('escape' => false)); ?>
                        </div>
                    <?php endif; ?>
                    
                    
                    <div class="introduction">
                        <?php echo nl2br(h($favoriteData->introduction)); ?>
                    </div>
                    <div class="follow">
                        <?php
                            echo $this->Form->create('Follow');
                            echo $this->Form->submit('フォローする',array('id'=>'button'.$i ,'class'=>'follow_button','data-follower'=>$favoriteData->user_id , 'data-button'=>'button'.$i));
                            echo $this->Form->end();
                        ?>
                    </div>
                </div>
            </article>

    <?php
        endif;
    endforeach;
    ?>
    </div>
</div>
