<?php ?>


	<div class="contents shop">
		<article class="content">
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

				<div class="follower_followed">あなたがフォローしているユーザの内、<br /><?php echo h($shopData->shopname); ?>をお気に入り登録している人数<br ><?php echo h($countShopFollowMyUser); ?>人
					<div class="shoprating">
						平均お気に入り度<br /><?= $this->element('rating',['rating'=>round($avgShopFollowMyUser->rating_avg),'shop_id'=>$shopData->shop_id,'enable'=>0]); ?>
						<?= round($avgShopFollowMyUser->rating_avg,1)?> 
					</div>
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
	            		<!-- <p><?= h($shopData->holiday); ?></p> -->
						<dl>
							<dt>駐車場</dt>
							<dd><?= h($shopData->parking); ?></dd>
						</dl>
						<dl>
							<dt>電話番号</dt>
							<dd><?= h($shopData->tel); ?></dd>
						</dl>
						<dl>
							<dt>HP</dt>
							<dd><?= $this->Html->link($shopData->homepage, $shopData->homapage,array('target'=>'_blank')); ?></dd>
						</dl>
						<dl>
							<dt>お気に入り登録者数</dt>
							<dd>
								<?php echo h($countFollowShop); ?>人	<br />
								<span class="shoprating">
									<form>
									平均お気に入り度：<?= $this->element('rating',['rating'=>round($avgFollowShop->rating_avg),'shop_id'=>$shopData->shop_id,'enable'=>0]); ?>
									<?= round($avgFollowShop->rating_avg,1)?>	
								</form>
								</span>
							</dd>
						</dl>
										<div class="edit">
					<?= $this->Html->link('編集', [
				    'controller' => 'ShopUpdates',
				    'action' => 'edit',
				    'id' => $shopData->shop_id]);
				 	?>
				</div>

					
					<?php if($ShopFollowData['rating'] === 0){
						echo $this->Form->button("お気に入りに登録する",['id'=>'display_favorite_button'  ,'class'=>'display_favorite_button pointer']);
					}else{
						echo $this->Form->button("お気に入りに登録する",['id'=>'display_favorite_button'  ,'class'=>'display_favorite_button pointer clicked hide']);
					} ?>


				</div>
			</div>
</article>

		<?php if($ShopFollowData['rating'] > 0): ?>
			<article class="content_review">
		<?php else: ?>
			<article class="content_review hide">
		<?php endif; ?>
				<!-- <div class="review_wrap"> -->
					<!-- <div class="icon_area">
	                    <span class="icon"> <?php echo $this->Html->image('icon_user',['alt'=> 'User']); ?></span>
	                </div> -->
	                <h5>お気に入りに登録</h5>
	                <div class="review_area">
						<div class="myrating">
							お気に入り度<?= $this->element('rating',['rating'=>$ShopFollowData['rating'],'shop_id'=>$shopData->shop_id,'enable'=>1,'name'=>'input_rating']); ?>
						</div>
						<div class="myreview">
							<!-- <div class="wrap on"> -->
								<?= $this->Form->control('コメント', [
								    'type' => 'textarea',   
							        'templates' => [
								        'inputContainer' => '{{content}}'
								    ],
								    'placeholder' => '',
								    'rows' => 1,
								    'label' => false,
								    'id' => 'input_review',
								    'value' => $ShopFollowData['review'],
								    'disabled' => true,
								    'maxlength' => 450,
									]
								 );
								 ?>
<!-- 								<div class="mirror"></div>
							</div> -->
						</div>
					</div>
				<!-- </div> -->
				<?php if($ShopFollowData['rating'] > 0){
						$buttonTitle = "更新";
					}else{
						$buttonTitle = "お気に入り登録";
					}
				?>
				<?php
					echo "<div class='follow hide'>";
					echo "<p>";
					echo $this->Form->submit($buttonTitle,['id'=>'favorite_button'  ,'class'=>'favorite_button','data-shop'=>$shopData->shop_id ]);
					echo $this->Form->end();
					echo "</p></div>";
				?>

				<?= $this->Form->button("編集",['id'=>'edit_favorite'  ,'class'=>'edit_favorite']); ?>
				<?= $this->Form->submit("削除",['id'=>'delete_favorite'  ,'class'=>'delete_favorite','data-shop'=>$shopData->shop_id ]); ?>


			</article>

		</div>



 				<div class="loading hide"></div>
	<div class="col-sm-12">


  <!-- Swiper -->

	<article class="followlist">
		<p>オススメのお店</p>
		<div class="swiper-container">
	    	<div class="swiper-wrapper">
			<?php foreach($shop_data_summary as $id => $data) : ?>
				<div class='swiper-slide'>
					<div class='follow_user'>

						<?=	
							$this->element('shop_summary',['photoShop' => $shop_icon[$id] ,'shopname'=> $data->shopname,'typename'=> $data->typename,'address'=> $data->pref.$data->ward.$data->city,
								'shop_id'=>$data->shop_id]);
						?>

					</div>
				</div>
			<?php endforeach; ?>
			</div>
			<div class="swiper-pagination"></div>
		</div>
<!-- 	<div class="swiper-button-prev"></div>
	次ページボタン
	<div class="swiper-button-next"></div> -->
	</article>




	</div>


	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div id="googlemap">
				</div>
			</div>
		</div>
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

// ▼クチコミ入力欄を可変高さにする▼
	jQuery(document).ready(function($) {
		$('textarea').on('keyup', function(event) {
		    var str = $(this).val();
		    str = str.replace(/\r?\n/g, '<br>') + '<br>';
		    $(this).next('.mirror').html(str);
		});
	});
// ▲クチコミ入力欄を可変高さにする▲

// ▼お気に入りの登録▼
	const placeholder = 'コメントは記載されていません';
	const placeholder_edit = 'コメントを記入して下さい';
 	
 	//placeholderのデフォルト設定
	$('#input_review').attr('placeholder',placeholder);

 	//「お気に入りに登録する」ボタン
	$("#display_favorite_button").on("click",function(){
		$('.content_review').removeClass('hide'); //お気に入り登録画面の表示
		$('#display_favorite_button').addClass('clicked'); //ボタンの装飾を変更
		$('#display_favorite_button').removeClass('pointer'); //ボタンのポインターを削除
		disabled_review('enable'); //コメント蘭の編集を可能にする
	});

	//編集ボタン
	$("#edit_favorite").on("click",function(){
		disabled_review('enable'); //コメント蘭の編集を可能にする
	});
	
	//お気に入りを削除ボタン
	$("#delete_favorite").on("click",function(){ 
		$('.content_review').addClass('hide'); //お気に入り登録画面を非表示
		$('#display_favorite_button').removeClass('clicked hide'); //お気に入りに登録するボタンを再表示
		$('#display_favorite_button').addClass('pointer'); //ポインタを表示
		$('#input_review').val(""); //コメントを削除
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
			$('input[name="input_rating"]').prop('disabled', true); //お気に入り度の編集を禁止
			$('#input_review').prop('disabled', true);  
			$('#input_review').removeClass('border_bottom');//コメント記入欄に下線を削除
			$('label').removeClass('pointer'); //お気に入り度のポインターを矢印に戻す
			$('.follow').addClass('hide'); //登録ボタンの非表示
			$('#display_favorite_button').addClass('hide'); //「お気に入りに登録する」ボタンを再表示
			change_placeholder(); //

		}else if(mode === 'enable'){ //編集モード
			$('input[name="input_rating"]').prop('disabled', false); //お気に入り度の編集を許可
			$('#input_review').prop('disabled', false);
			$('#input_review').addClass('border_bottom'); //コメント記入欄に下線を表示
			$('label').addClass('pointer'); //お気に入り度のポインターを指に変更
			$('.follow').removeClass('hide');//登録ボタンの表示
			change_placeholder('edit');
		}
	}

	//コメントのplaceholder表示設定
	function change_placeholder(data=''){
		if(data === 'edit'){
			$('#input_review').attr('placeholder',placeholder_edit); //placeholderを内容を変更
		}else{
			$('#input_review').attr('placeholder',placeholder); //placeholderを内容を変更
		}

	}
// ▲お気に入りの登録▲

</script>