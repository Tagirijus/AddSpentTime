<div class="page-header">
    <h2><?= t('Change complexity') ?></h2>
</div>
<form method="post" action="<?= $this->url->href('AddSpentTimeController', 'changeComplexity', ['plugin' => 'AddSpentTime', 'task_id' => $task['id'], 'project_id' => $task['project_id']]) ?>" autocomplete="off">
    <?= $this->form->csrf() ?>

    <div class="task-form-container">


        <!-- Complexity changer -->

        <div class="task-form-main-column">
            <?= $this->form->label(t('Complexity'), 'complexity') ?>
            <?= $this->form->number('complexity', [], [], [
                'autofocus',
                'required',
                'tabindex="1"',
                'value="' . $task['score'] . '"'
            ]) ?>
        </div>


        <div class="task-form-bottom">
            <?= $this->modal->submitButtons() ?>
        </div>
    </div>
</form>
