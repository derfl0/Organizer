<? if($groups): ?>
    <h2><?= _('Teams') ?></h2>
<? endif; ?>

<div class="organizer_groups">
    <? foreach ($groups as $group): ?>
        <div class="organizer_group">
            <div class="organizer_users">
                <? foreach ($group->users as $user): ?>
                    <div class="organizer_user">
                        <?= Avatar::getAvatar($user->user_id)->getImageTag(); ?>
                        <p><?= htmlReady($user->getFullname()) ?></p>
                        <? if ($user->user_id == User::findCurrent()->id && !OrganizerSettings::get()->locked): ?>
                            <p>
                                <a href="<?= $controller->url_for('show/leave/' . $user->user_id) ?>"><?= _('Gruppe verlassen') ?></a>
                            </p>
                        <? endif; ?>

                        <? /* remove user user */ ?>
                        <? if ($GLOBALS['perm']->have_studip_perm('tutor', Course::findCurrent()->id)): ?>
                            <p>
                                <a href="<?= $controller->url_for('show/removeuser/' . $user->user_id) ?>"><?= _('Entfernen') ?></a>
                            </p>
                        <? endif; ?>

                    </div>
                <? endforeach; ?>
            </div>

            <? foreach ($assetslists as $list): ?>
                <?= $this->render_partial('show/_assetlist.php', array('list' => $list, 'group' => $group)); ?>
            <? endforeach; ?>

            <? /* add user */ ?>
            <? if ($GLOBALS['perm']->have_studip_perm('tutor', Course::findCurrent()->id)): ?>
                <form class="studip_form organizer_useradd" action="<?= $controller->url_for('show/adduser')?>" method="post" >
                    <?= CSRFProtection::tokenTag() ?>
                    <input type="hidden" name="group_id" value="<?= $group->id ?>">
                    <label>
                        <?= _('Person hinzufügen') ?>
                        <select name="user_id">
                            <? foreach ($waiting as $wuser): ?>
                                <? if ($wuser->user_id != $wait->user_id): ?>
                                    <option value="<?= $wuser->id ?>"><?= htmlReady($wuser->getFullname()) ?></option>
                                <? endif; ?>
                            <? endforeach ?>
                        </select>
                    </label>
                    <?= \Studip\Button::create(_('Hinzufügen'), 'add') ?>
                </form>
            <? endif; ?>

        </div>
    <? endforeach; ?>
</div>

<h2><?= _('Ohne Team') ?></h2>
<div class="organizer_waiting_list">
    <? foreach ($waiting as $wait): ?>
        <div class="organizer_user organizer_waiting_user">
            <?= Avatar::getAvatar($wait->user_id)->getImageTag(); ?>
            <p><?= htmlReady($wait->getFullname()) ?></p>

            <? /* invite user */ ?>

            <? if ($wait->user_id != User::findCurrent()->id && !$GLOBALS['perm']->have_studip_perm('tutor', Course::findCurrent()->id)): ?>
                <? if (OrganizerInvite::isInvited(User::findCurrent()->id, $wait->user_id)): ?>
                    <p>
                        <a href="<?= $controller->url_for('show/cancel/' . $wait->user_id) ?>"><?= _('Einladung zurückziehen') ?></a>
                    </p>
                <? elseif (OrganizerInvite::isInvited($wait->user_id, User::findCurrent()->id)): ?>
                    <p>
                        <a href="<?= $controller->url_for('show/accept/' . $wait->user_id) ?>"><?= _('Annehmen') ?></a>
                        <a href="<?= $controller->url_for('show/cancel/' . $wait->user_id) ?>"><?= _('Ablehnen') ?></a>
                    </p>
                <? elseif (OrganizerInvite::canInvite()): ?>
                    <p>
                        <a href="<?= $controller->url_for('show/invite/' . $wait->user_id) ?>"><?= _('Einladen') ?></a>
                    </p>
                <? endif; ?>
            <? endif; ?>

            <? /* add user */ ?>
            <? if ($GLOBALS['perm']->have_studip_perm('tutor', Course::findCurrent()->id)): ?>
                <form class="studip_form" action="<?= $controller->url_for('show/createteam')?>" method="post" >
                    <?= CSRFProtection::tokenTag() ?>
                    <input type="hidden" name="user1" value="<?= $wait->user_id ?>">
                    <label>
                        <?= _('Team gründen') ?>
                        <select name="user2">
                            <? foreach ($waiting as $wuser): ?>
                                <? if ($wuser->user_id != $wait->user_id): ?>
                                    <option value="<?= $wuser->id ?>"><?= htmlReady($wuser->getFullname()) ?></option>
                                <? endif; ?>
                            <? endforeach ?>
                        </select>
                    </label>
                    <?= \Studip\Button::create(_('Gründen'), 'add') ?>
                </form>
            <? endif; ?>
        </div>
    <? endforeach; ?>
</div>