<?php
global $tt_params;

define('MAX_OVERLAPPINGS', 6);      //defines how many overlappings can be handled maximally
define('DAY_COLUMN_DISTANCE', 2);   //defines the space between the day columns in percent
?>
<script type="text/javascript">
    var ttOverlayBoxBackgroundImage = '<?php echo get_option('tt-overlay-box-background-image'); ?>';
    var ttOverlayBoxBackgroundColor = '<?php echo get_option('tt-overlay-box-background-color'); ?>';
    <?php
    if (get_option('tt-overlay-box-choose') == 'custom') {
    ?>
    var ttOverlayBoxChoose = false;
    <?php
    } else {
    ?>
    var ttOverlayBoxChoose = true;
    <?php
    }
    ?>
</script>
<style>

    <?php
    foreach(Category::getAllCategories() as $category) {
        ?>
        .tt-timetable-course-cat-<?php echo $category->getId(); ?> {
            background-color: <?php echo $category->getColor(); ?>;
        }
        <?php
    }
    ?>
</style>
<div class="tt-frontend-wrapper" style="max-width:<?php echo get_option('tt-width'); ?>px;">
    <div class="tt-frontend">
        <div class="tt-background-image" style="background-image: url(<?php echo get_option('tt-background-image'); ?>);"></div>
        <div class="tt-timetable-wrapper">
            <?php
            $days = TimeTableEntry::$DAYS;
            $entriesSaturday = TimeTableEntry::getTimeTableEntriesAtDay('Samstag');
            $entriesSunday = TimeTableEntry::getTimeTableEntriesAtDay('Sonntag');
            $starttime = strtotime('08:00');
            $endtime = strtotime('22:00');
            $myDays = array('Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag');
            $myShortDays = array('Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So');
            $useWeekendCourses = sizeof($entriesSaturday) > 0 || sizeof($entriesSunday) > 0;
            $timeSlotOverlap = array();
            for ($i = 0; $i < ($endtime - $starttime) / 900; $i ++) {
                for ($j = 0; $j < 7; $j ++) {
                    $timeSlotOverlap[$i][$myDays[$j]] = false;
                }
            }
            $timetableGap = false;
            echo '<a href="#" class="tt-timetable-slide-left tt-timetable-slide-button">&lt;</a>';
            echo '<a href="#" class="tt-timetable-slide-right tt-timetable-slide-button">&gt;</a>';
            echo '<div class="tt-timetable-time-column" style="width:' . 100 / ($useWeekendCourses?8:6) . '%">';
            echo '<table class="tt-timetable-days-header-time">';
            echo '<tr><td>Zeit</td></tr>';
            echo '</table>';
            for ($i = $starttime; $i < $endtime; $i += 900) {
                $time = date('H:i', $i);
                $usedTimeSlot = sizeof(TimeTableEntry::getTimeTableEntriesAtTime($time)) > 0;
                if ($usedTimeSlot) {
                    if (!$timetableGap) {
                        echo '<div class="tt-timetable"><table>';
                    }
                    echo '<tr class="tt-timetable-row" valign="top">';
                    echo '<td class="tt-timetable-cell tt-timetable-time">' . ((($i / 900) % 2 == 0) ? $time : '') . '</td>';
                    $timetableGap = true;
                }else if($timetableGap) {
                    echo '</table></div>';
                    $timetableGap = false;
                }
            }
            echo '</div>';
            echo '<div class="tt-timetable-columns-wrapper" style="width:' . (100 - 100 / ($useWeekendCourses?8:6)) . '%">';
            echo '<div class="tt-timetable-columns-slider">';
            echo '<table class="tt-timetable-days-header"><tr>';
            $i = 0;
            foreach ($days as $day) {
                if ($day != 'Samstag' && $day != 'Sonntag' || $useWeekendCourses) {
                    echo '<td width="' . 100 / ($useWeekendCourses?7:5) . '%"><span class="tt-day-full">' . $day . '</span>
                    <span class="tt-day-short">' . $myShortDays[$i] . '</span></td>';
                }
                $i ++;
            }
            $timetableGap = false;
            $td_width = (100 - ($useWeekendCourses?6:4)*DAY_COLUMN_DISTANCE) / (($useWeekendCourses ? 7 : 5) * MAX_OVERLAPPINGS);
            echo '</tr></table>';
            for ($i = $starttime; $i < $endtime; $i += 900) {
                $time = date('H:i', $i);
                $usedTimeSlot = sizeof(TimeTableEntry::getTimeTableEntriesAtTime($time)) > 0;
                if ($usedTimeSlot) {
                    if (!$timetableGap) {
                        echo '<div class="tt-timetable"><table>';
                        echo '<tr>';
                        for ($i_day = 0; $i_day < ($useWeekendCourses ? 7 : 5); $i_day++) {
                            if ($i_day > 0) {
                                echo '<td width="' . DAY_COLUMN_DISTANCE . '%" rowspan="100"></td>';
                            }
                            for ($i_td = 0; $i_td < MAX_OVERLAPPINGS; $i_td++) {
                                echo '<td width="' . $td_width . '%"></td>';
                            }
                        }
                        echo '</tr>';
                    }
                    echo '<tr class="tt-timetable-row" valign="top">';
                    //echo '<td class="tt-timetable-cell tt-timetable-time" width="' . 100 / ($useWeekendCourses?8:6) . '%">' . ((($i / 900) % 2 == 0)?$time:'') . '</td>';
                    foreach ($days as $day) {
                        if ($day != 'Samstag' && $day != 'Sonntag' || $useWeekendCourses) {

                            $course = TimeTableEntry::getTimeTableEntryAtDayAndTime($day, $time);
                            if (sizeof($course) > 0) {
                                $course = $course[0];
                                $overlappings = $course->countMaxConcurrentOverlappings();
                                $length = ((date($course->getEndTime()) - date($course->getStartTime())) / 900);
                                $width = (MAX_OVERLAPPINGS / $overlappings) * $td_width;
                                echo '<td class="tt-timetable-cell" rowspan="' . $length . '" colspan="' . (MAX_OVERLAPPINGS / $overlappings) . '" width="' . $width . '%">';
                                echo '<a id="tt-timetable-open-' . $day . (date($course->getStartTime()) / 900) . '" href="" class="tt-timetable-course tt-timetable-course-cat-' . $course->getCourse()->getCategory()->getId() . '">';
                                echo '<span class="tt-course-date">' . date('H:i', $course->getStartTime()) . ' - ' . date('H:i', $course->getEndTime()) . '<br /></span><span class="tt-course-name">' . $course->getCourse()->getName() . '</span>';
                                echo '</a>';
                                echo '<div id="tt-timetable-overlay-' . $day . (date($course->getStartTime()) / 900) . '" class="tt-timetable-overlay">';
                                echo '<img src="' . $course->getCourse()->getImage() . '" />';
                                echo '<div class="tt-timetable-course-description">';
                                echo '<div class="tt-timetable-course-name">' . $course->getCourse()->getName() . '</div>';
                                echo $course->getCourse()->getDescription();
                                if ($tt_params['more-info-link'] != 'hide' && $course->getCourse()->getLink() != '') {
                                    echo '<a href="' . $course->getCourse()->getLink() . '" class="tt-timetable-link"' . ($tt_params['more-info-link'] == 'new-page'?' target="_blank"':'') . '>Mehr Infos</a></div>';
                                }
                                echo '<script language="javascript" type="text/javascript">';
                                echo '(function($) {
                                    $(window).ready(function() {
                                        new TTOverlayBox("#tt-timetable-open-' . $day . (date($course->getStartTime()) / 900) . '",
                                                $("#tt-timetable-overlay-' . $day . (date($course->getStartTime()) / 900) . '").html());
                                    });
                                })(jQuery);';
                                echo '</script>';
                                echo '</div>';
                                echo '</td>';
                                $currentOverlappings = TimeTableEntry::countCurrentOverlappings($day, $time);
                                for ($i_fill = 0; $i_fill < MAX_OVERLAPPINGS / $currentOverlappings - MAX_OVERLAPPINGS / $overlappings; $i_fill++) {
                                    echo '<td width="' . $td_width . '"></td>';
                                }
                                for ($j = 0; $j < $length; $j++) {
                                    $timeSlotOverlap[($i - $starttime)/900 + $j][$day] = true;
                                }
                            }else if(!$timeSlotOverlap[($i - $starttime)/900][$day]) {
                                echo '<td class="tt-timetable-cell" colspan="' . MAX_OVERLAPPINGS . '" width="' . $td_width * MAX_OVERLAPPINGS . '%">' . $timeSlotOverlap[($i - $starttime)/900][$day] . '</td>';
                            }
                        }
                        $timetableGap = true;
                    }
                    echo '</tr>';
                }else if($timetableGap) {
                    echo '</table></div>';
                    $timetableGap = false;
                }
            }
            ?>
            </table>
            </div>
            </div>
        </div>
    </div>
    <?php
    if (get_option('tt-pdf-show-link') == 1) {
        ?>
        <a href="?pdf-time-table" title="Kursplan-PDF anzeigen" class="tt-pdf-link"><img src="<?php echo WP_PLUGIN_URL . '/time-table/images/icon-pdf.png'; ?>" title="pdf" width="32" height="32" /> PDF herunterladen</a>
        <?php
    }
    ?>
</div>

<script type="text/javascript">
    timetableHandleWindowSize();
</script>