<?php

class AssetsController extends StudipController
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
        $this->assets = OrganizerAsset::findBySQL('course_id = ?', array(Course::findCurrent()->id));
    }

    public function new_action()
    {
        $asset = new OrganizerAsset();
        $asset->course_id = Course::findCurrent()->id;
        $asset->name = _('Neu');
        $asset->store();
        $this->redirect('assets/index');
    }

    public function store_action()
    {

        $asset = new OrganizerAsset(Request::get('id'));
        if (Request::submitted('delete')) {
            $asset->delete();
        } else {
            $asset->name = Request::get('name');
            $asset->assignable = Request::get('assignable');
            $asset->visible = Request::get('visible');
            $asset->store();

            // Store new items
            foreach (Request::getArray('new_assets') as $item) {
                if (!empty($item)) {
                    $newitem = new OrganizerItem();
                    $newitem->asset_id = $asset->id;
                    $newitem->name = $item;
                    $newitem->store();
                }
            }

            // Replace old items
            foreach (Request::getArray('asset_item') as $id => $item) {
                if ($olditem = $asset->items->findOneBy('item_id', $id)) {
                    if ($item) {
                        $olditem->name = $item;
                        $olditem->store();
                    } else {
                        $olditem->delete();
                    }
                }
            }
        }

        $this->redirect('assets/index');
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