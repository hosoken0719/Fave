<?php ?>

<div class="contents regist">
	<div class="col-sm-12">
		<article class="content">
			<div class="form">

                <?= $this->Form->create('',['url' => ['controller' => 'ShopUpdates', 'action' => 'confirm']]) ?>

                <fieldset>

                    <legend><?= $shopname ?></legend>
                        <label>営業時間</label>

<?php for($count = 1; $count <= 3; $count++)://営業時間は3つ出力する ?>
                        <?php  //2行目からは非表示する
                            if(($count === 2 or $count === 3) and $flgDisplay[$count] === 0):
                                echo "<div class='hidden_box'>";
                                echo "<input type='checkbox' id='label{$count}'/>";
                                echo "<label for='label{$count}' class='label_add' type='button'>営業時間を追加する</label>";
                                echo "<div class='hidden_show'>";
                            endif;
                        ?>

                        <div class="week_set">

                        <?php //曜日ボタン開始
                            for($count_week = 0; $count_week <= 6; $count_week++):
                                echo "<input type='hidden' name='week{$count}_{$week_en[$count_week]}' value='close'>";
                                echo "<input type='checkbox' id='week{$count}_{$week_en[$count_week]}' name='week{$count}_{$week_en[$count_week]}' value='open' {$week_value[$week_en[$count_week]][$count]} '>";
                                echo "<label for='week{$count}_{$week_en[$count_week]}' data-on-label='{$week_ja[$count_week]}' data-off-label='{$week_ja[$count_week]}'></label>";
                            endfor;
                        ?>

                        </div>
                        <div class='bussinesshour'>
                            <ul>
                                <li>
                                    <?php //開始時間のselectbox
                                        echo "<select name='week{$count}_start'>";
                                        foreach ($select_time_s[$count] as $time):
                                            echo $time;
                                        endforeach;
                                        echo "</select>";
                                    ?>
                                </li>
                                <li>
                                     -
                                </li>
                                <li>
                                    <?php //終了時間のselectbox
                                        echo "<select name='week{$count}_end'>";
                                        foreach ($select_time_e[$count] as $time):
                                            echo $time;
                                        endforeach;
                                        echo "</select>";
                                    ?>
                                </li>
                            </ul>
                        </div>
                        <!-- <hr /> -->
<?php endfor; //営業時間の出力終了?>
                        <?php  //2行目からは非表示する
                            if($flgDisplay[2] === 0) echo '</div></div>';
                            if($flgDisplay[3] === 0) echo '</div></div>';
                        ?>
                    <?= $this->Form->control('tel', ['label' => '電話番号（ハイフンあり）','value'=>$tel]) ?>
                    <?= $this->Form->control('parking', ['label' => '駐車場','placeholder' => 'あり／なし or 10台','value'=>$parking]) ?>
                    <?= $this->Form->control('homepage', ['label' => 'ホームページ','placeholder' => 'https://fave-jp.info','value'=>$homepage]) ?>
                    <?= $this->Form->control('introduction', ['label' => '紹介','value'=>$introduction]) ?>

                    <?= $this->Form->button(__('確認')) ?>
                    <?= $this->Form->end() ?>


                </fieldset>

            </div>
        </article>
    </div>
</div>