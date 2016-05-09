<? if ($GLOBALS['perm']->have_studip_perm('tutor', Course::findCurrent()->id) || ($list->visible && $list->assignable && $group->hasMinTeamSize() && $group->isMember(User::findCurrent()->id))) :?>
<form class="studip_form organizer_auto_form" action="<?= $controller->url_for('show/asset/' . $list->id) ?>" method="post">
    <input type="hidden" name="list" value="<?= $list->id ?>">
    <input type="hidden" name="group_id" value="<?= $group->id ?>">
    <label>
        <?= htmlReady($list->name) ?>
        <select name="item_id">
            <option></option>
            <? foreach ($list->items as $item): ?>
                <option
                    value="<?= $item->id ?>" <?= $item->assign_id == $group->id ? 'SELECTED' : '' ?> <?= !$GLOBALS['perm']->have_studip_perm('tutor', Course::findCurrent()->id) && $item->assign_id && $item->assign_id != $group->id ? 'disabled' : '' ?>><?= htmlReady($item->name) ?></option>
            <? endforeach ?>
        </select>
    </label>
    <?= \Studip\Button::create(_('Übernehmen'), 'accept') ?>
</form>
    <? elseif ($list->visible): ?>
    <dl>
        <dt><?= htmlReady($list->name) ?></dt>
        <dd><?= htmlReady($list->getGroupChoise($group->id)) ?></dd>
    </dl>
<? endif; ?>