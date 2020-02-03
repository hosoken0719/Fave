<?php ?>
<div class="contents shop">
    <article class="contain">
		<div class="contain_inner bg-white">
			<?= $this->element('shop_header', ["type" => "basic"]); ?>
			<div class="photo_list mt-5 mb-5">
				<?php // if($shop_photos <> null): ?>


<!-- 		            <canvas id="canvas" width="0" height="0"></canvas>
					<button class="btn btn-primary" id="upload">投稿</button> -->
<!-- サムネイル表示領域 -->
				<?php if($shop_photos <> null): ?>
					<?php foreach ($shop_photos as $value) :?>
						<figure class="thumbnail"><div class="inner"><?= $this->Html->image($shop_photo_dir.$value->file_name,array("class" => "")); ?> </div></figure>
					<?php endforeach; ?>
				<?php endif; ?>
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

