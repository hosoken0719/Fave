<?php ?>
<div class="contents top shop">

    <div class="col-sm-12">

    	<h2>フォローショップ</h2>


        <h2>フォローユーザ</h2>
        <?php
            foreach ($FollowedUsers as $FollowedUser) {
                echo $FollowedUser->username;
            }
        ?>

        <h2>フォロワー</h2>

    </div>
</div>
