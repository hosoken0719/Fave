<?php ?>
<div class="contents user">
    <article class="contain search">
        <div class="sub_title">
            <h4>お店を探す</h4>
        </div>
        <div class="search_box shop">
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
                        echo $this->Form->control('word', array(
                            'type' => 'text',
                            'maxlength' => false,
                            'placeholder' => 'User Name',
                            'class' => 'search_box_line',
                            'id' => 'ac_user',
                            'label' => false
                        ));?>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </article>
</div>