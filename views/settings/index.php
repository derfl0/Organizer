<form class="studip_form" method="post">
    <?= CSRFProtection::tokenTag() ?>
    <fieldset>
        <legend>
            <?= _('Einstellungen') ?>
        </legend>

        <label>
            <input type="checkbox" name="locked" value="1" <?= $settings->locked ? 'CHECKED' : '' ?>>
            <?= _('Gesperrt') ?>
        </label>

        <label>
            <?= _('Minimale Gruppengröße') ?>
            <input name="min" type="number" min="1" value="<?= htmlReady($settings->min_size) ?>">
        </label>

        <label>
            <?= _('Maximale Gruppengröße') ?>
            <input name="max" type="number" min="1" value="<?= htmlReady($settings->max_size) ?>">
        </label>

        <?= \Studip\Button::create(_('Übernehmen'), 'store') ?>
    </fieldset>
</form>