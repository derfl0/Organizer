<div data-group_id="<?= $group->id ?>" class="organizer_group organizer_ajax_replace organizer_group_drop">
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
                        <a class="organizer_auto_link" href="<?= $controller->url_for('show/removeuser/' . $user->user_id) ?>"><?= _('Entfernen') ?></a>
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
        <form class="studip_form organizer_useradd organizer_add_to_group_form" action="<?= $controller->url_for('show/adduser')?>" method="post" >
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