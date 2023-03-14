<?php if ($task['is_active'] == 1): ?>
<li>
<?= $this->modal->small('clock-o', t('Add spent time'), 'TagiAddSpentTimeController', 'enter', array('plugin' => 'TagiAddSpentTime', 'task_id' => $task['id'])) ?>
</li>
<?php endif ?>
