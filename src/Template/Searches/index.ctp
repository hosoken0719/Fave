<?php ?>
<div class="contents search">

<?php if(!$result_flg): //検索ボタンが押された場合?>
    <article class="contain">
		<div class="contain_inner bg-white">
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
						echo $this->Form->control('word', [
							'maxlength' => false,
							'placeholder' => '',
							'label' => 'ユーザ名',
							'id' => 'ac_user'
						]);?>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</article>
<?php else: ?>

	<div class="search_result">
		<article class="contain">
			<div class="contain_inner bg-white">
				<div class="element_wrap">

					<?= $this->Form->create('Searches',['type' => 'get','url' => ['action' => 'index'],'inputDefaults'=>['label'=>false,'div'=>false],'templates' => $template]);?>
					<div class="input_wrap">
						<div class="element">
						<?php
							//ショップタイプ
							echo $this->Form->control('shoptype', [
								'options' => $typename,
							    'empty' => '選択してください',
								'label' => 'ショップタイプ',
								'default' => $shoptype
							]);

							?>
						</div>
						<div class="element ">
							<?php
							//エリア
							echo $this->Form->control('area' , [
								'maxlength' => false,
								'placeholder' => '例)名古屋市',
								'label' => '住所',
								'default' => $area
							]);
							?>
						</div>
					</div>
					<div class="button_wrap">
						<div class="element">
							<?php
								echo $this->Form->button('検索',['class'=>'search_btn']);
							?>
						</div>
					</div>
					<?= $this->Form->end(); ?>
				</div>
				<div class="switch">
					<ul class="display_tab clearfix">
						<li class="active">リスト表示</li>
						<li>地図表示</li>
					</ul>
				</div>

				<hr />
				<div class="result">
					<ul class="show_result">
						<li>
							<div class="shop_flame">
								<?= $this->element('shop_list',['Query' => $shopDatas,'follower_user'=>$follower_user]); ?>
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
		<div class="paginator">
			<ul class="pagination">
				<?= $this->Paginator->first('<< ') ?>
				<?= $this->Paginator->prev('<') ?>
				<?= $this->Paginator->numbers(['modulus' => 6]) ?>
				<?= $this->Paginator->next('>') ?>
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

		//検索ボタンの有効/無効
		$('select[name="shoptype"]').change(function() {
			changeDisabled();
		});
		$('input[name="area"]').on('input', function() {
			changeDisabled();
		});
		function changeDisabled(){
			if($('select[name="shoptype"]').val().length > 0 || $('input[name="area"]').val().length > 0){
    			$('.search_btn').prop('disabled', false);
			}else{
				$('.search_btn').prop('disabled', true);
			}
		}
	</script>
