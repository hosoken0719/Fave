<?php echo $this->Html->script(['croppie.min.js','croppie_option.js'],['block' => true]) ?>
<div class="contents shop">
    <article class="contain">
		<div class="contain_inner bg-white">
			<div class="information">
				<div class="shop_header">
					<div class="name mb-5">

						<h2><?php echo h($shopData->shopname); ?></h2>

						<div class="type">
							<?php echo h($shopData->typename); ?> | <?= h($shopData->pref.$shopData->city.$shopData->ward)?>
						</div>
					</div>
					<?php if($this->request->getSession()->read('Auth.User.id')): //ログイン時のみ表示?>
					<div class='follower_followed'>
						<?php if($countShopFollowMyUser > 0):
							echo $this->Html->link(
							"あなたがフォローしているユーザの内、<br />".$shopData->shopname."をお気に入り登録している人数<br />".$countShopFollowMyUser."人
								<div class='shoprating'>
									平均お気に入り度<br />".$this->element('rating',['rating'=>round($avgShopFollowMyUser->rating_avg),'shop_id'=>$shopData->shop_id,'enable'=>0]).
									round($avgShopFollowMyUser->rating_avg,1).
								"</div>
							", [
								'controller' => 'shops',
							    'action' => '/favorited_follow',$shopData->shop_id
							],
							['escape' => false]);
						else:
							echo $this->Html->link(
							"あなたがフォローしているユーザの内、<br />".$shopData->shopname."をお気に入り登録している人数<br />0人"
							, [
								'controller' => 'shops',
							    'action' => '/favorited_all',$shopData->shop_id
							],
							['escape' => false]);
						endif; ?>

						<div class="button_wrap">
							<a href="#review_area">
							<?php if($ShopFollowData['rating'] === 0){
								echo $this->Form->button("お気に入りに登録する",['id'=>'display_favorite_button'  ,'class'=>'display_favorite_button ']);
							}else{
								echo $this->Form->button("お気に入りに登録する",['id'=>'display_favorite_button'  ,'class'=>'display_favorite_button hide']);
							} ?>
							</a>

						</div>
					</div>
					<?php else: ?>

					<?php endif; ?>


				</div>
			</div>
			<div class="photo mb-5">
				<?php if($shop_photos <> null): ?>
				<h5 class="text-center">投稿写真</h5><hr>
					<ul>
						<?php foreach ($shop_photos as $key => $value) : ?>
							<li><?= $this->Html->image($value,array("class" => "img-fluid")); ?> </li>
						<?php endforeach; ?>
				</ul>
                <?php elseif($instagram_photos_count > 0): ?>
				<h5 class="text-center">Instagram</h5><hr>
				<div class="swiper-container">
					<div class="swiper-wrapper">
	                        <?php
	                        	foreach ($instagram_photos as $instagram_photo) {
	                        		echo "<div class='swiper-slide'>";
									echo $instagram_photo->url;
	                        		echo '</div>';
	                        	}
                        ?>
                	</div>
                    <div class="swiper-pagination"></div>
				    <div class="swiper-button-prev"></div>
				    <div class="swiper-button-next"></div>
                </div>
            	<?php endif; ?>

					<div class="panel panel-default text-center">
						<div class="panel-body file">
							<label>お店の写真を投稿
							<?= $this->Form->control('field', ['type' => 'file' , 'name' => 'upload_image' , 'id' =>'upload_image','label'=>false]); ?>
							</label>
							<div id="uploaded_image"></div>
						</div>
					</div>
			</div>


<div class="">
			<div class="shop_detail">

				<h5 class="text-center">店舗情報</h5>
				<div class="follower">
				</div>
<!-- 				</span>
				<strong class="choice">Choose a rating</strong> -->
				<!-- <div class="introduction"> -->
					<?php //echo nl2br(h($shopData->introduction)); ?>
					<!-- <p><?php //echo $hashtag; ?></p> -->
				<!-- </div> -->
				<div class="infor text-left">
										<dl>
						<dt>お気に入り登録者数</dt>
						<dd>
						<?php echo $this->Html->link(
							h($countFollowShop)."人	<br />
							<span class='shoprating'>
								<form>
								平均お気に入り度：".
								$this->element('rating',['rating'=>round($avgFollowShop->rating_avg),'shop_id'=>$shopData->shop_id,'enable'=>0]).round($avgFollowShop->rating_avg,1).
								"</form>
							</span>", [
							'controller' => 'shops',
						    'action' => '/favorited_all',$shopData->shop_id
							],
							['escape' => false]);
						?>
						</dd>
					</dl>
					<dl>
						<dt>住所</dt>
						<dd> <?= h($shopData->pref.$shopData->city.$shopData->ward.$shopData->town.$shopData->building); ?></dd>
					</dl>
					<dl>
						<dt>営業時間</dt>
			            <dd>
			            <?php
							if($bussiness_hours_flg==1){
								for($i = 0 ; $i <= 6 ; $i++) {
									echo $week_ja[$i] . "曜日 : ";
									$length = count($bussiness_hours[$i]);
									$no = 0;
									foreach ($bussiness_hours[$i] as $day) {
										echo $day['open'] . "〜" . $day['close'];
										if(++$no !== $length){
											echo  " , ";
										}
									}
									if($no === 0){
										echo "定休日";
									}
									echo "<br />";
								}
							}else{
								echo '-';
							}
			              ?>
			            </dd>
			        </dl>
					<dl>
						<dt>営業時間備考</dt>
						<dd><?
							if(!empty($shopData->shop_business_hour_detail)){
								echo nl2br(h($shopData->shop_business_hour_detail));
							}else{
								echo '-';
							}
							?>
						</dd>
					</dl>
            		<!-- <p><?= h($shopData->holiday); ?></p> -->
					<dl>
						<dt>駐車場</dt>
						<dd><?
							if(!$shopData->parking === "0"){
								echo h($shopData->parking);
							}else{
								echo 'なし';
							}
							?>
						</dd>
					</dl>
					<dl>
						<dt>電話番号</dt>
						<dd><?= h($shopData->phone_number); ?></dd>
					</dl>
					<dl>
						<dt>HP</dt>
						<dd>
							<?php if(!empty($shopData->homepage)):
								echo $this->Html->link($shopData->homepage, $shopData->homapage,array('target'=>'_blank'));
							endif;?>
						</dd>

					</dl>

					<!-- <div class="edit"> -->
						<?php // $this->Html->link('編集', ['controller' => 'ShopUpdates','action' => 'edit','id' => $shopData->shop_id]); ?>
					<!-- </div> -->


				</div>
			</div></div>

		</div>
	</article>

	<?php if($ShopFollowData['rating'] > 0): ?>
		<article class="contain content_review">
	<?php else: ?>
		<article class="contain content_review hide">
	<?php endif; ?>
		<div class="contain_inner bg-white">
			<div id="review_area">
			<?php if($ShopFollowData['rating'] > 0): ?>
			    <h5 class="text-center">お気に入り登録済み</h5>
			<?php else: ?>
			    <h5 class="text-center">お気に入り登録</h5>
			<?php endif; ?>
				<dl class="myrating">
					<dt>お気に入り度</dt>
					<dd>
						<form type="get" action="#">
							<div class="evaluation">
								<input type="radio" name="input_rating" value="5"  id=star1 <?php if($ShopFollowData['rating']==5) echo " checked";  ?> disabled />
								<label for="star1">&#9829;</label>
								<input type="radio" name="input_rating" value="4"  id=star2 <?php if($ShopFollowData['rating']==4) echo " checked"; ?> disabled/>
								<label for="star2">&#9829;</label>
								<input type="radio" name="input_rating" value="3"  id=star3 <?php if($ShopFollowData['rating']==3) echo " checked"; ?> disabled/>
								<label for="star3">&#9829;</label>
								<input type="radio" name="input_rating" value="2"  id=star4 <?php if($ShopFollowData['rating']==2) echo " checked"; ?> disabled/>
								<label for="star4">&#9829;</label>
								<input type="radio" name="input_rating" value="1"  id=star5 <?php if($ShopFollowData['rating']==1) echo " checked"; ?> disabled/>
								<label for="star5">&#9829;</label>
								<input type="hidden" name="shop_id" value=<?= $shopData->shop_id ?>>
								<input type="hidden" name="button" value="button" ?>
							</div>
						</form>

					</dd>
				</div>
				<div class="myreview_edit hide">
					<dt>コメント</dt>
					<dd>
					<?= $this->Form->control('コメント', [
					    'type' => 'textarea',
				        'templates' => [
					        'inputContainer' => '{{content}}'
					    ],
					    'placeholder' => 'コメントを記入して下さい',
					    'rows' => 1,
					    'label' => false,
					    'id' => 'input_review',
					    // 'class' => 'hide',
					    'value' => $ShopFollowData['review'],
					    'disabled' => true,
					    'maxlength' => 1500,
						]
					 );
					 ?>
					 </dd>
					<?php if($ShopFollowData['rating'] > 0){
							$buttonTitle = "更新";
						}else{
							$buttonTitle = "お気に入り登録";
						}

						echo "<div class='follow'>";
						echo "<p>";

						echo $this->Form->button("お気に入りに登録する",['id'=>'favorite_button'  ,'class'=>'favorite_button','data-shop'=>$shopData->shop_id]);
						// echo $this->Form->submit($buttonTitle,['id'=>'favorite_button'  ,'class'=>'favorite_button','data-shop'=>$shopData->shop_id ]);
						echo $this->Form->end();
						echo "</p></div>";
					?>
				</div>

				<div class="myreview_display">
					<dl>
						<dt>コメント</dt>
						<dd><span class="review"><?= nl2br(h($ShopFollowData['review'])); ?></s></dd>
					</dl>
					<div class="button">
						<?= $this->Form->button("編集",['id'=>'edit_favorite'  ,'class'=>'edit_favorite','data-shop'=>$shopData->shop_id]); ?>
						<?= $this->Form->button("登録を解除",['id'=>'delete_favorite'  ,'class'=>'delete_favorite','data-shop'=>$shopData->shop_id]); ?>
					</div>
				</div>
			</div>
		</div>
	</article>
</div>
<div class="loading hide"></div>

<!-- <div id="googlemap">
</div> -->
<div id="uploadimageModal" class="modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-body">
				<div class="row">
						<div id="image-demo" style="width:100%; margin-top:30px";></div>
				</div>
				<div class="button form">
					<button class="btn vanilla-rotate">回転</button>
					<?= $this->Form->create(); ?>
					<?= $this->Form->button('登録', array('type' => 'button','class' => 'btn crop_image')); ?>
					<?= $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
</div>




<script type="text/javascript">
// ▼swiperの設定▼
    var swiper = new Swiper('.swiper-container', {
		slidesPerView: 2,
		spaceBetween: 15,
		pagination: {
			el: '.swiper-pagination',
			type: 'bullets',
			clickable: true
		},
		breakpoints: {
			// 1024: {
			//   slidesPerView: 2,
			// },
			768: {
			  slidesPerView: 2,
			},
			640: {
			  slidesPerView: 1,
			},
			320: {
			  slidesPerView: 1,
			  spaceBetween: 10,
			}
		},
		navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
    });
// ▲swiperの設定▲


// ▼お気に入りの登録▼

 	//「お気に入りに登録する」ボタン

	<?php if($this->request->getSession()->read('Auth.User.id')): //ログイン時のみ表示?>
		$("#display_favorite_button").on("click",function(){
			$('.content_review').removeClass('hide'); //お気に入り登録画面の表示
			$('#display_favorite_button').addClass('hide'); //ボタンの装飾を変更
			disabled_review('enable'); //コメント蘭の編集を可能にする
		});
	<?php else: ?>
		$("#display_favorite_button").on("click",function(){
			window.location.href = '/login';
		});
	<?php endif; ?>
	//編集ボタン
	$("#edit_favorite").on("click",function(){
		disabled_review('enable'); //コメント蘭の編集を可能にする
	});

	//お気に入りを削除ボタン
	$("#delete_favorite").on("click",function(){
		$('.content_review').addClass('hide'); //お気に入り登録画面を非表示
		$('#display_favorite_button').removeClass('clicked'); //ボタンの装飾を変更
		$('#input_review').val(""); //コメントを削除
		$('.review').text(""); //コメントを削除
		$('#display_favorite_button').removeClass('hide'); //「お気に入りに登録する」ボタンの表示
		$('input[name="input_rating"]').prop('checked',false); //お気に入り度のチェックを全て外す
		$('#star5').prop('checked',true); //お気に入り度のチェックを1つだけ付ける
		$('#review_area h5').text("お気に入りに登録");; //コメントの編集を禁止
	});
	//お気に入り登録ボタン。
	//ajaxの登録処理と以下の表示変更を実行
	$("#favorite_button").on("click",function(){
		disabled_review('disabled'); //コメントの編集を禁止
		$('#review_area h5').text("お気に入り登録済み");; //コメントの編集を禁止
	});
	//お気に入り欄の編集モード
	function disabled_review(mode){
		if(mode === 'disabled' ){ //編集禁止
			$('.content_review').removeClass('edit');
			$('input[name="input_rating"]').prop('disabled', true); //お気に入り度の編集を禁止
			$('#input_review').prop('disabled', true);
			$('#display_favorite_button').addClass('hide'); //「お気に入りに登録する」ボタンを非表示
			$('.review').textWithLF($('#input_review').val()); //登録したコメントを表示
		}else if(mode === 'enable'){ //編集モード
			$('.content_review').addClass('edit');
			$('input[name="input_rating"]').prop('disabled', false); //お気に入り度の編集を許可
			$('#input_review').prop('disabled', false);
		}
	}
// ▲お気に入りの登録▲


(function ($) {
    var escapes = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        },
        escapeRegexp = /[&<>"']/g,
        hasEscapeRegexp = new RegExp(escapeRegexp.source),
        unescapes = {
            '&amp;': '&',
            '&lt;': '<',
            '&gt;': '>',
            '&quot;': '"',
            '&#39;': "'"
        },
        unescapeRegexp = /&(?:amp|lt|gt|quot|#39);/g,
        hasUnescapeRegexp = new RegExp(unescapeRegexp.source),
        stripRegExp = /<(?:.|\n)*?>/mg,
        hasStripRegexp = new RegExp(stripRegExp.source),
        nl2brRegexp = /([^>\r\n]?)(\r\n|\n\r|\r|\n)/g,
        hasNl2brRegexp = new RegExp(nl2brRegexp.source),
        br2nlRegexp = /<br\s*\/?>/mg,
        hasBr2nlRegexp = new RegExp(br2nlRegexp.source);

    $.fn.textWithLF = function (text) {
        var type = typeof text;

        return (type == 'undefined')
            ? htmlToText(this.html())
            : this.html((type == 'function')
                ? function (index, oldHtml) {
                    var result = text.call(this, index, htmlToText(oldHtml));
                    return (typeof result == 'undefined')
                        ? result
                        : textToHtml(result);
                } : textToHtml(text));
    };

    function textToHtml(text) {
        return nl2br(escape(toString(text)));
    }

    function htmlToText(html) {
        return unescape(strip(br2nl(html)));
    }

    function escape(string) {
        return replace(string, escapeRegexp, hasEscapeRegexp, function (match) {
            return escapes[match];
        });
    }

    function unescape(string) {
        return replace(string, unescapeRegexp, hasUnescapeRegexp, function (match) {
            return unescapes[match];
        });
    }

    function strip(html) {
        return replace(html, stripRegExp, hasStripRegexp, '');
    }

    function nl2br(string) {
        return replace(string, nl2brRegexp, hasNl2brRegexp, '$1<br>');
    }

    function br2nl(string) {
        return replace(string, br2nlRegexp, hasBr2nlRegexp, '\n');
    }

    function replace(string, regexp, hasRegexp, replacement) {
        return (string && hasRegexp.test(string))
            ? string.replace(regexp, replacement)
            : string;
    }

    function toString(value) {
        if (value == null) return '';
        if (typeof value == 'string') return value;
        if (Array.isArray(value)) return value.map(toString) + '';
        var result = value + '';
        return '0' == result && 1 / value == -(1 / 0) ? '-0' : result;
    }
})(jQuery);

</script>
<script type="text/javascript">var id = "<?= $shopData->shop_id ?>";var path = 'shopimage';var type = 'square';</script>