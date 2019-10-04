<?php //②フォローユーザ?>
<div class="contents user">
    <article class="contain">
        <div class="contain_inner bg-white">
            <?php echo $this->element('user_header', ["type" => "user"]); ?>

            <div class="user_flame">
                <?php
                //フォローしているユーザを先に表示
                foreach ($FollowedUsersIn as $FollowerIn) {
                    echo $this->element('user_summary', ["user_data" => $FollowerIn]);
                }

                //フォローしていないユーザを後に表示
                foreach ($FollowedUsersNotIn as $FollowerIn) :
                    echo $this->element('user_summary', ["user_data" => $FollowerIn]);
                endforeach;
                ?>
            </div>
        </div>
    </article>
</div>
