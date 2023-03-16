<?php

$html = '<i class="fa fa-clock-o fa-fw js-modal-small" aria-hidden="true"></i>' . t('Add spent time');
$href = $this->helper->url->href(
    'AddSpentTimeController',
    'enter',
    ['plugin' => 'AddSpentTime', 'task_id' => $task['id']]
);
$a_element = '<a href="' . $href . '" class="js-modal-small" id="addSpentTimeMenu" data-addUrl="' . $href . '">' . $html . '</a>';

?>

<?php if ($task['is_active'] == 1): ?>
<li>
    <?php echo $a_element; ?>
</li>
<?php endif ?>
