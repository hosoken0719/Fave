<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete User'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['action' => 'add']) ?> </li>
<!--         <li><?= $this->Html->link(__('List Shops'), ['controller' => 'Shops', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Shop'), ['controller' => 'Shops', 'action' => 'add']) ?> </li> -->
        <!-- <li><?= $this->Html->link(__('List Tags'), ['controller' => 'Tags', 'action' => 'index']) ?> </li> -->
        <!-- <li><?= $this->Html->link(__('New Tag'), ['controller' => 'Tags', 'action' => 'add']) ?> </li> -->
    </ul>
</nav>
<div class="users view large-9 medium-8 columns content">
    <h3><?= h($user->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Username') ?></th>
            <td><?= h($user->username) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Password') ?></th>
            <td><?= h($user->password) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Email') ?></th>
            <td><?= h($user->email) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($user->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Role') ?></th>
            <td><?= $this->Number->format($user->role) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Last Login At') ?></th>
            <td><?= h($user->last_login_at) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($user->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($user->modified) ?></td>
        </tr>
    </table>
<!--     <div class="related">
        <h4><?= __('Related Tags') ?></h4>
        <?php if (!empty($user->tags)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Tag') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($user->tags as $tags): ?>
            <tr>
                <td><?= h($tags->id) ?></td>
                <td><?= h($tags->tag) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Tags', 'action' => 'view', $tags->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Tags', 'action' => 'edit', $tags->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Tags', 'action' => 'delete', $tags->id], ['confirm' => __('Are you sure you want to delete # {0}?', $tags->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div> -->
   <!--  <div class="related">
        <h4><?= __('Related Shops') ?></h4>
        <?php if (!empty($user->shops)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('User Id') ?></th>
                <th scope="col"><?= __('Accountname') ?></th>
                <th scope="col"><?= __('Status') ?></th>
                <th scope="col"><?= __('Shopname') ?></th>
                <th scope="col"><?= __('Shoptype') ?></th>
                <th scope="col"><?= __('Pref') ?></th>
                <th scope="col"><?= __('City') ?></th>
                <th scope="col"><?= __('Ward') ?></th>
                <th scope="col"><?= __('Town') ?></th>
                <th scope="col"><?= __('Building') ?></th>
                <th scope="col"><?= __('Lat') ?></th>
                <th scope="col"><?= __('Lng') ?></th>
                <th scope="col"><?= __('Businesshour-s') ?></th>
                <th scope="col"><?= __('Businesshour-e') ?></th>
                <th scope="col"><?= __('Holiday') ?></th>
                <th scope="col"><?= __('Tel') ?></th>
                <th scope="col"><?= __('Parking') ?></th>
                <th scope="col"><?= __('Url') ?></th>
                <th scope="col"><?= __('Photo') ?></th>
                <th scope="col"><?= __('Introduction') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($user->shops as $shops): ?>
            <tr>
                <td><?= h($shops->id) ?></td>
                <td><?= h($shops->user_id) ?></td>
                <td><?= h($shops->accountname) ?></td>
                <td><?= h($shops->status) ?></td>
                <td><?= h($shops->shopname) ?></td>
                <td><?= h($shops->shoptype) ?></td>
                <td><?= h($shops->pref) ?></td>
                <td><?= h($shops->city) ?></td>
                <td><?= h($shops->ward) ?></td>
                <td><?= h($shops->town) ?></td>
                <td><?= h($shops->building) ?></td>
                <td><?= h($shops->lat) ?></td>
                <td><?= h($shops->lng) ?></td>
                <td><?= h($shops->businesshour-s) ?></td>
                <td><?= h($shops->businesshour-e) ?></td>
                <td><?= h($shops->holiday) ?></td>
                <td><?= h($shops->tel) ?></td>
                <td><?= h($shops->parking) ?></td>
                <td><?= h($shops->url) ?></td>
                <td><?= h($shops->photo) ?></td>
                <td><?= h($shops->introduction) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Shops', 'action' => 'view', $shops->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Shops', 'action' => 'edit', $shops->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Shops', 'action' => 'delete', $shops->id], ['confirm' => __('Are you sure you want to delete # {0}?', $shops->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div> -->
</div>
