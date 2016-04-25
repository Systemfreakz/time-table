<?php

$pdfH1 = get_option('tt-pdf-h1');
$pdfH2 = get_option('tt-pdf-h2');

$html = "<h1 style='text-align: center'>" . ($pdfH1 != ''?$pdfH1:'Kursplan') . "</h1>";
if ($pdfH2 != '') {
    $html .= "<h2 style='text-align: center'>" . $pdfH2 . "</h2>";
}
$html .= "<br /><br />";
$days = TimeTableEntry::$DAYS;
$entriesSaturday = TimeTableEntry::getTimeTableEntriesAtDay('Samstag');
$entriesSunday = TimeTableEntry::getTimeTableEntriesAtDay('Sonntag');
$starttime = strtotime('08:00');
$endtime = strtotime('22:00');
$myDays = array('Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag');
$useWeekendCourses = sizeof($entriesSaturday) > 0 || sizeof($entriesSunday) > 0;
$timeSlotOverlap = array();
for ($i = 0; $i < ($endtime - $starttime) / 900; $i ++) {
    for ($j = 0; $j < 7; $j ++) {
        $timeSlotOverlap[$i][$myDays[$j]] = false;
    }
}
$timetableGap = false;
$html .= "<table width='100%' border='1'><tr bgcolor='lightgray'><td width=\"" . (100 / ($useWeekendCourses?8:6)) . "%\" align='center' height='40px'>Zeit</td>";
foreach ($days as $day) {
    if ($day != 'Samstag' && $day != 'Sonntag' || $useWeekendCourses) {
        $html .= '<td width="' . 100 / ($useWeekendCourses?8:6) . '%" align="center">' . $day . '</td>';
    }
    $i ++;
}
$timetableGap = false;
$html .= '</tr></table><br />';
for ($i = $starttime; $i < $endtime; $i += 900) {
    $time = date('H:i', $i);
    $usedTimeSlot = sizeof(TimeTableEntry::getTimeTableEntriesAtTime($time)) > 0;
    $previousTimeSlotUsed = sizeof(TimeTableEntry::getTimeTableEntriesAtTime(date('H:i', $i - 900))) > 0;
    if ($usedTimeSlot || $previousTimeSlotUsed && ($i / 900) % 2 == 1) {
        if (!$timetableGap) {
            $html .= '<table width="100%" style=" border: 1px solid #000">';
        }
        $html .= '<tr>';
        if (($i / 900) % 2 == 0) {
            $html .= '<td bgcolor="lightgray" style="border: 1px solid #000;" align="center" rowspan="2" height="36px" width="' . (100 / ($useWeekendCourses?8:6)) . '%">' . $time . '</td>';
        }

        foreach ($days as $day) {
            if ($day != 'Samstag' && $day != 'Sonntag' || $useWeekendCourses) {

                $course = TimeTableEntry::getTimeTableEntryAtDayAndTime($day, $time);
                if (sizeof($course) > 0) {
                    $course = $course[0];
                    $length = ((date($course->getEndTime()) - date($course->getStartTime())) / 900);
                    $html .= '<td rowspan="' . $length . '" align="center" width="' . (100 / ($useWeekendCourses?8:6)) . '%"';
                    $html .= ' bgcolor="' . $course->getCourse()->getCategory()->getColor() . '" style="border: 1px solid #000">';
                    $html .= '<span style="text-shadow: 1px 1px #000; color: #fff">' . date('H:i', $course->getStartTime()) . ' - ' . date('H:i', $course->getEndTime()) . '<br /></span>';
                    $html .= '<span style="text-shadow: 1px 1px #000; color: #fff">' . $course->getCourse()->getName() . '</span>';
                    $html .= '</td>';
                    for ($j = 0; $j < $length; $j++) {
                        $timeSlotOverlap[($i - $starttime)/900 + $j][$day] = true;
                    }
                }else if(!$timeSlotOverlap[($i - $starttime)/900][$day]) {
                    $html .= '<td height="18px"' . (($i/900) % 2 == 0 ? ' style="border-top: 0px solid #000"' : '') . '></td>';
                }
            }
            $timetableGap = true;
        }
        $html .= '</tr>';
    } else if($timetableGap) {
        $html .= '</table><br />';
        $timetableGap = false;
    }
}
$html .= "</table>";

if (get_option('tt-pdf-show-legend') == 1) {
    $html .= "<h3>Legende: </h3>";
    $html .= "<table cellspacing='10'>";
    foreach(Category::getAllCategories() as $category) {
        $html .= "<tr><td style=\"background-color: " . $category->getColor() . "; border: 1px solid #000;\" width='28px' height='28px'></td>";
        $html .= "<td>" . $category->getName() . '</td></tr>';

    }
    $html .= "</table>";
}


//==============================================================
//==============================================================
//==============================================================
include(plugin_dir_path(__FILE__) . "../lib/mpdf/mpdf.php");
$mpdf=new mPDF('c');

$mpdf->WriteHTML($html);
$mpdf->Output('kursplan.pdf', 'D');
exit;

//==============================================================
//==============================================================
//==============================================================


?>