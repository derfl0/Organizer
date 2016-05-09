<?php

class OrganizerGroup extends SimpleORMap
{
    protected static function configure($config = array())
    {
        $config['db_table'] = 'organizer_groups';
        /*$config['has_many']['users'] = array(
            'class_name' => 'User',
            'thru_table' => 'organizer_groupuser',
            'thru_key' => 'group_id',
            'thru_assoc_key' => 'group_id',
            'assoc_foreign_key' => 'user_id'
        );*/
        $config['belongs_to']['course'] = array(
            "class_name" => "Course",
            "foreign_key" => "course_id",
            "assoc_key" => "Seminar_id"
        );
        $config['belongs_to']['settings'] = array(
            "class_name" => "OrganizerSettings",
            "foreign_key" => "course_id",
            "assoc_key" => "Seminar_id"
        );
        $config['additional_fields']['users'] = true;
        parent::configure($config);
    }

    public function getUsers()
    {
        return User::findThru($this->group_id, array('thru_table' => 'organizer_groupuser', 'thru_key' => 'group_id',
            'thru_assoc_key' => 'user_id',
            'assoc_foreign_key' => 'user_id'));
    }

    /**
     * @param $user_id
     * @param null $course_id
     * @return NULL|OrganizerGroup
     */
    public static function findGroup($user_id, $course_id = null)
    {
        $course_id = $course_id ?: Course::findCurrent()->id;
        return self::findOneBySQL(' JOIN organizer_groupuser USING (group_id) WHERE course_id = ? AND user_id = ?', array($course_id, $user_id));
    }

    public static function hasGroup($user_id, $course_id = null)
    {
        $course_id = $course_id ?: Course::findCurrent()->id;
        return DBManager::get()->execute('SELECT 1 FROM organizer_groups JOIN organizer_groupuser USING (group_id) WHERE course_id = ? AND user_id = ?', array($course_id, $user_id));
    }

    public static function createGroup($user1, $user2)
    {
        $group = new self;
        $group->course_id = Course::findCurrent()->id;
        $group->store();
        $group->addUser($user1);
        $group->addUser($user2);
    }

    public function countUser() {
        return DBManager::get()->fetchColumn('SELECT COUNT(*) FROM organizer_groupuser WHERE group_id = ?', array($this->id));
    }

    public function removeUser($user_id) {
        if ($this->countUser() <= 2) {
            DBManager::get()->execute('DELETE FROM organizer_groupuser WHERE group_id = ?', array($this->id));
            $this->delete();
        } else {
            DBManager::get()->execute('DELETE FROM organizer_groupuser WHERE group_id = ? AND user_id = ?', array($this->id, $user_id));
        }
    }

    public function isMember($user_id) {
        foreach ($this->users as $user) {
            if ($user->user_id == $user_id) {
                return true;
            }
        }
        return false;
    }

    public static function findCurrent() {
        return self::findGroup(User::findCurrent()->id);
    }

    public function addUser($user_id)
    {
        $stmt = DBManager::get()->prepare("REPLACE INTO organizer_groupuser VALUES (?,?)");
        $stmt->execute(array($this->group_id, $user_id));
    }

    public function hasMinTeamSize() {
        return count($this->users) >= $this->settings->min_size;
    }

    public function hasMaxTeamSize() {
        return count($this->users) >= $this->settings->max_size;
    }
}