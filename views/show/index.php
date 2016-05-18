<? if($groups): ?>
    <h2><?= _('Teams') ?></h2>
<? endif; ?>

<div class="organizer_groups">
    <? foreach ($groups as $group): ?>
        <?= $this->render_partial('show/_group.php', array('waiting' => $waiting, 'group' => $group)); ?>
    <? endforeach; ?>
</div>

<h2><?= _('Ohne Team') ?></h2>
<div class="organizer_waiting_list">
    <? foreach ($waiting as $wait): ?>
        <div data-user_id="<?= $wait->user_id ?>" class="organizer_user organizer_user_drag organizer_user_drop organizer_waiting_user">
            <?= Avatar::getAvatar($wait->user_id)->getImageTag(); ?>
            <p><?= htmlReady($wait->getFullname()) ?></p>

            <? /* invite user */ ?>

            <? if ($wait->user_id != User::findCurrent()->id && !$GLOBALS['perm']->have_studip_perm('tutor', Course::findCurrent()->id)): ?>
                <? if (OrganizerInvite::isInvited(User::findCurrent()->id, $wait->user_id)): ?>
                    <p>
                        <a class="organizer_auto_link" href="<?= $controller->url_for('show/cancel/' . $wait->user_id) ?>"><?= _('Einladung zurückziehen') ?></a>
                    </p>
                <? elseif (OrganizerInvite::isInvited($wait->user_id, User::findCurrent()->id)): ?>
                    <p>
                        <a class="organizer_auto_link" href="<?= $controller->url_for('show/accept/' . $wait->user_id) ?>"><?= _('Annehmen') ?></a>
                        <a class="organizer_auto_link" href="<?= $controller->url_for('show/cancel/' . $wait->user_id) ?>"><?= _('Ablehnen') ?></a>
                    </p>
                <? elseif (OrganizerInvite::canInvite()): ?>
                    <p>
                        <a class="organizer_auto_link" href="<?= $controller->url_for('show/invite/' . $wait->user_id) ?>"><?= _('Einladen') ?></a>
                    </p>
                <? endif; ?>
            <? endif; ?>

            <? /* add user */ ?>
            <? if ($GLOBALS['perm']->have_studip_perm('tutor', Course::findCurrent()->id)): ?>
                <form class="studip_form organizer_useradd organizer_new_group" action="<?= $controller->url_for('show/createteam')?>" method="post" >
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