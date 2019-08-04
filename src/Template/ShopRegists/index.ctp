<?php ?>
<div class="contents regist">
    <article class="contain-white">
    <div class="contain_inner">
			<div class="form">
        <fieldset class="basic">
          <legend><?= "基本情報" ?></legend>
          <?= $this->Form->create('',['class'=>'next','url' => ['controller' => 'ShopRegists', 'action' => 'checkshopname'],'templates' => $template]); ?>
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
        <fieldset class="open_hour">
          <legend>営業時間</legend>
              <dl>
                <dt>営業時間1</dt>
                <dd><?= $this->element('open_hour',['count'=>1]) ?>
                <button id='add_open_hour' class='add_open_hour button1 <?= $flgDisplay[1]['button'] ?>'>営業時間を追加する</button></dd>
              </dl>
              <dl class='open_hour2 <?= $flgDisplay[2]['open_hour'] ?>'>
                <dt>営業時間2</dt>
                <dd><?= $this->element('open_hour',['count'=>2]) ?>
                <button id='add_open_hour' class='add_open_hour button2 <?= $flgDisplay[2]['button'] ?>'>営業時間を追加する</button></dd>
                </dd>
              </dl>
              <dl class='open_hour3 <?= $flgDisplay[3]['open_hour'] ?>'>
                <dt>営業時間3</dt>
                <dd><?= $this->element('open_hour',['count'=>3]) ?></dd>
              </dl>
              <dl class='business_hour_detail'>
                <dt>営業時間備考</dt>
                <dd>
                <?= $this->Form->control('business_hour_detail', [
                'placeholder' => '詳細情報を記入して下さい（例）第3月曜は休業日',
                'rows' => 3,
                'label' => false,
                'id' => 'input_review',
                'value' => $business_hour_detail,
                'maxlength' => 1500,
                ]);
                ?>
                </dd></dl>
        </fieldset>
        <fieldset>
          <legend>詳細情報</legend>
              <dl><?= $this->Form->control('phone_number', ['label' => '電話番号（ハイフンあり）','value'=>$phone_number , 'placeholder' => '052-123-4567']) ?></dl>
              <dl><?= $this->Form->control('parking', ['label' => '駐車場','placeholder' => 'あり／なし or 10台','value'=>$parking]) ?></dl>
              <dl><?= $this->Form->control('homepage', ['label' => 'ホームページ','placeholder' => 'https://fave-jp.info','value'=>$homepage]) ?></dl>
          <?= $this->Form->button(__('進む')); ?>
          <?= $this->Form->end(); ?>

        </fieldset>
      </div>
      </div>
    </article>
</div>

<script type="text/javascript">
  $(".next").submit(function(){
    var chkForm = true;
    var check_require = function(type,tag){
      if ($(type+"[name="+tag+"]").val() == ''){
        $('.'+tag+' .require').text("*必須項目です"); 
        chkForm = false;
      }else{
        $('.'+tag+' .require').text(''); 
      }
    }
    
    check_tag = ['shopname','kana','address'];
    check_tag.forEach(function(tag){
      check_require('input',tag)
    })
    
    check_tag = ['pref','shoptype'];
    check_tag.forEach(function(tag){
      check_require('select',tag)
    })

    var str = $("input[name='kana']").val();
    if(!str.match(/^[ァ-ヶー　]*$/)){  
      $('.kana .require').text("*全角カタカタで入力下さい"); 
      chkForm = false;
    }
    return chkForm;
  });


$("dt:contains('必須')").addClass('text-danger');


    //「営業時間を追加する」ボタン
  $(".button1").on("click",function(){
    $('.open_hour2').removeClass('hide'); //お気に入り登録画面の表示
    $('.button1').addClass('hide'); //お気に入り登録画面の表示
    return false
  });

  $(".button2").on("click",function(){
    $('.open_hour3').removeClass('hide'); //お気に入り登録画面の表示
    $('.button2').addClass('hide'); //お気に入り登録画面の表示
    return false
  });
</script>
