<?php ?>
<div class="col-sm-12">
<div class="login">
    <div class="box box__bg_photo">
        <div class="title"><h1>好きなお店を探そう</h1>
        <p>Find your Favorite</p>


    <?php  //"<a href='https://access.line.me/dialog/oauth/weblogin?response_type=code&client_id=1604848142&redirect_uri=https://fave-jp.info/auth&state=".rand() ."&scope=profile'>LINEでログイン</a>"; ?>
   <?php //"<a href='https://access.line.me/oauth2/v2.1/authorize?response_type=code&client_id=1604848142&redirect_uri=https://fave-jp.info/auth/line&state=".rand() ."&scope=profile'>LINEでログイン</a>"; ?>
   <?= "<a href='https://access.line.me/oauth2/v2.1/authorize?response_type=code&client_id=1604848142&redirect_uri=https://fave-jp.info/auth/line&state=2sBz1U5epdrtub6DQH26MfzhXAehaf1H55SRwTBu&scope=profile'>LINEでログイン</a>"; ?>

https://access.line.me/oauth2/v2.1/authorize?client_id=1604848142&redirect_uri=https://fave-jp.info/auth/line&state=2sBz1U5epdrtub6DQH26MfzhXAehaf1H55SRwTBu&scope=profile'>
https://access.line.me/oauth2/v2.1/authorize?client_id=1604848142&redirect_uri=https://fave-jp.info/auth/line/oauth2callback&state=803e0ce8146b8c0eae3d64470777f96d311ec732
https://access.line.me/oauth2/v2.1/authorize?client_id=1604848142&redirect_uri=https%3A%2F%2Ffave-jp.info%2Fauth%2Fline%2Foauth2callback&response_type=code&

        <?php echo $this->Form->create('User'); 


            //Username
            echo $this->Form->controle('username' , array(
            	'type' => 'text',
            	'id' => 'login_form',
            	'label' => 'Account Name',
            	'maxlength' => false,
            	'placeholder' => '',
            ));

            //Password
            echo $this->Form->controle('password' , array(
            	'type' => 'text',
            	'id' => 'login_form',
            	'label' => 'Password',
            	'maxlength' => false,
            	'placeholder' => '',
            ));
			

			// 	//後から確認する事!!
			// 	//echo $this->Form->end('Sign in', array(
			// 	//'class' => 'btn btn-lg btn-primary btn-block btn-signin',
			// 	//));
			
			echo $this->Form->submit('Sign in',['class' => 'btn']);
			echo $this->Form->end();
			
			?>

        <a href="#" class="forgot-password">
            パスワードを忘れた場合
		</a>
		<div id="remember" class="checkbox">
            <label>
                <input type="checkbox" value="remember-me"> Remember me
            </label>
        </div> 
        <div class="text-center">
		<?php 
		      // echo $this->Html->link('新規登録', '/add');
		?>
		</div></div>
    </div>
<section class="box">
<h2>About</h2>
<h3></h3>
</section>
