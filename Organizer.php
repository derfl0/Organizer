<?php
require 'bootstrap.php';

/**
 * SandkastenPlugin.class.php
 *
 * ...
 *
 * @author  Florian Bieringer <florian.bieringer@uni-passau.de>
 * @version 0.1a
 */
class Organizer extends StudIPPlugin implements StandardPlugin
{

    public function perform($unconsumed_path)
    {
        self::addStylesheet('/assets/organizer.less');
        PageLayout::addScript($this->getPluginURL() . '/assets/organizer.js');
        $this->setupAutoload();
        $dispatcher = new Trails_Dispatcher(
            $this->getPluginPath(),
            rtrim(PluginEngine::getLink($this, array(), null), '/'),
            'show'
        );
        $dispatcher->plugin = $this;
        $dispatcher->dispatch($unconsumed_path);
    }

    private function setupAutoload()
    {
        if (class_exists('StudipAutoloader')) {
            StudipAutoloader::addAutoloadPath(__DIR__ . '/models');
        } else {
            spl_autoload_register(function ($class) {
                include_once __DIR__ . $class . '.php';
            });
        }
    }

    /**
     * Return a template (an instance of the Flexi_Template class)
     * to be rendered on the course summary page. Return NULL to
     * render nothing for this plugin.
     *
     * The template will automatically get a standard layout, which
     * can be configured via attributes set on the template:
     *
     *  title        title to display, defaults to plugin name
     *  icon_url     icon for this plugin (if any)
     *  admin_url    admin link for this plugin (if any)
     *  admin_title  title for admin link (default: Administration)
     *
     * @return object   template object to render or NULL
     */
    function getInfoTemplate($course_id)
    {
        // TODO: Implement getInfoTemplate() method.
    }

    /**
     * Return a navigation object representing this plugin in the
     * course overview table or return NULL if you want to display
     * no icon for this plugin (or course). The navigation object's
     * title will not be shown, only the image (and its associated
     * attributes like 'title') and the URL are actually used.
     *
     * By convention, new or changed plugin content is indicated
     * by a different icon and a corresponding tooltip.
     *
     * @param  string $course_id course or institute range id
     * @param  int $last_visit time of user's last visit
     * @param  string $user_id the user to get the navigation for
     *
     * @return object   navigation item to render or NULL
     */
    function getIconNavigation($course_id, $last_visit, $user_id)
    {
        // TODO: Implement getIconNavigation() method.
    }

    /**
     * Return a navigation object representing this plugin in the
     * course overview table or return NULL if you want to display
     * no icon for this plugin (or course). The navigation object's
     * title will not be shown, only the image (and its associated
     * attributes like 'title') and the URL are actually used.
     *
     * By convention, new or changed plugin content is indicated
     * by a different icon and a corresponding tooltip.
     *
     * @param  string $course_id course or institute range id
     *
     * @return array    navigation item to render or NULL
     */
    function getTabNavigation($course_id)
    {
        $navigation = new AutoNavigation(_('Organizer'));
        $navigation->setURL(PluginEngine::GetURL($this, array(), 'show/index'));
        $navigation->setImage(Assets::image_path('icons/16/white/community.png'));
        $navigation->setActiveImage(Assets::image_path('icons/16/black/community.png'));
        $navigation->addSubNavigation('index', new AutoNavigation(_('Teams'), PluginEngine::GetURL($this, array(), 'show/index')));

        if ($GLOBALS['perm']->have_studip_perm('tutor', Course::findCurrent()->id)) {
            $navigation->addSubNavigation('assets', new AutoNavigation(_('Assets'), PluginEngine::GetURL($this, array(), 'assets/index')));
            $navigation->addSubNavigation('settings', new AutoNavigation(_('Einstellungen'), PluginEngine::GetURL($this, array(), 'settings/index')));
        }
        return array('organizer' => $navigation);
    }

    /**
     * return a list of ContentElement-objects, conatinging
     * everything new in this module
     *
     * @param  string $course_id the course-id to get the new stuff for
     * @param  int $last_visit when was the last time the user visited this module
     * @param  string $user_id the user to get the notifcation-objects for
     *
     * @return array an array of ContentElement-objects
     */
    function getNotificationObjects($course_id, $since, $user_id)
    {
        // TODO: Implement getNotificationObjects() method.
    }

}
