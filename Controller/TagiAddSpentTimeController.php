<?php

namespace Kanboard\Plugin\TagiAddSpentTime\Controller;

use Kanboard\Core\Controller\AccessForbiddenException;


class TagiAddSpentTimeController extends \Kanboard\Controller\PluginController
{
    /**
     * Show the modal for entering the spent time.
     *
     * @return HTML response
     */
    public function enter()
    {
        $task = $this->getTask();
        $user = $this->getUser();

        if ($user['username'] !== $task['assignee_username']) {
            throw new AccessForbiddenException();
        }

        $this->response->html($this->template->render(
            'TagiAddSpentTime:task_sidebar/tagiaddspenttime_enter', [
                'task' => $task,
                'user' => $user
            ]
        ));
    }

    /**
     * Add the spent time and redirect to the task / refresh the task.
     */
    public function addSpentTime()
    {
        $task = $this->getTask();
        $user = $this->getUser();
        $this->checkCSRFForm();

        $form = $this->request->getValues();

        if ($user['username'] !== $task["assignee_username"]) {
            throw new AccessForbiddenException();
        }

        $add = $this->parseTime((string) $form['time']);
        $values =['id' => $task['id'], 'time_spent' => number_format($task['time_spent'] + $add, 2)];

        if ($this->taskModificationModel->update($values, false)) {
            $this->flash->success(t('Spent time added.'));
        } else {
            $this->flash->failure(t('Unable to add spent time.'));
        }

        return $this->response->redirect($this->helper->url->to('TaskViewController', 'show', ['task_id' => $task['id']]), true);
    }

    /**
     * This function is for interpreting the given time
     * input string.
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
            // ... then convert it to a float
            $hours = explode(':', $time)[0];
            $minutes = explode(':', $time)[1];
            $time = (float) $hours + (float) $minutes / 60;
        }

        return (float) $time;
    }
}