<?php ?>

<div class="contents regist">
	<div class="col-sm-12">
		<article class="content">
			<div class="form">


<?= $this->Form->create('',['url' => ['action' => 'register']]) ?>

    <fieldset>
      <legend><?= '変更内容確認' ?></legend>
      <table class="brwsr1">
        <tbody>
          <tr>
            <th>ショップ名</th><td><?= $shopname ?></td>
          </tr>
          <tr>
            <th>営業時間</th><td></td>
          </tr>
          <tr>
            <th>駐車場</th><td><?= $parking ?></td>
          </tr>
          <tr>
            <th>ホームページ</th><td><?= $homepage ?></td>
          </tr>
          <tr>
            <th>紹介</th><td><?= $introduction ?></td>
          </tr>
        </tbody>
    </table>
            
    <?= $this->Form->button(__('登録')) ?>
    <?= $this->Form->end() ?>
    <?= $this->Form->create('',['class'=>'prev' , 'url' => ['action' => 'option2']]) ?>
    <?= $this->Form->button('戻る',['class'=>'back']); ?>
    <?= $this->Form->end() ?>
    </fieldset>

      </div>
    </article>
  </div>
</div>