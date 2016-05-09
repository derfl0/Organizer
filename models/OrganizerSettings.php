<?php

class OrganizerSettings extends SimpleORMap
{
    protected static function configure($config = array())
    {
        $config['db_table'] = 'organizer_settings';
        parent::configure($config);
    }

    public static function get($course_id = null) {
        $course_id = $course_id ? : Course::findCurrent()->id;
        $settings = new self($course_id);

        // If is new restore default settings
        if ($settings->isNew()) {
            $default = $settings->default_values;
            unset($default['course_id']);
            $settings->setData($default);
        }
        return $settings;
    }

}