<?php

function tt_get_attachment_id_from_src($image_src) {
    global $wpdb;
    $query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
    $id = $wpdb->get_var($query);
    return $id;
}

function tt_print_image_dimensions($image_src) {
    if ($image_src != '') {
        $img_meta = wp_get_attachment_metadata(tt_get_attachment_id_from_src($image_src));
        echo '<div class="ac-img-dimensions">' . $img_meta['width'] . 'x' . $img_meta['height'] . '</div>';
    }
}

function relativize_image_url($image_src) {
    echo 'input: ' . $image_src;
    $path = bloginfo('url');
    return str_replace($path,'',$image_src);
}