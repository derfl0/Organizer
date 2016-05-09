<?php

class SettingsController extends StudipController
{

    public function __construct($dispatcher)
    {
        parent::__construct($dispatcher);
        $this->plugin = $dispatcher->plugin;
    }

    public function before_filter(&$action, &$args)
    {
        parent::before_filter($action, $args);

        // Have to be tutor
        $GLOBALS['perm']->check('tutor', Course::findCurrent()->id);

        $this->set_layout($GLOBALS['template_factory']->open('layouts/base_without_infobox.php'));
//      PageLayout::setTitle('');
    }

    public function index_action()
    {
        $this->settings = OrganizerSettings::get();

        if (Request::submitted('store')) {
            CSRFProtection::verifyUnsafeRequest();
            $this->settings->locked = Request::get('locked') ? 1 : 0;
            $this->settings->min_size = Request::get('min');
            $this->settings->max_size = Request::get('max');
            $this->settings->store();
        }
    }

    // customized #url_for for plugins
    function url_for($to)
    {
        $args = func_get_args();

        # find params
        $params = array();
        if (is_array(end($args))) {
            $params = array_pop($args);
        }

        # urlencode all but the first argument
        $args = array_map('urlencode', $args);
        $args[0] = $to;

        return PluginEngine::getURL($this->dispatcher->plugin, $params, join('/', $args));
    }

}