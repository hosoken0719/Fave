<?php ?>
<div class="contents search">

<?php if(!$result_flg): //検索ボタンが押された場合?>

	<article class="contain-white">
		<div class="search_box">
			<div class="switch">
				<ul class="search_tab clearfix">
					<li class="active">お店</li>
					<li>ユーザ</li>
				</ul>
			</div>
			<div class="category">
				<ul class="show_category shops block">
					<li>
					<?php echo $this->element('search_box_shop'); ?>
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
<?php else: ?>

	<div class="search_result shop">
		<article>
			<div class="switch">
				<ul class="display_tab clearfix">
					<li class="active">リスト表示</li>
					<li>地図表示</li>
				</ul>
			</div>

			<div class="result">
				<ul class="show_result">
					<li>
						<div class="shop_flame">
				<?php
				$i = 0;
				foreach($shopDatas as $shopData) :
					$i = $i + 1;
				    //ショップの写真パスを取得
		            $dir = SHOPPHOTO_UPLOADDIR . '/photo_shop/' . $shopData->shop_id .'/';
		            $photo_list = glob($dir . '*.png');
		            $photoShop = null;
		            if(!empty($photo_list)){
		                $photoShop_fullpath = max($photo_list); //最新写真のみ抽出
		                $photoShop_array = explode('/',$photoShop_fullpath); //サーバパスの取得となるため、最後のファイル名だけを取得
		                $photoShop = "https://fave-jp.info/img/photo_shop/" . $shopData->shop_id . "/thumbnail/middle_" . end($photoShop_array);
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
								 	 	<h2>
								 	 		<?php echo $this->Html->link($shopData->shopname, array('controller' => 'shops', 'action' => '/'. $shopData->shop_id)); ?>
										</h2>
										<?= h($shopData->typename) ?>
										<div>
											<?= h($shopData->pref.$shopData->city.$shopData->ward) ?>
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
		</article>
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
