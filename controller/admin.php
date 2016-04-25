<?php

global $wpdb;
define('TT_MYSQL_QUERY_SEPARATOR', "\n---end of time-table SQL-Query---\n");
define('TT_TABLE_CATEGORY', $wpdb->prefix . "time_table_category");
define('TT_TABLE_COURSE', $wpdb->prefix . "time_table_course");
define('TT_TABLE_TIME_TABLE', $wpdb->prefix . "time_table");

add_action('admin_menu', 'time_table_admin_menu');
add_action('admin_init', 'register_tt_settings');

function time_table_admin_menu() {
    add_menu_page('Kursplan', 'Kursplan', 'administrator', 'time-table', 'time_table_settings_page', 'dashicons-admin-generic');
}

function register_tt_settings() {
    register_setting('tt-settings', 'tt-background-image');
    register_setting('tt-settings', 'tt-width');
    register_setting('tt-settings', 'tt-height');
    register_setting('tt-settings', 'tt-overlay-box-choose');
    register_setting('tt-settings', 'tt-overlay-box-background-image');
    register_setting('tt-settings', 'tt-overlay-box-background-color');
    register_setting('tt-settings', 'tt-pdf-show-link');
    register_setting('tt-settings', 'tt-pdf-h1');
    register_setting('tt-settings', 'tt-pdf-h2');
    register_setting('tt-settings', 'tt-pdf-show-legend');

    wp_register_style('tt-admin-style', plugins_url('../styles/admin.css', __FILE__));
    wp_enqueue_style('tt-admin-style');
    wp_enqueue_script('jquery-ui-draggable');
    wp_enqueue_script('jquery-ui-resizable');
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');
    wp_enqueue_script('jquery-timepicker', plugins_url('../scripts/jquery.timepicker.js', __FILE__));
    wp_enqueue_style('jquery-timepicker-css', plugins_url('../styles/jquery.timepicker.css', __FILE__));
}

function time_table_settings_page() {
    if (isset($_GET['tab'])) {
        tt_admin_tabs($_GET['tab']);
        $tab = $_GET['tab'];
    } else {
        tt_admin_tabs();
        $tab = 'settings';
    }

    // jQuery
    wp_enqueue_script('jquery');
    // This will enqueue the Media Uploader script
    wp_enqueue_media();
    ?>
    <div id="tt-backend">
        <?php
        switch ($tab) {
            case 'courses':
                include(plugin_dir_path(__FILE__) . '../view/courses_page.php');
                break;
            case 'timetable':
                        ini_set('display_errors', 1);
                        ini_set('display_startup_errors', 1);
                        error_reporting(E_ALL);

                include(plugin_dir_path(__FILE__) . '../view/timetable_page.php');
                break;
            case 'add-course':
                include(plugin_dir_path(__FILE__) . '../view/add_course.php');
                break;
            case 'categories':
                include(plugin_dir_path(__FILE__) . '../view/categories_page.php');
                break;
            case 'import-export':
                include(plugin_dir_path(__FILE__) . '../view/import_export_page.php');
                break;
            case 'help':
                include(plugin_dir_path(__FILE__) . '../view/help.php');
                break;
            default:
                include(plugin_dir_path(__FILE__) . '../view/settings_page.php');
        }
        ?>

        <?php
        if ($tab != 'import-export' && $tab != 'help') {
            ?>
            <p class="submit" style="clear: both;">
                <input type="submit" name="submit" class="button-primary" value="Speichern"/>
                <input type="hidden" name="tt-settings-submit" value="Y"/>
            </p>
            </form>
            <?php
        }
        ?>
    </div>
    <script type="text/javascript">
        var ttChangedWithoutSave = false;
        (function($) {
            $(window).ready(function() {
                $('#tt-backend input, #tt-backend textarea, #tt-backend select').change(function() {
                    ttChangedWithoutSave = true;
                });
                setTimeout(function() {
                    for (var i = 0; i < tinymce.editors.length; i++) {
                        tinymce.editors[i].onChange.add(function(ed, e) {
                            ttChangedWithoutSave = true;
                        });
                    }
                }, 1000);
                $('#tt-backend .submit input, #tt-backend input[type="submit"]').click(function() {
                    ttChangedWithoutSave = false;
                })
            });
            $(window).bind('beforeunload', function() {
                if (ttChangedWithoutSave) {
                    return 'Are you sure you want to leave?';
                }
            });

        })(jQuery);
    </script>
    <?php
}

function tt_admin_tabs($current = 'settings') {
    $tabs = array(
        'settings' => 'Einstellungen',
        'categories' => 'Kategorien',
        'courses' => 'Kurse',
        'timetable' => 'Kursplan',
        'import-export' => 'Import/Export');
    echo '<h2 class="nav-tab-wrapper">';
    foreach ($tabs as $tab => $name) {
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab$class' href='?page=time-table&tab=$tab'>$name</a>";
    }
    $class = ( 'help' == $current ) ? ' nav-tab-active' : '';
    echo "<a class='nav-tab help$class' href='?page=time-table&tab=help'>Hilfe</a>";
    echo '</h2>';
}


function tt_admin_export_tables() {

    /*$file = '/time-table/tmp/time-table.sql';
    $file_path_dir = WP_PLUGIN_DIR . $file;
    $file_path_dir = str_replace('\\', '/', $file_path_dir);
    $file_path_url = WP_PLUGIN_URL . $file;

    if (file_exists($file_path_dir)) {
        unlink($file_path_dir);
    }*/

    return backup_tables(array(TT_TABLE_CATEGORY, TT_TABLE_COURSE, TT_TABLE_TIME_TABLE));

    //header('Location: ' . $file_path_url);

}

/* backup the db OR just a table */
function backup_tables($tables = '*') {

    global $wpdb;

    $return = "";

    //get all of the tables
    $tables = is_array($tables) ? $tables : explode(',',$tables);

    //cycle through
    foreach($tables as $key => $table)
    {
        $result = $wpdb->get_results('SELECT * FROM '.$table, ARRAY_N);
        $num_rows = $wpdb->num_rows;
        $num_fields = 0;
        if ($num_rows > 0) {
            $num_fields = sizeof($result[0]);
        }

        $return.= 'DROP TABLE IF EXISTS '.$table.';';
        $return .= TT_MYSQL_QUERY_SEPARATOR;
        //print_r($wpdb->get_results('SHOW CREATE TABLE '.$table)[0]);
        //$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
        $row2 = $wpdb->get_results('SHOW CREATE TABLE '.$table, ARRAY_N);
        $row2 = $row2[0];
        $return.= "\n\n".$row2[1].";\n\n";
        $return .= TT_MYSQL_QUERY_SEPARATOR;

        for ($i = 0; $i < $num_rows; $i++)
        {
            $row = $result[$i];
            $return.= 'INSERT INTO '.$table.' VALUES(';
            for($j=0; $j < $num_fields; $j++)
            {
                $row[$j] = addslashes($row[$j]);
                $row[$j] = preg_replace("/\n/","\\n",$row[$j]);
                if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
                if ($j < ($num_fields-1)) { $return.= ','; }
            }
            $return.= ");\n";
            if ($i < $num_rows - 1 || $key < sizeof($tables) - 1) {
                $return .= TT_MYSQL_QUERY_SEPARATOR;
            }
        }
        $return.="\n\n\n";
    }

    return $return;
}

if (isset($_POST['tt-export-submit']) && $_POST['tt-action'] == 'export') {
    $sql = tt_admin_export_tables();

    header('Content-Disposition: attachment; filename="time-table.sql"');
    header('Content-Type: text/plain');
    header('Content-Length: ' . strlen($sql));
    header('Connection: close');

    echo $sql;
    die();
}

