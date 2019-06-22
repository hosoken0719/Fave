<?php ?>



<div class="contents search">
	<div class="col-sm-12">
		<article class="content">
			<div class="search_box shop">
				<div class="switch">
					<ul class="search_tab clearfix">
						<li class="active">ショップ</li>
						<li>ユーザ</li>
					</ul>
				</div>
				<div class="category">
					<ul class="show_category shops block">
						<li>
						<?php echo $this->element('search_box'); ?>
						</li>
					</ul>
					<ul class="user">
						<li>
							<div class="element">
							<?php
							//キーワード
							echo $this->Form->control('word', array(
								'type' => 'text',
								'maxlength' => false,
								'placeholder' => 'User Name',
								'class' => 'search_box_line',
								'id' => 'ac_user',
								'label' => false
							));?>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</article>

<?php //if(!$this->request->getData('search_button')): //検索ボタンが押されていない場合?>


<?php 
// echo $this->Form->end();
?>
<?php //endif; ?>

<?php if($this->request->getData('search_button')): //検索ボタンが押された場合?>

		<div class="">
	<div class="search_result">
		<article>
			<div class="switch">
				<ul class="display_tab clearfix">
					<li class="active">リスト表示</li>
					<li>地図表示</li>
				</ul>
			</div>
		</article>

		<!-- <div class="content"> -->
		<div class="result">
			<ul class="show_result">
				<li>
					<div class="shop_flame">
<?php 
$i = 0;
foreach($shopDatas as $shopData) : 
			$i = $i + 1;
		    //ショップの写真パスを取得
            $dir = SHOPPHOTO_UPLOADDIR . '/photo_shop/' . $shopData->shop_id . '/thumbnail/';
            $photo_list = glob($dir . '*');
            $photoShop = null;
            if(!empty($photo_list)){
                $photoShop_fullpath = max($photo_list); //最新写真のみ抽出
                $photoShop_array = explode('/',$photoShop_fullpath); //サーバパスの取得となるため、最後のファイル名だけを取得
                $photoShop = "https://fave-jp.info/img/photo_shop/" . $shopData->shop_id . "/thumbnail/" . end($photoShop_array);
            }

			?>


					<div class="shop_infor">
						<div class="shop_thumbnail">
								<?php if(!empty($photoShop)): ?>
								<?= $this->Html->link(
									$this->Html->image($photoShop,array("class" => "trimming img-fluid")),
		                            array('controller' => 'shops', 'action' => '/'. $shopData->shop_id),
		                            array('escape' => false));
		                        ?>
								<?php else: ?>
								
								<?php endif; ?>

							</div>
							<div class="shop_detail">
								<div class="name">
							 	 	<h2><?php echo $this->Html->link($shopData->shopname, array('controller' => 'shops', 'action' => '/'. $shopData->shop_id)); ?>
									</h2>
									<?= h($shopData->typename) ?>
									<div> <?= h($shopData->pref.$shopData->city.$shopData->ward) ?>
									</div>
								</div>
								<div class="rating">
								<?= $this->element('rating',['rating'=>$rating[$shopData->shop_id],'shop_id'=>$shopData->shop_id,'enable'=>0]); ?>
								</div>
							</div>
					 	</div>
<?php endforeach; ?>
</div>
				</li>
			</ul>
			<ul>
				<li>
					<div id="googlemap">
					</div>
				</li>
			</ul>
		</div>
	</div>
		</div>
	</div>
</div>


<?php endif;?>
	<script type="text/javascript">
		$(function() {
		  // ①タブをクリックしたら発動
		  $('.search_tab li').click(function() {

		    // ②クリックされたタブの順番を変数に格納
		    var index = $('.search_tab li').index(this);

		    // ③クリック済みタブのデザインを設定したcssのクラスを一旦削除
		    $('.search_tab li').removeClass('active');

		    // ④クリックされたタブにクリック済みデザインを適用する
		    $(this).addClass('active');

		    // ⑤コンテンツを一旦非表示にし、クリックされた順番のコンテンツのみを表示
		    $('.category ul').removeClass('show_category').eq(index).addClass('show_category');

		  });
		});

		$(function() {
		  // ①タブをクリックしたら発動
		  $('.display_tab li').click(function() {

		    // ②クリックされたタブの順番を変数に格納
		    var index = $('.display_tab li').index(this);

		    // ③クリック済みタブのデザインを設定したcssのクラスを一旦削除
		    $('.display_tab li').removeClass('active');

		    // ④クリックされたタブにクリック済みデザインを適用する
		    $(this).addClass('active');

		    // ⑤コンテンツを一旦非表示にし、クリックされた順番のコンテンツのみを表示
		    $('.result ul').removeClass('show_result').eq(index).addClass('show_result');

		  });
		});
	</script>
