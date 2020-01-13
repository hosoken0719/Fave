<div class="contents shop">
    <article class="contain">
		<div class="contain_inner bg-white">
			<?= $this->element('shop_header', ["type" => "basic"]); ?>
			<div class="photo mt-5 mb-5">
				<?php // if($shop_photos <> null): ?>


<!-- 		            <canvas id="canvas" width="0" height="0"></canvas>
					<button class="btn btn-primary" id="upload">投稿</button> -->
<!-- サムネイル表示領域 -->
				<ul>
					<?php foreach ($shop_photos as $shop_photo) :?>
						<li><?= $this->Html->image($shop_photo_dir.$shop_photo->filename,array("class" => "img-fluid")); ?> </li>
					<?php endforeach; ?>
				</ul>
                <?php //elseif($instagram_photos_count > 0): ?>
				<!-- <h5 class="text-center">Instagram</h5><hr> -->
<!-- 				<div class="swiper-container">
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
                </div> -->
            	<?php //endif; ?>


			</div>

			<div class="shop_detail">

				<div class="infor">
					<dl>
						<dt>住所</dt>
						<dd> <?= h($shopData->pref.$shopData->city.$shopData->ward.$shopData->town.$shopData->building); ?>
							<?= $this->Html->link("（地図を開く）","https://www.google.co.jp/maps/place/".$shopData->pref.$shopData->city.$shopData->ward.$shopData->town,array('target'=>'_blank')); ?>
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
										echo $day['open'] . " - " . $day['close'];
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
								echo '-';
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
</div>
<div class="loading hide"></div>




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

