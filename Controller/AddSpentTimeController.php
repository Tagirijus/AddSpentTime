<?php

namespace Kanboard\Plugin\AddSpentTime\Controller;

use Kanboard\Core\Controller\AccessForbiddenException;


class AddSpentTimeController extends \Kanboard\Controller\PluginController
{
    /**
     * Show the modal for entering the spent time.
     *
     * @return HTML response
     */
    public function enter()
    {
        $task = $this->getTask();
        $subtasks = $this->subtaskModel->getAllByTaskIds([$task['id']]);
        $user = $this->getUser();

        if ($user['username'] !== $task['assignee_username']) {
            throw new AccessForbiddenException();
        }

        $this->response->html($this->template->render(
            'AddSpentTime:task_sidebar/addspenttime_enter', [
                'task' => $task,
                'subtasks' => $subtasks,
                'user' => $user
            ]
        ));
    }

    /**
     * Show the modal for entering the estimated time.
     *
     * @return HTML response
     */
    public function enterEstimated()
    {
        $task = $this->getTask();
        $subtasks = $this->subtaskModel->getAllByTaskIds([$task['id']]);
        $user = $this->getUser();

        if ($user['username'] !== $task['assignee_username']) {
            throw new AccessForbiddenException();
        }

        $this->response->html($this->template->render(
            'AddSpentTime:task_sidebar/changeestimatedtime_enter', [
                'task' => $task,
                'subtasks' => $subtasks,
                'user' => $user
            ]
        ));
    }

    /**
     * Show the modal for entering the complexity.
     *
     * @return HTML response
     */
    public function enterComplexity()
    {
        $task = $this->getTask();
        $user = $this->getUser();

        if ($user['username'] !== $task['assignee_username']) {
            throw new AccessForbiddenException();
        }

        $this->response->html($this->template->render(
            'AddSpentTime:task_sidebar/changecomplexity_enter', [
                'task' => $task,
                'user' => $user
            ]
        ));
    }

    /**
     * Wrapper for changeTaskTime(), which will alter the
     * tasks spent time.
     */
    public function addSpentTime()
    {
        return $this->changeTaskTime('spent');
    }

    /**
     * Wrapper for changeTaskTime(), which will alter the
     * tasks estimated time.
     */
    public function changeEstimatedTime()
    {
        return $this->changeTaskTime('estimated');
    }

    /**
     * Add the spent time and redirect to the task / refresh the task.
     *
     * @param string $which
     */
    public function changeTaskTime($which = 'spent')
    {
        // spent or estimated to change?
        if ($which == 'estimated') {
            $time_key = 'time_estimated';
            $success_message = t('Estimated time changed.');
            $failure_message = t('Unable to change estimated time.');
        } else {
            $time_key = 'time_spent';
            $success_message = t('Spent time added.');
            $failure_message = t('Unable to add spent time.');
        }

        // method starts here
        $task = $this->getTask();
        $user = $this->getUser();
        $subtask = false;
        $this->checkCSRFForm();

        $form = $this->request->getValues();

        if (array_key_exists('subtask', $form)) {
            $subtask = $this->subtaskModel->getById($form['subtask']);
        }

        if ($user['username'] !== $task["assignee_username"]) {
            throw new AccessForbiddenException();
        }

        // get the float (hours) to add to the time_spent
        $add = $this->parseTime((string) $form['time']);

        // get only data to modify and modify the time_spent of the task already
        $task_modification =[
            'id' => $task['id'],
            $time_key => number_format($task[$time_key] + $add, 2),
            'date_started' => $task['date_started']
        ];

        // set date_started retrospective in case it was not set
        if ($task_modification['date_started'] == 0) {
            $task_modification['date_started'] = time() - round($add * 3600);
        }

        if ($this->taskModificationModel->update($task_modification, false)) {

            // change subtask as well
            if ($subtask !== false && !is_null($subtask)) {
                $values_subtask = [
                    'id' => $subtask['id'],
                    'task_id' => $task['id'],
                    $time_key => number_format($subtask[$time_key] + $add, 2),
                    // in either way set the status of the subtask
                    // to "being edited", since time is going to be
                    // added anyway, thus it is being worked on, probably
                    // (even when the estimated time is exceeded!)
                    'status' => 1,
                ];
                if ($this->subtaskModel->update($values_subtask)) {
                    $this->flash->success($success_message);
                } else {
                    $this->flash->failure($failure_message);
                }
            } else {
                $this->flash->success($success_message);
            }

        } else {
            $this->flash->failure($failure_message);
        }

        return $this->response->redirect($this->helper->url->to('TaskViewController', 'show', ['task_id' => $task['id']]), true);
    }

    /**
     * This function is for interpreting the given time
     * input string.
     *
     * 0.5 (float) will be hours
     * 0:30 (string) will be hours and minutes
     * 30 (integer) will be minutes
     *
     * @param  string $time
     * @return float
     */
    private function parseTime($time)
    {
        // for the german freaks like me, who might use , instead of .
        $time = str_replace(',', '.', $time);

        // maybe it's a time formatted string like "1:45" ...
        if (strpos($time, ':') !== false) {
            // remove the negative minus and get just the info
            // that the number has to be multiplied by -1,
            // when the uer entered e.g. "-0:30". It'S kind
            // of a monkey patch maybe
            if (strpos($time, '-') !== false) {
                $multiply = -1;
                $time = str_replace('-', '', $time);
            } else {
                $multiply = 1;
            }

            // ... then convert it to a float
            $hours = explode(':', $time)[0];
            $minutes = explode(':', $time)[1];
            $time = ((float) $hours + (float) $minutes / 60) * $multiply;

        // no float, thus minutes entered
        } elseif (strpos($time, '.') === false) {
            $time = (float) $time / 60;
        }

        return (float) $time;
    }
}