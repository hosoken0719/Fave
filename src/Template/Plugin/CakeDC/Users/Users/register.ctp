<?php
/**
 * Copyright 2010 - 2017, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2017, Cake Development Corporation (https://www.cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
use Cake\Core\Configure;

?>
<div class="contents users form">
    <div class="col-sm-12">
      <article class="content">
        <?= $this->Form->create($user,['class'=>'submits']); ?>

        <fieldset>
            <legend><?= __d('CakeDC/Users', '新規登録') ?></legend>
            <?php
            echo $this->Form->control('username', ['label' => __d('CakeDC/Users', 'ユーザ名')]);
            echo "<div class='username'><div class='require'></div></div>";
            echo $this->Form->control('email', ['label' => __d('CakeDC/Users', 'Email')]);
            echo "<div class='email'><div class='require'></div></div>";
            echo $this->Form->control('password', ['label' => __d('CakeDC/Users', 'パスワード')]);
            echo "<div class='password'><div class='require'></div></div>";
            echo $this->Form->control('password_confirm', [
                'type' => 'password',
                'label' => __d('CakeDC/Users', 'パスワード確認'),
                'required' => true
            ]);

            echo "<div class='password_confirm'><div class='require'></div></div>";
            if (Configure::read('Users.Tos.required')) {
                echo $this->Form->control('tos', ['type' => 'checkbox', 'label' => __d('CakeDC/Users', 'Accept TOS conditions?'), 'required' => true]);
            }
            if (Configure::read('Users.reCaptcha.registration')) {
                echo $this->User->addReCaptcha();
            }
            ?>
        </fieldset>
        
<?= $this->Flash->render() ?>
        <?= $this->Form->button(__d('CakeDC/Users', '登録')) ?>
        <?= $this->Form->end() ?>

        <div class="link">

          <?php
          $registrationActive = Configure::read('Users.Registration.active');
          if ($registrationActive) {
              echo $this->Html->link(__d('CakeDC/Users', 'ログイン'), ['action' => 'login']);
          }
          ?>
          <?= $this->Html->link(__d('CakeDC/Users', ' | 認証メールの再送'), ['action' => 'resendTokenValidation']) ?>
        </div>
      </article>  
    </div>
</div>

<script type="text/javascript">
    
$(".submits").submit(function(){
  var chkForm = true;
  if ($("input[name='username']").val() == ''){
      $('.username .require').text("*必須項目です"); 
      chkForm = false;
  }else{
      $('.username .require').text(''); 
  }

  if ($("input[name='email']").val() == ''){
      $('.email .require').text("*必須項目です"); 
      chkForm = false;
  }else{
      $('.email .require').text(''); 
  }
  
  if ($("input[name='password']").val() == ''){
      $('.password .require').text("*必須項目です"); 
      chkForm = false;
  }else{
      $('.password .require').text(''); 
  }

  if ($("input[name='password_confirm']").val() == ''){
      $('.password_confirm .require').text("*必須項目です"); 
      chkForm = false;
  }else{
      $('.password_confirm .require').text(''); 
  }

  return chkForm;
});

</script>
