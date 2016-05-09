<?php

class OrganizerItem extends SimpleORMap
{
    protected static function configure($config = array())
    {
        $config['db_table'] = 'organizer_asset_items';
        $config['belongs_to']['list'] = array(
            "class_name" => "OrganizerAsset",
            "foreign_key" => "asset_id"
        );
        parent::configure($config);
    }

    public function isUnclaimed() {
        return !$this->assign_id;
    }

    public function assign($group_id) {
        $this->list->removeGroup($group_id);
        $this->assign_id = $group_id;
        $this->store();
    }
}