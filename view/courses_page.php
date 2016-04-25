<?php
if (isset($_POST['tt-settings-submit']) && $_POST['tt-settings-submit'] == 'Y') {
    tt_admin_courses_save();
}

global $wpdb;
?>
<form method="post" action="<?php admin_url('time-table.php?page=courses') ?>">
    <p class="submit" style="clear: both;">
        <input type="submit" name="submit"  class="button-primary" value="Speichern" />
        <a href="admin.php?page=time-table&tab=add-course" class="button-secondary">Kurs hinzufügen</a>
    </p>
    <table class="form-table" id="tt-list-courses">
        <?php
        wp_nonce_field("tt-settings-page");
        settings_fields('tt-settings');
        do_settings_sections('tt-settings');
        //$doors = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'advent_calendar_door');
        $courses = Course::getAllCourses();
        foreach ($courses as $course) {
            ?>
            <tr style="border-bottom:1px solid #ccc;">
                <td>
                <table width="100%">
                <tr>
                <td>
                <label>Kurs</label>
                </td>
                <td>
                <input type="text" name="tt-name-course[]"
                                    value="<?php echo $course->getName(); ?>" />
                </td>
                </tr>
                <tr>
                <td>
                <label>Kategorie</label>
                </td>
                <td>
                <select name="tt-category-course[]">
                <option value="choose">Kategorie w&auml;hlen</option>
                    <?php
                        $categories = Category::getAllCategories();
                        foreach ($categories as $category){
                            ?><option value="<?php echo $category->getId(); ?>"<?php selected($category->getId() == $course->getCategory()->getId()); ?>><?php echo $category->getName(); ?></option><?php
                        }
                    ?>
                    </select>
</td>
</tr>
<tr>
<td>
<label>Vorschaubild</label>
</td>
<td>
<img src="<?php echo $course->getImage(); ?>" width="112"
                         id="tt-image-course-<?php echo $course->getId(); ?>" />
                    <input type="hidden" name="tt-image-course[]"
                           value="<?php echo $course->getImage(); ?>"
                           id="tt-image-hidden-course-<?php echo $course->getId(); ?>"/>
                    <input type="button" name="upload-btn"
                           id="upload-btn-<?php echo $course->getId(); ?>"
                           class="button-secondary" value="Bild hochladen"/>
</td>
</tr>
<tr>
<td>
<label>Link zur Kursseite</label>
</td>
<td>
<input type="text" name="tt-link-course[]" class="large-text" value="<?php echo $course->getLink(); ?>" />
</td>
</tr>
<tr>
<td>
<input type="submit" class="tt-delete-course button-secondary" name="tt-delete-course-<?php echo $course->getId(); ?>" value="Kurs löschen" />
</td>
</tr>
</table>
<input type="hidden" name="tt-course-id[]" value="<?php echo $course->getId(); ?>" />


                </td>
                <td>
                    <?php
                    wp_editor($course->getDescription(), 'tt-description-course-' . $course->getId(), array(
                        'textarea_name' => 'tt-content-course[]',
                        'textarea_rows' => 10));
                    ?>
                </td>
            </tr>

            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    $('#upload-btn-<?php echo $course->getId(); ?>').click(function(e) {
                        e.preventDefault();
                        var image = wp.media({
                            title: 'Upload Image',
                            // mutiple: true if you want to upload multiple files at once
                            multiple: false
                        }).open()
                                .on('select', function(e) {
                                    // This will return the selected image from the Media Uploader, the result is an object
                                    var uploaded_image = image.state().get('selection').first();
                                    // We convert uploaded_image to a JSON object to make accessing it easier
                                    // Output to the console uploaded_image
                                    console.log(uploaded_image);
                                    var image_url = uploaded_image.toJSON().url;
                                    // Let's assign the url value to the input field
                                    $('#tt-image-hidden-course-<?php echo $course->getId(); ?>').val(image_url).trigger('change');
                                    $('#tt-image-course-<?php echo $course->getId(); ?>').attr('src', image_url);
                                });
                    });
                });
            </script>
            <?php
        }
        ?>
    </table>
    <script language="javascript" type="text/javascript">
        (function($) {
            $(window).ready(function() {
                $('.tt-delete-course').click(function() {
                    return confirm('Sind Sie sicher, dass Sie diesen Kurs löschen möchten? Alle Einträge des Kurses im Kursplan werden ebenfalls entfernt');
                })
            });
        })(jQuery);
    </script>
    <?php

    function tt_admin_courses_save() {
        global $wpdb;
        $courses = Course::getAllCourses();
        $tablename = $wpdb->prefix . 'time_table_course';
        $success = true;
        foreach ($courses as $course) {
            if (isset($_POST['tt-delete-course-' . $course->getId()])) {
                $wpdb->delete($tablename, array('ID' => $course->getId()));
                $wpdb->delete($wpdb->prefix . 'time_table', array(
                    'course' => $course->getId()
                ));
            }
        }
        foreach ($_POST['tt-course-id'] as $key => $course_id) {
                $image = stripslashes_deep($_POST['tt-image-course'][$key]);
                $description = stripslashes_deep($_POST['tt-content-course'][$key]);
                $category = $_POST['tt-category-course'][$key];
                $name = $_POST['tt-name-course'][$key];
                $link = stripslashes_deep($_POST['tt-link-course'][$key]);
                if ($name == "") {
                    ?>
                    <div id="message" class="error notice is-dismissible">
                        <p>Der Name darf nicht leer sein</p>
                    </div>
                    <?php
                    $success = false;
                }else if ($category == "choose") {
                ?>
                    <div id="message" class="error notice is-dismissible">
                        <p>Es muss eine Kategorie ausgewählt werden</p>
                    </div>
                    <?php
                    $success = false;
                }else {
                    $values = array(
                        'image' => $image,
                        'description' => $description,
                        'category' => $category,
                        'name' => $name,
                        'link' => $link
                    );
                    $success = $success && ($wpdb->update(
                                    $tablename, $values, array(
                                'id' => $course_id
                                    )
                            )) !== false;
                }

        }
        if ($success) {
            ?>
            <div id="message" class="updated notice is-dismissible">
                <p>Kurse erfolgreich gespeichert</p>
            </div>
            <?php
        } else {
            ?>
            <div id="message" class="error notice is-dismissible">
                <p>Beim Speichern der Kurse trat ein Fehler auf</p>
            </div>
            <?php
        }
    }

