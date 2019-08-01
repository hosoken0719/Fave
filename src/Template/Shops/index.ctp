<?php ?>
<div class="contents shop">
    <article class="contain-white">
		<div class="contain_inner">
			<div class="information">
				<div class="shop_header">
					<div class="name">

						<h2><?php echo h($shopData->shopname); ?></h2>

						<div class="type">
							<?php echo h($shopData->typename); ?> | <?= h($shopData->pref.$shopData->city.$shopData->ward)?>
						</div>
					</div>
					<div class="photo">
						<?php if($photoShop <> null): ?>
						<?= $this->Html->image($photoShop,array("class" => "img-fluid")); ?>
						<?php else: ?>
						<div class="containera">
							<div class="panel panel-default">
								<div class="panel-heading">写真を投稿する</div>
								<div class="panel-body">
									<input type="file" name="upload_image" id="upload_image" />
									<br />
									<div id="uploaded_image"></div>
								</div>
						    </div>
						</div>
						<?php endif; ?>

					</div>
					<?php if($countShopFollowMyUser > 0):
						echo $this->Html->link(
						"<div class='follower_followed'>あなたがフォローしているユーザの内、<br />".$shopData->shopname."をお気に入り登録している人数<br />".$countShopFollowMyUser."人
							<div class='shoprating'>
								平均お気に入り度<br />".$this->element('rating',['rating'=>round($avgShopFollowMyUser->rating_avg),'shop_id'=>$shopData->shop_id,'enable'=>0]).
								round($avgShopFollowMyUser->rating_avg,1).
							"</div>
						</div>
						", [
							'controller' => 'shops',
						    'action' => '/favorited_follow',$shopData->shop_id
						],
						['escape' => false]);
					else:
						echo $this->Html->link(
						"<div class='follower_followed'>あなたがフォローしているユーザの内、<br />".$shopData->shopname."をお気に入り登録している人数<br />0人"
						, [
							'controller' => 'shops',
						    'action' => '/favorited_all',$shopData->shop_id
						], 
						['escape' => false]);
					endif; ?>
				</div>
			</div>

			<div class="shop_detail">
				<div class="follower">
				</div>
<!-- 				</span>
				<strong class="choice">Choose a rating</strong> -->
				<!-- <div class="introduction"> -->
					<?php //echo nl2br(h($shopData->introduction)); ?>
					<!-- <p><?php //echo $hashtag; ?></p> -->
				<!-- </div> -->
				<div class="infor">					
					<dl>
						<dt>住所</dt>
						<dd> <?= h($shopData->pref.$shopData->city.$shopData->ward.$shopData->town.$shopData->building); ?></dd>
					</dl>
					<dl>
						<dt>営業時間</dt>
			            <dd>
			              <?php
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
			              ?>
			            </dd>
			        </dl>
					<dl>
						<dt>営業時間備考</dt>
						<dd><?= nl2br(h($shopData->shop_business_hour_detail)); ?></dd>
					</dl>
            		<!-- <p><?= h($shopData->holiday); ?></p> -->
					<dl>
						<dt>駐車場</dt>
						<dd><?= h($shopData->parking); ?></dd>
					</dl>
					<dl>
						<dt>電話番号</dt>
						<dd><?= h($shopData->phone_number); ?></dd>
					</dl>
					<dl>
						<dt>HP</dt>
						<dd>
							<?php if(!empty($shopData->homapage)):
								echo $this->Html->link($shopData->homepage, $shopData->homapage,array('target'=>'_blank'));
							endif;?>
						</dd>
						
					</dl>
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
					<div class="edit">
						<?= $this->Html->link('編集', [
					    'controller' => 'ShopUpdates',
					    'action' => 'edit',
					    'id' => $shopData->shop_id]);
					 	?>
					</div>
					<div class="button_wrap">
					
					<?php if($ShopFollowData['rating'] === 0){
						echo $this->Form->button("お気に入りに登録する",['id'=>'display_favorite_button'  ,'class'=>'display_favorite_button ']);
					}else{
						echo $this->Form->button("お気に入りに登録する",['id'=>'display_favorite_button'  ,'class'=>'display_favorite_button hide']);
					} ?>
				</div>

				</div>
			</div>
		</div>
	</article>

	<?php if($ShopFollowData['rating'] > 0): ?>
	<article class="contain-white content_review">
	<?php else: ?>
	<article class="contain-white content_review hide">
	<?php endif; ?>
	    
		<div class="contain_inner">
		    <legend><h3>お気に入り登録</h3></legend>
			<div class="review_area">
				<dl class="myrating">
					<dt>お気に入り度</dt>
					<dd><?= $this->element('rating',['rating'=>$ShopFollowData['rating'],'shop_id'=>$shopData->shop_id,'enable'=>1,'name'=>'input_rating']); ?>
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
						<?= $this->Form->button("削除",['id'=>'delete_favorite'  ,'class'=>'delete_favorite','data-shop'=>$shopData->shop_id]); ?>
					</div>
				</div>
			</div>
		</div>
	</article>
</div>
<div class="loading hide"></div>

<div id="googlemap">
</div>

<div id="uploadimageModal" class="modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
<!-- 			<div class="modal-header">
				<button tupe="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Upload & cCrop Image</h4>
			</div> -->
			<div class="modal-body">
				<div class="row">
						<div id="image-demo" style="width:100%; margin-top:30px";></div>
				</div>
				<div class="button">
					<button class="btn vanilla-rotate">回転</button>

					<?= $this->Form->create(); ?>
					<?= $this->Form->button('登録', array('type' => 'button','class' => 'btn btn-success crop_image')); ?>
					<?= $this->Form->end(); ?>
					<!-- <button class="btn btn-success crop_image">登録</button> -->
				</div>
			</div>
		</div>
		<div class="model-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
	</div>
</div>

</div>
</div>




<script type="text/javascript">
// ▼swiperの設定▼
    var swiper = new Swiper('.swiper-container', {
		slidesPerView: 6,
		spaceBetween: 15,
		// init: false,
		pagination: {
			el: '.swiper-pagination',
			type: 'bullets',
			clickable: true
		},
		breakpoints: {
			1024: {
			  slidesPerView: 4,
			},
			768: {
			  slidesPerView: 3,
			},
			640: {
			  slidesPerView: 2,
			},
			// 320: {
			//   slidesPerView: 1,
			//   spaceBetween: 10,
			// }
		},
		navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
    });
// ▲swiperの設定▲

// ▼croppieの設定▼
	$image_crop = $('#image-demo').croppie({
	    enableExif: true,
	    viewport: {
	        width: 200,
	        height: 150,
	        type: 'square'
	    },
	    boundary: {
	        width: 250,
	        height: 200
	    },
	    enableOrientation: true
	});

	// モーダルウィンドウの表示
	$('#upload_image').on('change', function () {
	  var reader = new FileReader();
	    reader.onload = function (event) {
	      $image_crop.croppie('bind', {
	        url: event.target.result
	      }).then(function(){
	        console.log('jQuery bind complete');
	      });
	    }
	    reader.readAsDataURL(this.files[0]);
	    $('#uploadimageModal').modal('show');
	});

	// モーダルウィンドウ
	//回転ボタン
	  $('.vanilla-rotate').on('click', function(event) {
	      $image_crop.croppie('rotate', '-90');
	  });

	// 登録ボタン
	 var csrf = $('input[name=_csrfToken]').val();
	$('.crop_image').on('click', function (event) {
	  $image_crop.croppie('result', {
	    type: 'canvas',
	    size: { width: 800, height: 600 }
	  }).then(function (image) {
	    $.ajax({
	      url: "/ajax/shopimage",
	      type: "POST",
	      data: {
	      	"image":image,
	      	"shop_id":<?= $shopData->shop_id ?>
	      },
	      beforeSend: function(xhr){
	        xhr.setRequestHeader('X-CSRF-Token', csrf);
	      },
	      success: function (data) {
	      	$('#uploadimageModal').modal('hide');
	      	location.reload(false)
	      	$('#uploaded_image').html(data);
	      },
	      error: function (data, status, errors){
	      }
	    });
	  });
	});
// ▲croppieの設定▲

// ▼お気に入りの登録▼

 	//「お気に入りに登録する」ボタン
	$("#display_favorite_button").on("click",function(){
		$('.content_review').removeClass('hide'); //お気に入り登録画面の表示
		$('#display_favorite_button').addClass('hide'); //ボタンの装飾を変更
		disabled_review('enable'); //コメント蘭の編集を可能にする
	});
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
	});
	//お気に入り登録ボタン。
	//ajaxの登録処理と以下の表示変更を実行
	$("#favorite_button").on("click",function(){
		disabled_review('disabled'); //コメントの編集を禁止
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