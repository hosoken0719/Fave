<?php ?>
<!DOCTYPE html>
<html>
  <head>
    <?php echo $this->Html->charset(); ?>

     <title><?php if(!empty($title)){echo $title;}else{echo "Fave - まだ、知らないお店へ";} ?></title>
     <meta name="description" content="<?php if(!empty($description)){echo $description;}else{echo "お店探しのためのSNS";} ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0", content="width=device-width" />
    <!-- <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css">
    <?= $this->Html->script('swiper.min.js'); ?>
    <?= $this->Html->css('swiper.min.css'); ?>
    <?php //echo $this->Html->css('bootstrap.min.css'); ?>
    <?= $this->Html->css('main'); ?>
    <?= $this->Html->script('follow.js'); ?>
    <?= $this->Html->script('autocomplete.js'); ?>

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
<script>
$(function() {
      $header = $('header');
      scrollHeight = 'scroll-height';
      $headerLogo = $('#header_logo');
      scrollHeaderLogo = 'scroll-header_logo';

  $(window).on('load scroll', function() {
    if ( $(this).scrollTop() > 100 ) {
      $header.addClass(scrollHeight);
    } else {
      $header.removeClass(scrollHeight);
      $headerLogo.removeClass(scrollHeaderLogo);
    }
  });
});
</script>


  </head>
  <?php if(!empty($header_link)): ?>
    <body <?= "class='header-" .$header_link ."'"?>>
  <?php else: ?>
    <body>
  <?php endif; ?>
    <header>
      <div id="header">
        <div class="header_inner">
          <div id="header_logo">
            <h1><a href="/"><?php echo $this->Html->image('logo.svg',['alt'=> 'Fave']); ?></a></h1>
          </div>
          <?php if($this->request->getSession()->read('Auth.User')): //ログインしている場合?>
          <nav>
            <div id="header-link">
              <div class="header-link-follow left"><?= $this->Html->link(__('お気に入り'), ['plugin'=>false,'controller' => 'Follows', 'action' => 'index']) ?></div>
              <div class="header-link-search left"><?= $this->Html->link(__('探す'), ['plugin'=>false,'controller' => 'Searches', 'action' => 'index']) ?></div>
              <div class="header-link-regist left"><?= $this->Html->link(__('お店登録'), ['plugin'=>false,'controller' => 'ShopRegists', 'action' => 'index']) ?></div>
              <div class="header-link-account right"><?= $this->Html->link(__('アカウント'), ['plugin'=>false,'controller' => 'Accounts', 'action' => 'index', '_full' => true]) ?></div>
            </div>
            <div id="header-link">
              <div class="header-link-home home_button left"><?= $this->Html->image('home.svg',['url'=>['plugin'=>false,'controller'=>'users','action'=>'index']]) ?><br />ホーム</div>
              <div class="header-link-follow home_button left"><?= $this->Html->image('follow.svg',['url'=>['plugin'=>false,'controller'=>'Follows','action'=>'index']]) ?><br />お気に入り</div>
              <div class="header-link-search home_button left"><?= $this->Html->image('search.svg',['url'=>['plugin'=>false,'controller'=>'Searches','action'=>'index']]) ?><br />探す</div>
              <div class="header-link-regist home_button left"><?= $this->Html->image('regist.svg',['url'=>['plugin'=>false,'controller'=>'ShopRegists','action'=>'index']]) ?><br />お店登録</div>
              <div class="header-link-account home_button left"><?= $this->Html->image('account.svg',['url'=>['plugin'=>false,'controller'=>'accounts','action'=>'index','_full' => true]]) ?><br />アカウント</div>
            </div>
          </nav>
  <?php else: //ログインしていない場合 ?>
          <nav>
            <div id="header-link">
              <div class="header-link-search left"><?= $this->Html->link(__('探す'), ['plugin'=>false,'controller' => 'Searches', 'action' => 'index']) ?></div>
              <div class="header-link-login left"><?= $this->Html->link(__('ログイン'), ['plugin'=>false,'controller' => 'login', 'action' => 'index']) ?></div>

            </div>
            <div id="header-link">
              <div class="header-link-home home_button left"><?= $this->Html->image('home.svg',['url'=>['plugin'=>false,'controller'=>'users','action'=>'index']]) ?><br />ホーム</div>
              <div class="header-link-search home_button left"><?= $this->Html->image('search.svg',['url'=>['plugin'=>false,'controller'=>'Searches','action'=>'index']]) ?><br />探す</div>
              <div class="header-link-account home_button left"><?= $this->Html->image('account.svg',['url'=>['plugin'=>false,'controller'=>'accounts','action'=>'index','_full' => true]]) ?><br />ログイン
            </div>
          </nav>
  <?php endif; ?>
        </div>
      </div>
    </header>
    <main>
    <div class="main">
        <?php echo $this->fetch('content'); ?>
    </div>
    </main>
    <footer>
      ©2019 Fave Creative
    </footer>
  <?php
  if(!empty($locate_json)):
    echo $this->element('googlemap');
  endif;
  ?>
<?= $this->fetch('script') ?>

  </body>
</html>