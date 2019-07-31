<? //$this->element('open_hour',['count'=>int])  ?>
<div class="week_set">

<?php //曜日ボタン開始
for($count_week = 0; $count_week <= 6; $count_week++):
    echo "<input type='hidden' name='week{$count}_{$week_en[$count_week]}' value='close'>";
    echo "<input type='checkbox' id='week{$count}_{$week_en[$count_week]}' name='week{$count}_{$week_en[$count_week]}' value='open' {$week_value[$week_en[$count_week]][$count]} >";
    echo "<label for='week{$count}_{$week_en[$count_week]}' data-on-label='{$week_ja[$count_week]}' data-off-label='{$week_ja[$count_week]}'></label>";
endfor;

?>
</div>
<div class='bussinesshour'>
<ul>
    <li>

        <?php //開始時間のselectbox
             echo $this->Form->control('week'.$count.'_start',
                ['options' => $open_hour_list,
                'type' => 'select',
                'default' => $default_hour['open'][$count],
                'label' => ''
            ]);
        ?>
    </li>
    <li>
         -
    </li>
    <li>
        <?php //終了時間のselectbox
             echo $this->Form->control('week'.$count.'_end',
                ['options' => $open_hour_list,
                'type' => 'select',
                'default' => $default_hour['close'][$count],
                'label' => ''
            ]);
        ?>
    </li>
</ul>
</div>