<?php

class TimeTableEntry {

    private $data;
    private $course;
    private $start_time;
    private $end_time;
    private $day;

    public static $DAYS = array(
        'mon' => 'Montag',
        'tue' => 'Dienstag',
        'wen' => 'Mittwoch',
        'thu' => 'Donnerstag',
        'fri' => 'Freitag',
        'sat' => 'Samstag',
        'sun' => 'Sonntag'
    );

    public final static function getAllTimeTableEntries() {
        global $wpdb;
        $entries = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'time_table');
        $resultTimeTableEntries = array();
        foreach ($entries as $entry) {
            $resultTimeTableEntries[] = new TimeTableEntry($entry);
        }
        return $resultTimeTableEntries;
    }

    public final static function getTimeTableEntriesAtDay($day) {
        global $wpdb;
        $entries = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'time_table WHERE day = "' . $day . '" ORDER BY start_time ASC');
        $resultTimeTableEntries = array();
        foreach ($entries as $entry) {
            $resultTimeTableEntries[] = new TimeTableEntry($entry);
        }
        return $resultTimeTableEntries;
    }

    public final static function getTimeTableEntryAtDayAndTime($day, $time) {
        global $wpdb;
        $entries = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'time_table WHERE day = "' . $day . '"
            AND start_time = "' . $time . '"');
        $resultTimeTableEntries = array();
        foreach ($entries as $entry) {
            $resultTimeTableEntries[] = new TimeTableEntry($entry);
        }
        return $resultTimeTableEntries;
    }

    public final static function getTimeTableEntriesAtTime($time) {
        global $wpdb;
        $entries = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'time_table WHERE start_time <= "' . $time . '" AND end_time > "' . $time . '" ORDER BY day ASC');
        $resultTimeTableEntries = array();
        foreach ($entries as $entry) {
            $resultTimeTableEntries[] = new TimeTableEntry($entry);
        }
        return $resultTimeTableEntries;
    }

    public final static function entryOverlaps($starttime, $endtime, $day) {
        global $wpdb;
        $entries = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'time_table WHERE day = "' . $day . '"
        AND start_time < "' . $endtime . '" AND end_time > "' . $starttime . '"');
        return sizeof($entries) > 0;
    }

    public function __construct($data) {
        $this->data = $data;
        $this->course = new Course($data->course);
        $this->start_time = strtotime($data->start_time);
        $this->end_time = strtotime($data->end_time);
        $this->day = $data->day;
    }

    public function getCourse() {
        return $this->course;
    }

    public function getStartTime() {
        return $this->start_time;
    }

    public function getEndTime() {
        return $this->end_time;
    }

    public function getDay() {
        return $this->day;
    }

}