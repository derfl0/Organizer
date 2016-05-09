<? foreach ($assets as $asset): ?>
    <form action="<?= $controller->url_for('assets/store') ?>" class="studip_form">
        <input type="hidden" name="id" value="<?= $asset->id ?>">
        <fieldset>
            <legend>
                <?= htmlReady($asset->name) ?>
            </legend>

            <fieldset>
                <legend>
                    <?= _('Assetdaten') ?>
                </legend>

                <label>
                    <?= _('Name') ?>
                    <input type="text" name="name" value="<?= $asset->name ?>">
                </label>

                <label>
                    <input type="checkbox" name="visible" value="1" <?= $asset->visible ? 'CHECKED' : '' ?>>
                    <?= _('Sichtbar') ?>
                </label>

                <label>
                    <input type="checkbox" name="assignable" value="1" <?= $asset->assignable ? 'CHECKED' : '' ?>>
                    <?= _('Freigegeben') ?>
                </label>

            </fieldset>

            <fieldset>
                <legend>
                    <?= _('Items') ?>
                </legend>

                <? foreach ($asset->items as $item): ?>
                    <label>
                        <input type="text" name="asset_item[<?= $item->id ?>]" value="<?= $item->name ?>">
                    </label>
                <? endforeach; ?>

                <? for ($i = 0; $i < 5; $i++): ?>
                    <label>
                        <input type="text" name="new_assets[]" placeholder="<?= _('Neu') ?>" value="">
                    </label>

                <? endfor; ?>

                <p><?= _('Neue Felder werden nach dem Speichern automatisch hinzugefügt') ?></p>
                <?= \Studip\Button::create(_('Speichern'), 'store'); ?>
                <?= \Studip\Button::create(_('Löschen'), 'delete'); ?>
            </fieldset>


        </fieldset>
    </form>

<? endforeach; ?>


<form action="<?= $controller->url_for('assets/new') ?>">
    <?= \Studip\Button::create(_('Assets hinzufügen'), 'create'); ?>
</form>