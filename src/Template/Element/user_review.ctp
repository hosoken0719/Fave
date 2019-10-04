<?php
	//FollowのEntityに引き渡し
    $user_data->LoginUserId = $login_user_id; //ログインユーザID
    $user_data->FollowerId = $user_data->user_id; //表示するユーザID
    $user_data->FollowerShopId = $shopData->shop_id; //表示しているショップID
    $user_data->LoginUserFollow = $LoginUserFollow;
    
?>
    <div class="user_review">
        <div class="user_wrap">
            <div class="user_infor">
                <div class="icon_area">
                    <div class="avatar">
                        <?php
                            echo $this->Html->image($user_data->Avatar,['alt'=> $user_data->username,'class'=>'circle']);
                        ?>
                    </div>
                    <div class="sex">
                        <?php if($user_data->sex_id === 1 or $user_data->sex_id === 2) echo $user_data->sex_typename;  ?>
                    </div>
                    <div class="is_follow">
                        <?php
                            if($user_data->IsUserFollow === 1){
      echo "フォロー中";
    } ?>
                    </div>

                </div>
                <div class="follow_area">
                    <?php //nicknameの登録がなければ、ユーザネームを表示
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
                        <tr><th>お気に入り</th><td><?= h($user_data->FollowShopCount) ?>店(共通:<?= ($user_data->FollowShopCommonCount) ?>店)</td></tr>
                        <tr><th>フォロー</th><td><?= h($user_data->FollowUserCount) ?>人(共通:<?= h($user_data->FollowUserCommonCount) ?>人)</td></tr>
                        <tr><th>フォロワー</th><td><?= h($user_data->FollowerUserCount) ?>人(共通:<?= h($user_data->FollowerUserCommonCount) ?>人)</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="review_wrap">
            <div class="review_area">
                <div class="rating">
                    お気に入り度：<?= $this->element('rating',['rating'=>$user_data->UserReview->rating,'shop_id'=>$shopData->shop_id,'enable'=>0]) ?>
                </div>
                <div class="review">
                    <?= nl2br(h($user_data->UserReview->review)); ?>
                </div>
            </div>
        </div>
    </div>
    <hr>