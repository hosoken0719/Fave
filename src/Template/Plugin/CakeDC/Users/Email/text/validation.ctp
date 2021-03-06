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

$activationUrl = [
    '_full' => true,
    'plugin' => 'CakeDC/Users',
    'controller' => 'Users',
    'action' => 'validateEmail',
    isset($token) ? $token : ''
];
?>
<?= __d('CakeDC/Users', "ご登録ありがとうございます。
"); ?>

<?= __d(
    'CakeDC/Users',
    "ご本人様確認のため、24時間以内に下記のURLへアクセスしアカウントの本登録を完了させて下さい。
{0}",$this->Url->build($activationUrl)
) ?>

<?= __d('CakeDC/Users', '
Fave') ?>