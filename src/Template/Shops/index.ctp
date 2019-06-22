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

					
					<?php if($ShopFollowData['rating'] === 0): ?>
						<?= $this->Form->button("お気に入りに登録する",['id'=>'disply_favorite_button'  ,'class'=>'disply_favorite_button']); ?>
					<?php endif; ?>

				</div>
			</div>
</article>

		<!-- <?php if($ShopFollowData['rating'] > 0): ?> -->
			<article class="review_area">
<!-- 		<?php else: ?>
			<article class="review_area_hide">
		<?php endif; ?> -->
				<span class="myrating">
					お気に入り度<?= $this->element('rating',['rating'=>$ShopFollowData['rating'],'shop_id'=>$shopData->shop_id,'enable'=>1,'name'=>'input_rating']); ?>
				</span>
				<div class="review">
					<p><a class="modal-open">コメント</a></p>
					<div class="wrap on">
						<?= $this->Form->control('クチコミ', [
						    'type' => 'textarea',   
					        'templates' => [
						        'inputContainer' => '{{content}}'
						    ],
						    'placeholder' => 'クコチミを記入してください',
						    'rows' => 1,
						    'label' => false,
						    'id' => 'input_review',
						    'value' => $ShopFollowData['review']
							]
						 );
						 ?>
						<div class="mirror"></div>
					</div>
					<?php
						echo "<div class='follow'>";
						echo "<p>";
						echo $this->Form->submit("お気に入り登録",['id'=>'favorite_button'  ,'class'=>'favorite_button','data-shop'=>$shopData->shop_id ]);
						echo $this->Form->end();
						echo "</p></div>";
					?>
					<?= $this->Form->create('Follow'); ?>
					<?php if($ShopFollowData['rating'] > 0){
							$buttonTitle = "更新";
						}else{
							$buttonTitle = "お気に入り登録";
						}
					?>
					<?= $this->Form->end(); ?>
				</div>

				<div class="edit">
					<?= $this->Html->link('編集', [
				    'controller' => 'ShopUpdates',
				    'action' => 'edit',
				    'id' => $shopData->shop_id]);
				 	?>
				</div>
			</div>
		</article>

	</div>



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
<!-- フォローワーのモーダルウィンドウ -->
<div id="modal-main">
	<div class="header">全てのフォロワー<span class="cancel_button"></span></div>

<?php
foreach ($followUserDatas as $followUserData) {

echo $this->Html->link(__($followUserData->username), ['controller' => 'Users', 'action' => '/',$followUserData->username]);
	echo "<div class='follow'>";
	echo "<p>";
	echo $this->Form->create('Follow'); 
	echo $this->Form->submit('フォローする',array('id'=>'button'.$followUserData->user_id  ,'class'=>'follow_button','data-user'=>$followUserData->user_id , 'data-button'=>'button'.$followUserData->user_id ));
	echo $this->Form->end();
	echo "</p></div>";
}
?>
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
	// <script async="async">
// ▼クチコミ入力欄を可変高さにする▼
	jQuery(document).ready(function($) {
		$('textarea').on('keyup', function(event) {
		    var str = $(this).val();
		    str = str.replace(/\r?\n/g, '<br>') + '<br>';
		    $(this).next('.mirror').html(str);
		});
	});
// ▲クチコミ入力欄を可変高さにする▲

//modal
 
  //テキストリンクをクリックしたら
 $(".modal-open").click(function(){
      //body内の最後に<div id="modal-bg"></div>を挿入
     $("body").append('<div id="modal-bg"></div>');
 
    //画面中央を計算する関数を実行
    modalResize();
 
    //モーダルウィンドウを表示
        $("#modal-bg,#modal-main").fadeIn("slow");
 
    //画面のどこかをクリックしたらモーダルを閉じる
      $("#modal-bg,.cancel_button").click(function(){
          $("#modal-main,#modal-bg").fadeOut("slow",function(){
         //挿入した<div id="modal-bg"></div>を削除
              $('#modal-bg').remove() ;
         });
 
        });
 
    //画面の左上からmodal-mainの横幅・高さを引き、その値を2で割ると画面中央の位置が計算できます
     $(window).resize(modalResize);
      function modalResize(){
 
            var w = $(window).width();
          var h = $(window).height();
 
            var cw = $("#modal-main").outerWidth();
           var ch = $("#modal-main").outerHeight();
 
        //取得した値をcssに追加する
            $("#modal-main").css({
                "left": ((w - cw)/2) + "px",
                "top": ((h - ch)/2) + "px"
          });
     }
   });

	$("#disply_favorite_button").on("click",function(){
		$('.review_area').css('display','block');
		$('button').css({
			'background-color':'#fff',
			'border':'1px solid #ccc',
			'color':'#000'});
		$('#input_review').focus();
	});


</script>