<?php ?>

<div class="users content">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Add User') ?></legend>
        <?php
            echo $this->Form->control('email');
            echo $this->Form->control('accountname');
            echo $this->Form->control('username');
            echo $this->Form->control('password');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>

    <?= "<a href='https://access.line.me/dialog/oauth/weblogin?response_type=code&client_id=1604848142&redirect_uri=https://fave-jp.info&state=".rand() ."&scope=profile'>aa</a>"; ?>

</div>
