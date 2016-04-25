<?php
    global $actDay;
    if (isset($_GET['day']) && array_key_exists($_GET['day'], TimeTableEntry::$DAYS)){
        $actDay = $_GET['day'];
    }else {
        $actDay = 'mon';
    }

if (isset($_POST['tt-settings-submit']) && $_POST['tt-settings-submit'] == 'Y') {
    tt_admin_timetable_save();
}
?>

<form method="post" action="<?php admin_url('time-table.php?page=timetable') ?>">
    <?php
    wp_nonce_field("tt-settings-page");
    settings_fields('tt-settings');
    do_settings_sections('tt-settings');

    global $wpdb;
            echo '<h3 class="nav-tab-wrapper">';
    foreach (TimeTableEntry::$DAYS as $day => $name) {
        $class = ( $day == $actDay ) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab$class' href='?page=time-table&tab=timetable&day=$day'>" . $name . "</a>";
    }
    echo '</h3>';
    ?>
    <input type="hidden" name="tt-day" value="<?php echo $actDay; ?>" />

    <div class="tt-settings-section">
        <div class="tt-settings-header">
            <h2>Kurs in Kursplan eintragen</h2>
        </div>
        <br />
        <div class="tt-settings-content">
            <label>Kurs auswählen</label>
            <select name="tt-add-timetable-course">
                <option value="choose">Kurs auswählen</option>
                <?php
                    foreach (Course::getAllCourses() as $course) {
                        echo '<option value="' . $course->getId() . '">' . $course->getName() . '</option>';
                    }
                 ?>
            </select><br />
            <label>Startzeit</label>
            <input type="text" id="tt-add-timetable-starttime" name="tt-add-timetable-starttime" class="small-text" />
            <label>Endzeit</label>
            <input type="text" id="tt-add-timetable-endtime" name="tt-add-timetable-endtime" class="small-text" />
            <input type="submit" name="tt-add-timetable-entry" value="Kurs eintragen" class="button-secondary" />
        </div>
        <br />
    </div>
    <div class="tt-settings-section">
        <div class="tt-settings-header">
            <h2>Eingetragene Kurse</h2>
        </div>
        <br />
        <div class="tt-settings-content">
        <table>
            <?php
            foreach (TimeTableEntry::getTimeTableEntriesAtDay(TimeTableEntry::$DAYS[$actDay]) as $entry) {
                echo '<tr class="tt-entry">';
                echo '<td>'.$entry->getCourse()->getName().'</td>';
                echo '<td><strong>' . date('H:i', $entry->getStartTime()) . '</strong> bis <strong>' . date('H:i', $entry->getEndTime()) . '</strong></td>';
                echo '<td><a href="admin.php?page=time-table&tab=add-course&course-id=' . $entry->getCourse()->getId() . '" class="button-primary">Bearbeiten</a></td>';
                echo '<td><input type="submit" class="button-secondary tt-timetable-delete" name="tt-timetable-delete-' . $entry->getStartTime() . '" value="Löschen" /></td>';
                echo '</tr>';
            }
            ?>
            </table>
        </div>
    </div>

    <script type="text/javascript" language="javascript">
        (function($) {
            $(window).ready(function() {
                $('#tt-add-timetable-starttime, #tt-add-timetable-endtime, .tt-timepicker').timepicker({
                    timeFormat: 'H:i',
                    minTime: '08:00',
                    maxTime: '22:00',
                    step: 15
                });
                $('.tt-timetable-delete').click(function() {
                    return confirm('Sind Sie sicher, dass Sie den Eintrag aus dem Kursplan entfernen möchten?');
                });
            });
        })(jQuery);
    </script>

    <?php

    function isValidDateTimeString($str_dt, $str_dateformat, $str_timezone) {
  $date = DateTime::createFromFormat($str_dateformat, $str_dt, new DateTimeZone($str_timezone));
  $lastErrors = DateTime::getLastErrors();
  return $date && $lastErrors["warning_count"] == 0 && $lastErrors["error_count"] == 0;
}

    function tt_admin_timetable_save() {
        global $wpdb, $actDay;
        $tablename = $wpdb->prefix . 'time_table';
        $success = false;

        if (isset($_POST['tt-add-timetable-entry'])){
            $course = $_POST['tt-add-timetable-course'];
            $starttime = $_POST['tt-add-timetable-starttime'];
            $endtime = $_POST['tt-add-timetable-endtime'];
            if ($course == 'choose'){
            ?>
            <div id="message" class="error notice is-dismissible">
                <p>Es muss ein Kurs ausgewählt werden</p>
            </div>
            <?php
            }else if ($starttime == ''){
            ?>
            <div id="message" class="error notice is-dismissible">
                <p>Es muss eine Startzeit angegeben werden</p>
            </div>
            <?php
            }else if ($endtime == ''){
            ?>
            <div id="message" class="error notice is-dismissible">
                <p>Es muss eine Endzeit angegeben werden</p>
            </div>
            <?php
            }else if (!isValidDateTimeString($starttime, 'H:i', 'Europe/Berlin')){
            ?>
            <div id="message" class="error notice is-dismissible">
                <p>Die Startzeit muss vom richtigen Format (hh:mm) sein</p>
            </div>
            <?php
            }else if (!isValidDateTimeString($endtime, 'H:i', 'Europe/Berlin')){
            ?>
            <div id="message" class="error notice is-dismissible">
                <p>Die Endzeit muss vom richtigen Format (hh:mm) sein</p>
            </div>
            <?php
            }else if (strtotime($starttime) >= strtotime($endtime)) {
            ?>
            <div id="message" class="error notice is-dismissible">
                <p>Die Startzeit muss vor der Endzeit liegen</p>
            </div>
            <?php
            }else if (TimeTableEntry::entryOverlaps($starttime, $endtime, TimeTableEntry::$DAYS[$actDay])){
            ?>
            <div id="message" class="error notice is-dismissible">
                <p>Der Kurs überschneidet sich zeitlich mit einem anderen</p>
            </div>
            <?php
            }else {
                $values = array(
                    'course' => $course,
                    'start_time' => $starttime,
                    'end_time' => $endtime,
                    'day' => TimeTableEntry::$DAYS[$actDay]
                );
                $success = $wpdb->insert($tablename, $values);
            }
            if ($success) {
            ?>
                <div id="message" class="updated notice is-dismissible">
                    <p>Kursplan erfolgreich gespeichert</p>
                </div>
                <?php
            } else {
                ?>
                <div id="message" class="error notice is-dismissible">
                    <p>Beim Speichern des Kursplans trat ein Fehler auf</p>
                </div>
                <?php
            }
        }
        foreach (TimeTableEntry::getTimeTableEntriesAtDay(TimeTableEntry::$DAYS[$actDay]) as $entry){
            if (isset($_POST['tt-timetable-delete-' . $entry->getStartTime()])){
                $wpdb->delete(
                    $tablename, array('start_time' => date('H:i', $entry->getStartTime()))
                );
            }
        }
    }
    ?>