<?php ?>
<div class="contents top shop">

    <div class="col-sm-12">

    	<h2>フォローショップ</h2>


        <h2>フォローユーザ</h2>


        <h2>フォロワー</h2>
        <?php
            foreach ($FollowerUsers as $FollowerUser) {
                echo $FollowerUser->username;
            }
        ?>

    </div>
</div>
