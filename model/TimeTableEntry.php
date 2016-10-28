<?php

class TimeTableEntry {

    private $data;
    private $course;
    private $start_time;
    private $end_time;
    private $day;
    private $id;

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

    /**
     * @param $day
     * @param $time
     * @return TimeTableEntry[]
     */
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

    public final static function countCurrentOverlappings($day, $time) {
        global $wpdb;
        $entries = $wpdb->get_results('
          SELECT start_time, end_time FROM ' . $wpdb->prefix . 'time_table
          WHERE day = "' . $day . '"
          AND (start_time <= "' . $time . '" AND end_time > "' . $time . '")');
        return $wpdb->num_rows;
    }

    public function __construct($data) {
        $this->data = $data;
        $this->id = $data->id;
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

    public function getId() {
        return $this->id;
    }

    public function countOverlappings() {
        global $wpdb;
        $entries = $wpdb->get_results('
          SELECT t2.start_time, t2.end_time FROM ' . $wpdb->prefix . 'time_table t1, ' . $wpdb->prefix . 'time_table t2
          WHERE t1.id = "' . $this->id . '" AND t1.day = t2.day
          AND (t1.start_time <= t2.start_time AND t1.end_time > t2.start_time
              OR t1.start_time <= t2.start_time AND t1.end_time >= t2.end_time
              OR t1.start_time > t2.start_time AND t1.start_time < t2.end_time)');
        return $wpdb->num_rows;
    }

    public function countMaxConcurrentOverlappings() {
        global $wpdb;
        $entries = $wpdb->get_results('
          SELECT t2.start_time, t2.end_time FROM ' . $wpdb->prefix . 'time_table t1, ' . $wpdb->prefix . 'time_table t2
          WHERE t1.id = "' . $this->id . '" AND t1.day = t2.day
          AND (t1.start_time <= t2.start_time AND t1.end_time > t2.start_time
              OR t1.start_time <= t2.start_time AND t1.end_time >= t2.end_time
              OR t1.start_time > t2.start_time AND t1.start_time < t2.end_time)');
        $start_values = array();
        $end_values = array();
        foreach ($entries as $entry) {
            $start_values[] = strtotime($entry->start_time);
            $end_values[] = strtotime($entry->end_time);
        }
        sort($start_values);
        sort($end_values);
        $result = 0;
        $max_result = 0;
        while (sizeof($start_values) > 0) {
            $smallest_start_value = $start_values[0];
            $smallest_end_value = $end_values[0];
            if ($smallest_start_value < $smallest_end_value) {
                $result ++;
                array_shift($start_values);
                if ($result > $max_result) {
                    $max_result = $result;
                }
            } else {
                $result --;
                array_shift($end_values);
            }
        }
        return $max_result;
    }

}