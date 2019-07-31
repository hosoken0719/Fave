<?php //③フォロワー?>
<div class="contents user">
    <article class="contain-white">
            <div class="contain_inner">
            <?php echo $this->element('user_header', ["type" => "follower"]); ?>

            <div class="user_flame">
                <?php
                //フォローしているユーザを先に表示
                foreach ($FollowerUsersIn as $FollowerIn) {
                    echo $this->element('user_summary', ["user_data" => $FollowerIn]);
                }
                
                //フォローしていないユーザを後に表示
                foreach ($FollowerUsersNotIn as $FollowerIn) :
                    echo $this->element('user_summary', ["user_data" => $FollowerIn]);
                endforeach;
                ?>
            </div>
    </article>
</div>
