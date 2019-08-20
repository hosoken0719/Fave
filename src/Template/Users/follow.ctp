<?php ?>
<div class="contents top shop">

    <div class="col-sm-12">

    	<h2>フォローショップ</h2>
    	<?php
    		foreach ($FollowedShops as $key => $FollowedShop) {
    			echo $FollowedShop['shopname'];
    			echo $FollowedShop['rating'];
    			echo "<br />";
    		}
    		foreach ($UnFollowShops as $key => $UnFollowShop) {
    			echo $UnFollowShop['shopname'];
    			echo "<br />";
    		}
    	?>

        <h2>フォローユーザ</h2>
        <?php
            foreach ($FollowedUsers as $FollowedUser) {
                echo $FollowedUser->username;
            }
        ?>

        <h2>フォロワー</h2>
        <?php
            foreach ($FollowerUsers as $FollowerUser) {
                echo $FollowerUser->username;
            }
        ?>

    </div>
</div>
