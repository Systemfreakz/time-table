<?php

/**
 * Plugin Name: Kursplan
 * Description: Fügt einen Kursplan zu Wordpress hinzu, dessen Aussehen und Inhalte bearbeitet werden können.
 * Version: 2.5
 * Author: Tobias Kirschstein
 */


require_once(plugin_dir_path(__FILE__) . 'model/Course.php');
require_once(plugin_dir_path(__FILE__) . 'model/Category.php');
require_once(plugin_dir_path(__FILE__) . 'model/TimeTableEntry.php');

include(plugin_dir_path(__FILE__) . 'functions.php');
include(plugin_dir_path(__FILE__) . 'controller/admin.php');
include(plugin_dir_path(__FILE__) . 'controller/frontend.php');

function time_table_install() {
    global $wpdb;
    $course_table_name = $wpdb->prefix . "time_table_course";
    $category_table_name = $wpdb->prefix . "time_table_category";
    $time_table_table_name = $wpdb->prefix . "time_table";

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $course_table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      name VARCHAR(255) NOT NULL,
      image VARCHAR(255) NOT NULL,
      description TEXT NOT NULL,
      link VARCHAR(255) NOT NULL,
      category mediumint(9) NOT NULL REFERENCES " . $category_table_name . "(id),
      UNIQUE KEY id (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    $sql = "CREATE TABLE $category_table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      name VARCHAR(255) NOT NULL,
      color VARCHAR(7) NOT NULL,
      UNIQUE KEY id (id)
    ) $charset_collate;";

    dbDelta($sql);

    $sql = "CREATE TABLE $time_table_table_name (
      id int AUTO_INCREMENT PRIMARY KEY,
      course mediumint(9) NOT NULL REFERENCES " . $course_table_name . "(id),
      start_time TIME NOT NULL,
      end_time TIME NOT NULL,
      day ENUM('Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag')
    ) $charset_collate;";

    dbDelta($sql);

    add_option("time_table_db_version", "1.0");
}

function time_table_uninstall() {
    global $wpdb;
    $course_table_name = $wpdb->prefix . "time_table_course";
    $category_table_name = $wpdb->prefix . "time_table_category";
    $time_table_table_name = $wpdb->prefix . "time_table";

    $sql = "DROP TABLE IF EXISTS $course_table_name;";
    $wpdb->query($sql);

    $sql = "DROP TABLE IF EXISTS $category_table_name;";
    $wpdb->query($sql);

    $sql = "DROP TABLE IF EXISTS $time_table_table_name;";
    $wpdb->query($sql);
}

function time_table_install_data() {
    global $wpdb;

    $course_table_name = $wpdb->prefix . "time_table_course";
    $category_table_name = $wpdb->prefix . "time_table_category";
    $time_table_table_name = $wpdb->prefix . "time_table";

    $wpdb->insert($category_table_name, array(
        'name' => 'Test Kategorie',
        'color' => '#ffab00'
    ));

    $wpdb->insert($course_table_name, array(
        'name' => 'Test Kurs',
        'image' => '',
        'description' => 'Test Beschreibung',
        'link' => '',
        'category' => 1
    ));

    $wpdb->insert($time_table_table_name, array(
        'course' => 1,
        'start_time' => '10:00:00',
        'end_time' => '12:00:00',
        'day' => 'Montag'
    ));

    update_option('tt-width', 1000);
    update_option('tt-height', 700);
    update_option('tt-overlay-box-choose', 'custom');

    update_option('tt-pdf-show-link', 1);
    update_option('tt-pdf-h1', 'Kursplan ' . get_bloginfo());
    update_option('tt-pdf-show-legend', 1);

}

register_activation_hook(__FILE__, 'time_table_install');
register_activation_hook(__FILE__, 'time_table_install_data');

register_deactivation_hook(__FILE__, 'time_table_uninstall');

add_shortcode('time_table', 'time_table_shortcode');
add_shortcode('kurs_plan', 'time_table_shortcode');
add_shortcode('Kursplan', 'time_table_shortcode');

$tt_params = array();

function time_table_shortcode($atts) {

    global $tt_params;
    $tt_params = shortcode_atts(array(
        'more-info-link' => 'show'
    ), $atts);

    include(plugin_dir_path(__FILE__) . 'view/Frontend.php');
}

?>