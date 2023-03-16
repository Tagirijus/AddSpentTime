<?php

namespace Kanboard\Plugin\TagiKPAddSpentTime;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Translator;


class Plugin extends Base
{
    public function initialize()
    {
        // Views - Template Hook
        $this->template->hook->attach(
            'template:task:sidebar:actions',
            'TagiKPAddSpentTime:task_sidebar/tagikpaddspenttime_button'
        );
    }

    public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
    }

    public function getPluginName()
    {
        // Plugin Name MUST be identical to namespace for Plugin Directory to detect updated versions
        // Do not translate the plugin name here
        return 'TagiKPAddSpentTime';
    }

    public function getPluginDescription()
    {
        return t('Adds a button for a task to quickly add spent time with an time-input prompt');
    }

    public function getPluginAuthor()
    {
        return 'Tagirijus';
    }

    public function getPluginVersion()
    {
        return '1.2.0';
    }

    public function getCompatibleVersion()
    {
        // Examples:
        // >=1.0.37
        // <1.0.37
        // <=1.0.37
        return '>=1.2.27';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/Tagirijus/TagiKPAddSpentTime';
    }
}
