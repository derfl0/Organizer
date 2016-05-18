<?php

class OrganizerAsset extends SimpleORMap
{
    const DEFAULT_ITEM = "-";

    protected static function configure($config = array())
    {
        $config['db_table'] = 'organizer_assets';
        $config['has_many']['items'] = array(
            'class_name' => 'OrganizerItem',
            'assoc_foreign_key' => 'asset_id',
            'on_delete' => 'delete',
            'on_store' => 'store'
        );
        $config['belongs_to']['course'] = array(
            "class_name" => "Course",
            "foreign_key" => "course_id",
            "assoc_key" => "Seminar_id"
        );
        parent::configure($config);
    }

    public function removeGroup($group_id) {
        $assigned = $this->items->findBy('assign_id', $group_id);
        $assigned->setValue('assign_id', null);
        $assigned->store();
    }

    public function getGroupChoise($group_id) {
        if ($item = $this->items->findOneBy('assign_id', $group_id)) {
            return $item->name;
        }
        return self::DEFAULT_ITEM;
    }
/*
    public static function getUpdateInformation($course_id = null) {
        $course_id = $course_id ? : Course::findCurrent()->id;
        if ($GLOBALS['perm']->have_studip_perm('tutor', $course_id)) {
            $lists = self::findBySQL('course_id = ?', array($course_id));
        } else {
            $lists = self::findBySQL('course_id = ? AND visible = 1', array($course_id));
        }

    }
*/
}