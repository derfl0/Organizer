<?php

class OrganizerInvite extends SimpleORMap
{
    private static $cache;

    protected static function configure($config = array()) {
        $config['db_table'] = 'organizer_invites';
        parent::configure($config);
    }

    public static function isInvited($from, $to) {
        if (self::$cache === null) {
            $stmt = DBManager::get()->prepare("SELECT CONCAT(user1, '_', user2) FROM organizer_invites WHERE course_id = ?");
            $stmt->execute(array(Course::findCurrent()->id));
            while ($key = $stmt->fetchColumn()) {
                self::$cache[$key] = true;
            }
        }
        return (bool) self::$cache[$from."_".$to];
    }

    public static function invite($user_id) {
        OrganizerInvite::create(array('user1' => User::findCurrent()->id, 'user2' => $user_id, 'course_id' => Course::findCurrent()->id));
    }

    public static function cancel($user_id, $other_id = null) {
        $other_id = $other_id ? : User::findCurrent()->id;
        $stmt = DBManager::get()->prepare('DELETE FROM organizer_invites WHERE ((user1 = :user1 AND user2 = :user2) OR (user1 = :user2 AND user2 = :user1)) AND course_id = :course_id');
        $stmt->bindParam(':user1', $user_id);
        $stmt->bindParam(':user2', $other_id);
        $stmt->bindParam(':course_id', Course::findCurrent()->id);
        $stmt->execute();
    }

    public static function canInvite($user_id = null) {
        $user_id = $user_id ? : User::findCurrent()->id;
        if (OrganizerSettings::get()->locked) {
            return false;
        }
        $group = OrganizerGroup::findGroup($user_id);
        if ($group && $group->hasMaxTeamSize()) {
            return false;
        }
        return true;
    }

    public static function accept($user_id) {

        // if current user has a group
        if ($group = OrganizerGroup::findGroup(User::findCurrent()->id)) {
            $group->addUser($user_id);
        } elseif ($group = OrganizerGroup::findGroup($user_id)) {
            $group->addUser(User::findCurrent()->id);
        } else {
            OrganizerGroup::createGroup($user_id, User::findCurrent()->id);
        }

    }
}