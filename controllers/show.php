<?php

class ShowController extends StudipController
{

    public function __construct($dispatcher)
    {
        parent::__construct($dispatcher);
        $this->plugin = $dispatcher->plugin;

        if (Request::isXhr()) {
            $this->set_layout(null);
            $this->set_content_type('text/html;Charset=windows-1252');
        } else {
            $this->set_layout($GLOBALS['template_factory']->open('layouts/base'));
        }
    }

    public function index_action()
    {
        $this->waiting = User::findBySQL("JOIN seminar_user USING (user_id) WHERE Seminar_id = ? AND status = 'autor' AND NOT EXISTS (SELECT 1 FROM organizer_groups JOIN organizer_groupuser USING (group_id) WHERE course_id = Seminar_id AND organizer_groupuser.user_id = seminar_user.user_id)", array(Course::findCurrent()->id));
        $this->groups = OrganizerGroup::findBySQL('course_id = ?', array(Course::findCurrent()->id));
        $this->assetslists = OrganizerAsset::findBySQL('course_id = ?', array(Course::findCurrent()->id));
    }

    public function invite_action($user_id)
    {
        // check if settings allow self assigning
        if (OrganizerInvite::canInvite()) {
            OrganizerInvite::invite($user_id);
        }
        $this->redirect('show/index');
    }

    public function accept_action($user_id)
    {

        // check if settings allow self assigning
        if (!OrganizerSettings::get()->locked && !OrganizerGroup::findGroup($user_id)->hasMaxTeamSize()) {
            OrganizerInvite::accept($user_id);
        }

        // Remove existing invites
        OrganizerInvite::cancel($user_id, User::findCurrent()->id);
        $this->redirect('show/index');
    }

    public function leave_action()
    {
        if (!OrganizerSettings::get()->locked) {
            $group = OrganizerGroup::findGroup(User::findCurrent()->id);
            $group->removeUser(User::findCurrent()->id);
        }
        $this->redirect('show/index');
    }

    public function cancel_action($user_id)
    {
        OrganizerInvite::cancel($user_id);
        $this->redirect('show/index');
    }

    public function createteam_action()
    {
        CSRFProtection::verifyUnsafeRequest();
        $GLOBALS['perm']->check('tutor', Course::findCurrent()->id);
        OrganizerGroup::createGroup(Request::get('user1'), Request::get('user2'));
        $this->redirect('show/index');
    }

    public function adduser_action()
    {
        CSRFProtection::verifyUnsafeRequest();
        $GLOBALS['perm']->check('tutor', Course::findCurrent()->id);
        $group = OrganizerGroup::find(Request::get('group_id'));
        $group->addUser(Request::get('user_id'));
        $this->redirect('show/index');
    }

    public function removeuser_action($user_id)
    {
        $GLOBALS['perm']->check('tutor', Course::findCurrent()->id);
        $group = OrganizerGroup::findGroup($user_id);
        $group->removeUser($user_id);
        $this->redirect('show/index');
    }

    public function asset_action($list_id)
    {
        $item = OrganizerItem::find(Request::get('item_id'));
        $group = OrganizerGroup::find(Request::get('group_id'));
        $tutor = (bool)$GLOBALS['perm']->have_studip_perm('tutor', $item->list->course_id ?: Course::findCurrent()->id);
        if (!$item) {
            $list = OrganizerAsset::find(Request::get('list'));
            if ($GLOBALS['perm']->have_studip_perm('tutor', $list->course_id) || $group->isMember(User::findCurrent()->id)) {
                $list->removeGroup($group->id);
            }
        } else {
            if ($item->isUnclaimed() && $item->list->assignable && $group->isMember(User::findCurrent()->id) || $tutor) {
                $item->assign($group->id);
            }
        }
        $this->redirect('show/index');
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