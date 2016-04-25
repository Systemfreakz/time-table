<?php

add_action('wp_enqueue_scripts', 'tt_register_styles');

if (isset($_GET['pdf-time-table'])) {
    include(plugin_dir_path(__FILE__) . '../view/pdf.php');
}

function tt_register_styles() {
    global $post;
    //if (has_shortcode($post->post_content, 'Kursplan') || has_shortcode($post->post_content, 'kurs_plan') || has_shortcode($post->post_content, 'time_table')) {

        wp_enqueue_script('tt-overlay-box', plugins_url('../scripts/frontendOverlay.js', __FILE__));
        wp_enqueue_script('tt-responsiveness', plugins_url('../scripts/frontendResponsiveness.js', __FILE__), array('jquery'));
        if (get_option('tt-overlay-box-choose') != 'custom') {
            wp_enqueue_script('jquery-colorbox', plugins_url('../scripts/jquery.colorbox-min.js', __FILE__), array('jquery'));
            wp_enqueue_style('jquery-colorbox-style-tt', plugins_url('../colorbox-themes/' . get_option('tt-overlay-box-choose') . '/colorbox.css', __FILE__));
        }
        wp_enqueue_style('tt-frontend-style', plugins_url('../styles/frontend-style.css', __FILE__));

    //}
}

?>