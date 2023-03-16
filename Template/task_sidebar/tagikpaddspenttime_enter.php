<div class="page-header">
    <h2><?= t('Add spent time') ?></h2>
</div>
<form method="post" action="<?= $this->url->href('TagiKPAddSpentTimeController', 'addSpentTime', ['plugin' => 'TagiKPAddSpentTime', 'task_id' => $task['id'], 'project_id' => $task['project_id']]) ?>" autocomplete="off">
    <?= $this->form->csrf() ?>

    <div class="task-form-container">
        <div class="task-form-main-column">
            <?= $this->form->label(t('Time'), 'time') ?>
            <?= $this->form->text('time', [], [], [
                'autofocus',
                'required',
                'tabindex="1"',
                'placeholder="1:00"'
            ]) ?>

            <p style="font-style: italic; font-size: .75em; opacity: .75; margin-top: 1em;">
                <?= t('Enter time to add ("0.5", "0:30")') ?>
            </p>
        </div>

        <div class="task-form-bottom">
            <?= $this->modal->submitButtons() ?>
        </div>
    </div>
</form>
