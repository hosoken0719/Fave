<?php
	//FollowUserのEntityに引き渡し
    $user_data->FollowerId = $user_data->user_id;
    if(!empty($LoginUserFollow)){
    	$user_data->LoginUserFollow = $LoginUserFollow;
    }
?>
    <div class="user_infor">
        <dl>
            <dt class="icon_area">
                <div class="icon">
                    <?php echo $this->Html->image($user_data->avatar,['alt'=> 'User','class'=>'circle']); ?>
                </div>
                <div class="sex">
                    <?php if($user_data->sex_id === "1" or $user_data->sex_id === "2") echo $user_data->sex_typename;  ?>
                </div>
            </dt>
            <dd class="detail">
                <ul><li>
                <?php
                   if(!empty($user_data->nickname)){
                        echo $this->Html->link(__($user_data->nickname), ['controller' => 'Users', 'action' => '/',$user_data->username,'div'=>false]);
                    }else{
                        echo $this->Html->link(__($user_data->username), ['controller' => 'Users', 'action' => '/',$user_data->username,'div'=>false]);
                    }
                ?>
                <div class="address">
                    <?= $user_data->address ?>
                </div>
                <hr />
	                <table>
                        <?php if($this->request->getSession()->read('Auth.User.id')): ?>
	                       <tr><td>お気に入り</td><td><?= h($user_data->FollowShopCount) ?>店(共通:<?= h($user_data->FollowShopCommonCount) ?>店)</td></tr>
                        <?php else: ?>
                           <tr><td>お気に入り</td><td><?= h($user_data->FollowShopCount) ?>店</td></tr>
                        <?php endif; ?>


                        <?php if($this->request->getSession()->read('Auth.User.id')): ?>
	                       <tr><td>フォロー</td><td><?= h($user_data->FollowUserCount) ?>人(共通:<?= h($user_data->FollowUserCommonCount) ?>人)</td></tr>
                        <?php else: ?>
                            <tr><td>フォロー</td><td><?= h($user_data->FollowUserCount) ?>人</td></tr>
                        <?php endif; ?>

                        <?php if($this->request->getSession()->read('Auth.User.id')): ?>
	                       <tr><td>フォロワー</td><td><?= h($user_data->FollowerUserCount) ?>人(共通:<?= h($user_data->FollowerUserCommonCount) ?>人)</td></tr>
                        <?php else: ?>
                            <tr><td>フォロワー</td><td><?= h($user_data->FollowerUserCount) ?>人</td></tr>
                        <?php endif; ?>
                </table>
            </dd>
        </dl>
    </div>