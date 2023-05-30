<div class="page-header">
    <h2><?= t('Add spent time') ?></h2>
</div>
<form method="post" action="<?= $this->url->href('AddSpentTimeController', 'addSpentTime', ['plugin' => 'AddSpentTime', 'task_id' => $task['id'], 'project_id' => $task['project_id']]) ?>" autocomplete="off">
    <?= $this->form->csrf() ?>

    <div class="task-form-container">

        <!-- Subtask chooser, if available -->
        <?php if (!empty($subtasks)): ?>

            <?php
                $prepared_subtasks = [];
                foreach ($subtasks as $subtask) {
                    $prepared_subtasks[$subtask['id']] = $subtask['title'];
                }
                $prepared_subtasks[-999] = '--- MAIN: ' . $task['title'] . ' ---';
            ?>

            <div class="task-form-main-column">
                <?= $this->form->label(t('Subtask'), 'subtask') ?>
                <?= $this->form->select('subtask', $prepared_subtasks, [], [], [
                    'required',
                    'tabindex="2"'
                ]) ?>

            </div>

        <?php endif ?>

        <!-- Time adder -->

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
