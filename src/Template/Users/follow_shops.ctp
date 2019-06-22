<?php //①フォローショップ?>
<div class="contents user">

    <div class="col-sm-12">
<?php echo $this->element('user_header' , ["type" => "shop"]); ?>

         <div class="shop_flame">

<?php
//共通のフォローショップを表示
echo $this->element('shop_list',['Query' => $FollowShopsIn,'isFollowed'=>1]);
//表示ユーザのみフォローしているショップを表示
echo $this->element('shop_list',['Query' => $FollowShopsNotIn,'isFollowed'=>0]);
?>
        </div>
    </div>
</div>
