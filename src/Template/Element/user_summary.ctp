<?php
	//FollowUserのEntityに引き渡し
    $user_data->FollowerId = $user_data->user_id;
    if(!empty($LoginUserFollow)){
    	$user_data->LoginUserFollow = $LoginUserFollow;
    }
?>
    <div class="user_infor">
        <div class="icon_area">
            <div class="icon">
                <?php echo $this->Html->image('icon_user',['alt'=> 'User']); ?>
            </div>
            <div class="sex">
                <?php if($user_data->sex_id === "1" or $user_data->sex_id === "2") echo $user_data->sex_typename;  ?>
            </div>
        </div>
        <div class="detail">
            <?= "<ul><li>"; ?>
            <?= $this->Html->link(__($user_data->nickname), ['controller' => 'Users', 'action' => '/',$user_data->username,'div'=>false]); ?>
            <div class="address">
                <?= $user_data->address ?>
            </div>
            <hr />
            <table>
                <tr><td>お気に入りショップ</td><td><?= h($user_data->FollowShopCount) ?>店(共通:<?= ($user_data->FollowShopCommonCount) ?>店)</td></tr>
                <tr><td>フォローユーザ</td><td><?= h($user_data->FollowUserCount) ?>人(共通:<?= h($user_data->FollowUserCommonCount) ?>人)</td></tr>
                <tr><td>フォロワー</td><td><?= h($user_data->FollowerUserCount) ?>人(共通:<?= h($user_data->FollowerUserCommonCount) ?>人)</td></tr>
            </table>
        </div>
    </div>