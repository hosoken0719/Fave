<?php ?>
<div class="contents user">




	<div class="col-sm-12">
		<header class="infor">

			<div class="icon">
				<img src="<?php echo h($user->icon);?>" class="img-circle img-responsive">
			</div>
			<div class="detail">
				<div class="user_name">
					<p><?php echo $user->user_name; ?></p>
				</div>
				<div class="comment">
				</div>
			</div>
		</header>
		<article class="userlist">
			フォロー
			<hr>
			<ul>
<?php 
	foreach($followShopDatas as $data) :
		echo "<li>";
	
		echo $this->Html->link(
		$this->element('shop_summary',['icon' => $data->icon ,'shopname'=> $data->shopname,'typename'=> $data->typename,'address'=> $data->pref.$data->ward.$data->city]), 
		array('controller' => 'shops', 'action' => '/'. $data->accountname), array('escape' => false));
	
		echo "</li>";

	endforeach;
	
	foreach($followUserDatas as $data) : 
		echo "<li>";

		echo $this->Html->link(
		$this->element('user_summary',['icon' => $data->icon ,'username'=> $data->username]),
		array('controller' => 'users', 'action' => '/'. $data->accountname), array('escape' => false));
	
		echo "</li>";

	endforeach;
?>
			</ul>

			フォロワー
			<hr>
			<ul>
<?php 
	foreach($followerShopDatas as $data) :
		echo "<li>";
	
		echo $this->Html->link(
		$this->element('shop_summary',['icon' => $data->icon ,'shopname'=> $data->shopname,'typename'=> $data->typename,'address'=> $data->pref.$data->ward.$data->city]), 
		array('controller' => 'shops', 'action' => '/'. $data->accountname), array('escape' => false));
	
		echo "</li>";

	endforeach;
	
	foreach($followerUserDatas as $data) : 
		echo "<li>";

		echo $this->Html->link(
		$this->element('user_summary',['icon' => $data->icon ,'username'=> $data->username]),
		array('controller' => 'users', 'action' => '/'. $data->accountname), array('escape' => false));
	
		echo "</li>";

	endforeach;
?>
	
			</ul>
		</article>
	</div>

</div>

