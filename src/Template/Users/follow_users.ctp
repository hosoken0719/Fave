<?php //②フォローユーザ?>
<div class="contents user">
    <div class="col-sm-12">

    <?php echo $this->element('user_header', ["type" => "user"]); ?>

<div class="user_flame">
            <?php
                foreach ($FollowedUsersIn as $FollowerIn) {

                    $FollowerIn->FollowerId = $FollowerIn->user_id;
                    $FollowerIn->TableEntity = $TableEntity;
                    $FollowerIn->LoginUserFollow = $LoginUserFollow;

                    echo "<ul><li>";
                    echo $this->Html->link(__($FollowerIn->nickname), ['controller' => 'Users', 'action' => '/',$FollowerIn->username,'div'=>false]);
                    echo "</li><li>住まい:{$FollowerIn->address}</li>";
                    echo "<li>性別:{$FollowerIn->sex}</li>";
                    echo "<li>フォローショップ:{$FollowerIn->FollowShopCount}店";
                    echo "(共通:{$FollowerIn->FollowShopCommonCount}店)</li>";
                    echo "<li>フォローユーザ:{$FollowerIn->FollowUserCount}人";
                    echo "(共通:{$FollowerIn->FollowUserCommonCount}人)</li>";
                    echo "<li>フォロワー:{$FollowerIn->FollowerUserCount}人";
                    echo "(共通:{$FollowerIn->FollowerUserCommonCount}人)</li>";
                    echo "</ul>ss";

                            // <li>お気に入りショップ：{$FollowerIn->FollowShopCount}店(共通:{$FollowerIn->FollowShopCommonCount}店)</li>
                            // <li>フォローユーザ:{$FollowerIn->FollowUserCount}人(共通:{$FollowerIn->FollowUserCommonCount}人)</li>
                            // <li>フォロワー:{$FollowerIn->FollowerUserCount}人(共通:{$FollowerIn->FollowerUserCommonCount}人)</li>
                }

                foreach ($FollowedUsersNotIn as $FollowerIn) :
                    $FollowerIn->FollowerId = $FollowerIn->user_id;
                    $FollowerIn->LoginUserFollow = $LoginUserFollow;
                    ?>
                 <div class="user_infor">
                <div class="icon_area">
                    <div class="icon"><?php echo $this->Html->image('icon_user',['alt'=> 'User']); ?></a></h1></div>
                    <div class="sex"><?php if($FollowerIn->sex_id === "1" or $FollowerIn->sex_id === "2") echo "{$FollowerIn->sex_typename}";  ?></div>
                    
                </div>
                <div class="detail">
                    <?= "<ul><li>"; ?>
                    <?= $this->Html->link(__($FollowerIn->nickname), ['controller' => 'Users', 'action' => '/',$FollowerIn->username,'div'=>false]); ?>
                    <div class="address"><?= $FollowerIn->address ?>天白区天白町</div><hr />
                    <?=  "
                            <table>
                            <tr><td>お気に入りショップ</td><td>{$FollowerIn->FollowShopCount}店(共通:{$FollowerIn->FollowShopCommonCount}店)</td></tr>
                            <tr><td>フォローユーザ</td><td>{$FollowerIn->FollowUserCount}人(共通:{$FollowerIn->FollowUserCommonCount}人)</td></tr>
                            <tr><td>フォロワー</td><td>{$FollowerIn->FollowerUserCount}人(共通:{$FollowerIn->FollowerUserCommonCount}人)</td></tr></table>
                        ";
                    ?>
                </div>
            </div>
                <?php endforeach; ?>
</div>
    </div>
</div>
