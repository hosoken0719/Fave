

<div class="contents regist">
    <article class="contain-white">
    <div class="contain_inner">
			<div class="form">
        <fieldset class="basic">
          <legend><?= "基本情報" ?></legend>
          <?= $this->Form->create('',['class'=>'next','url' => ['action' => 'register'],'templates' => $template]); ?>
          <dl><div class='shopname'><div class='require'></div></div>
            <?= $this->Form->control('shopname', ['label' => "ショップ名（必須)",'name'=>'shopname','value'=>$shopname]); ?></dl>
            <dl><?= $this->Form->control('branch', ['label' => '支店名','value'=>$branch,'placeholder' => '栄支店']) ?></dl>

          <dl><div class='kana'><div class='require'></div></div>
            <?= //フリガナ（必須項目のため、空欄の場合はcontrollerからフラグを受け取る）
                $this->Form->control('kana', ['label' => 'フリガナ（必須)','name'=>'kana','value'=>$kana]); ?>
            </dl>

            <dl>
              <div class='shoptype'><div class='require'></div></div>
              <?= //ショップタイプ一覧
              $this->Form->control('shoptype',
                ['options' => $typename,
                  'empty' => '選択してください',
                  'class' => 'search_box_select',
                  'id' => 'login_form_select',
                  'label' => 'ショップタイプ（必須）',
                  'type' => 'select',
                  'default' => $shoptype,
                ]); ?>
            </dl>
            <dl>
              <?= //ショップタイプ一覧
              $this->Form->control('shoptype2',
                ['options' => $typename,
                  'empty' => '選択してください',
                  'class' => 'search_box_select',
                  'id' => 'login_form_select',
                  'label' => 'ショップタイプ2',
                  'type' => 'select',
                  'default' => $shoptype2,
                ]); ?>
            </dl>
        </fieldset>
        <fieldset class="address">
          <legend><?= "住所" ?></legend>
            <dl>
              <div class='pref'><div class='require'></div></div>
            <?= //住所の県一覧
              $this->Form->control('pref',
                ['options' => $pref_list,
                  'empty' => '選択してください',
                  'class' => 'search_box_select',
                  'id' => 'login_form_select',
                  'label' => '県名（必須）',
                  'type' => 'select',
                  'default' => $pref,
              ]); ?>
            </dl>
          <dl>
            <div class='address'><div class='require'></div>
            <?php  if($this->request->getQuery('error') == 1) echo "住所が間違っています。";  ?>
          </div>
            <?= $this->Form->control('address', ['label' => '市区町村・番地(必須)','name'=>'address','value'=>$address]); ?>
          </dl>
          <dl>
            <?= $this->Form->control('building', ['label' => 'ビル名・階数','value'=>$building]); ?>
          </dl>
        </fieldset>

        <fieldset>
          <legend>詳細情報</legend>
              <dl><?= $this->Form->control('phone_number', ['label' => '電話番号（ハイフンあり）','value'=>$phone_number , 'placeholder' => '052-123-4567']) ?></dl>
              <dl><?= $this->Form->control('parking', ['label' => '駐車場','placeholder' => 'あり／なし or 10台','value'=>$parking]) ?></dl>
              <dl><?= $this->Form->control('homepage', ['label' => 'ホームページ','placeholder' => 'https://fave-jp.info','value'=>$homepage]) ?></dl>
        </fieldset>

        <fieldset>
          <legend>インスタグラム</legend>
			<?= $this->Html->link($instagram, $instagram,array('target'=>'_blank')); ?>
          <dl><?= $this->Form->control('instagram', ['label' => 'インスタグラム','value'=>$instagram]) ?></dl>
          <dl><?= $this->Form->control('thumbnail', ['label' => 'サムネイル','value'=>$thumbnail]) ?></dl>

          <?php
          	$i = 1;
          	foreach ($shop_photos as $key => $shop_photo) {
          		echo '<dl>';
          		echo $this->Form->control('shop_photo'.$i, ['label' => '写真'.$i,'value'=>$shop_photo->url]);
          		echo '</dl>';
          		$i++;
   			}
   			for ($i; $i <= 3; $i++) {
          		echo '<dl>';
          		echo $this->Form->control('shop_photo'.$i, ['label' => '写真'.$i]);
          		echo '</dl>';
   			}
          ?>
            <?= $this->Form->control('confirm', ['type' => 'checkbox','label' => '確認済みにする','checked'=>true]); ?>
		</fieldset>
        <fieldset>

          <?= $this->Form->hidden('shop_id' ,['value'=> $shop_id ]) ; ?>
          <?= $this->Form->button(__('進む')); ?>
          <?= $this->Form->end(); ?>
          <?php
	        echo $this->Form->create('',['class'=>'prev' , 'url' => ['action' => 'index']]);
	        echo $this->Form->button('戻る',['class'=>'back']);
	        echo $this->Form->end();
	      ?>


        </fieldset>
      </div>
      </div>
    </article>
</div>