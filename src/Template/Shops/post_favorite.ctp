<?php ?>
<div class="contents shop">
    <article class="contain">
		<div class="contain_inner bg-white">
			<?= $this->element('shop_header',["type"=>""]); ?>
			<article class="contain content_review edit">
			<div class="contain_inner bg-white">
				<div id="review_area">

	        	<?php echo $this->Form->create('',['url' => ['controller' => 'Shops', 'action' => 'register']]); ?>
					<dl class="myrating">
						<dt>お気に入り度</dt>
						<dd>
							<!-- <form type="get" action="#"> -->
								<div class="evaluation">
									<input type="radio" name="rating" value="5"  id=star1 <?php if($ShopFollowData['rating']==5) echo " checked";  ?>  />
									<label for="star1">&#9829;</label>
									<input type="radio" name="rating" value="4"  id=star2 <?php if($ShopFollowData['rating']==4) echo " checked"; ?> />
									<label for="star2">&#9829;</label>
									<input type="radio" name="rating" value="3"  id=star3 <?php if($ShopFollowData['rating']==3) echo " checked"; ?> />
									<label for="star3">&#9829;</label>
									<input type="radio" name="rating" value="2"  id=star4 <?php if($ShopFollowData['rating']==2) echo " checked"; ?> />
									<label for="star4">&#9829;</label>
									<input type="radio" name="rating" value="1"  id=star5 <?php if($ShopFollowData['rating']==1) echo " checked"; ?> />
									<label for="star5">&#9829;</label>
								</div>
							<!-- </form> -->

						</dd>
					</dl>
					<dl class="myreview_edit edit">
						<dt>コメント</dt>
						<dd>
						<?= $this->Form->control('review', [
						    'type' => 'textarea',
					        'templates' => [
						        'inputContainer' => '{{content}}'
						    ],
						    'placeholder' => 'コメントを記入して下さい',
						    'rows' => 1,
						    'label' => false,
						    'id' => 'input_review',
						    'value' => $ShopFollowData['review'],
						    'disabled' => false,
						    'maxlength' => 1500,
							]
						 );
						 ?>
						 </dd>
					</dl>

					<?= $this->Form->control('shop_id',['type'=>'hidden','value'=>$shopData->shop_id]); ?>
					<?php if($ShopFollowData['rating'] > 0){
							//更新ボタン
							echo $this->Form->submit('更新',['id'=>'favorite_button'  ,'class'=>'favorite_button','data-mothod'=>'delete']);
							echo $this->Form->control('method',['type'=>'hidden','value'=>'add']);
							echo $this->Form->end();
							//お気に入り解除ボタン
		        			echo $this->Form->create('',['url' => ['controller' => 'Shops', 'action' => 'register']]);
							echo $this->Form->submit('お気に入り解除',['id'=>'favorite_button'  ,'class'=>'favorite_button','data-mothod'=>'delete']);
							echo $this->Form->control('shop_id',['type'=>'hidden','value'=>$shopData->shop_id]);
							echo $this->Form->control('method',['type'=>'hidden','value'=>'delete']);
							echo $this->Form->end();
						}else{
							//登録ボタン
							echo $this->Form->submit('登録',['id'=>'favorite_button'  ,'class'=>'favorite_button','data-shop'=>$shopData->shop_id ]);
							echo $this->Form->control('method',['type'=>'hidden','value'=>'add']);
							echo $this->Form->end();
						}
					?>
					<div class='follow'>
						<p>
					 	</p>
					</div>
				</div>
			</div>
		</article>
		</div>
	</article>
</div>