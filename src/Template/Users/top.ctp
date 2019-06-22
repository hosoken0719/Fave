<?php ?>
<div id="top">
	<div class="main">
	    <header id="header">
	        <div id="header_logo">
	          <h1><a href="/"><?php echo $this->Html->image('logo.svg'); ?></a></h1>
	        </div>
	        <div class="content">
				<div class="comment mincho">
					<h2 class="top-copy_ja">まだ、知らないお店へ</h2>
					<h2 class="top-copy_en">Find your favorite shops</h2>
				</div>
			</div>
	    </header>
		<nav>
			<div id="header-links" class="text-center header-fluid-box">
	            <div class="header-fluid-box-inner">
	            	<ul class="list-unstyled list-inline">
	            		<li class="list-inline-item"><?= $this->Html->link(__d('CakeDC/Users', '新規登録'), ['plugin' => 'CakeDC/Users', 'controller' => 'Users', 'action' => 'register']) ?></li>
	             		<li class="list-inline-item"><?= $this->Html->link(__d('CakeDC/Users', 'ログイン'), ['plugin' => 'CakeDC/Users', 'controller' => 'Users', 'action' => 'login']) ?></li>
	            	</ul>
	        	</div>
	        </div>
        </nav>
		<div class="top-hero_image">
			<?php echo $this->Html->image('hero.jpg'); ?>
		</div>
	</div>

	<section>
		<div class="concept bg_white">
			<div class="mincho">
				<h2>Concept</h2>
			</div>
			<div class="comment">
				<p><span class="breakSP">自分と同じお店が好きな人って、</span>
				他にどんなお店に行ってるんだろう。</p>
				<p><span class="breakSP">知らない人の評価が高いお店が、</span>自分も好きとは限らないけど、</p>
				<p><span class="breakSP">同じお店が好きな人が行く所は、</span>きっと自分も好きだと思う。</p><hr />
				<p>同じ服屋さんが好きな人が行く服屋さん</p>
				<p>同じカフェが好きない人が行くレストラン</p>
				<p>同じ花屋さんが好きな人が行く雑貨屋さん</p><hr />
				<p>まだ、知らないお店が見つかります</p>
			</div>
		</div>
	</section>
	<section>
		<div class="content">
			<div class="how_to comment">
				<div class="mincho">
					<h2>好きなお店を<br>フォローするだけ</h2>
				</div>
				 <hr />
				<p>衣・食・住</p>
				<p>好きなお店をフォローしてください。</p>
				<p>色々なお店をフォローすることで、あなたの好きなお店の傾向を解析します</p>
			</div>
		</div>
	</section>
	<section>
		<div class="content bg_white">
			<div class="function comment">
				<div class="mincho">
					<h2>機能</h2>
				</div>
				<div class="flex-container">
					<div class="flex-item">
						<?php echo $this->Html->image('time.svg'); ?>
						<h3 class="mincho">好みのお店を提案</h3>
						<p>タイムラインにあなた好みのお店を提案</p>
						<p>随時更新されるので、時々チェック</p>
					</div>
					<div class="flex-item">
						<?php echo $this->Html->image('map.svg'); ?>
						<h3 class="mincho">フォローマップ</h3>
						<p>フォローしているお店をマップに表示</p>
						<p>今いる場所に好きなお店があるかチェック</p>
					</div>
					<div class="flex-item">
						<?php echo $this->Html->image('search.svg'); ?>
						<h3 class="mincho">検索</h3>
						<p>お店の検索はあなたの好みに合わせた順番で表示</p>
					</div>
				</div>
			</div>
		</div>
	</section>


	<section class="regist">
		<div class="flex-container">
			<div class="flex-item">
				<p><?= $this->Html->link(__d('CakeDC/Users', '新規登録'), ['plugin' => 'CakeDC/Users', 'controller' => 'Users', 'action' => 'register']) ?></p>
			</div>
			<div class="flex-item">
				<p><?= $this->Html->link(__d('CakeDC/Users', 'ログイン'), ['plugin' => 'CakeDC/Users', 'controller' => 'Users', 'action' => 'login']) ?></p>
			</div>
		</div>
	</section>

</section>

</div>
<footer>
	copy right Fave 2019
</footer>

</div>