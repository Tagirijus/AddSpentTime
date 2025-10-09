<?php

$html = '<i class="fa fa-trophy fa-fw js-modal-small" aria-hidden="true"></i>' . t('Change complexity');
$href = $this->helper->url->href(
    'AddSpentTimeController',
    'enterComplexity',
    ['plugin' => 'AddSpentTime', 'task_id' => $task['id']]
);
$a_element = '<a href="' . $href . '" class="js-modal-small" id="changeComplexityMenu" data-addUrl="' . $href . '">' . $html . '</a>';

?>

<?php if ($task['is_active'] == 1): ?>
<li>
    <?php echo $a_element; ?>
</li>
<?php endif ?>
