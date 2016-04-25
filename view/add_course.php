<?php

if (isset($_POST['tt-settings-submit']) && $_POST['tt-settings-submit'] == 'Y') {
    tt_admin_courses_save();
}

if (isset($_GET['course-id'])) {
    $course = new Course($_GET['course-id']);
}else {
    $course = new Course(null);
}

global $wpdb;
?>
<form method="post" action="<?php admin_url('time-table.php?page=add-course') ?>">
<?php if (isset($_GET['course-id'])) {
?>
<h2>Kurs <span style="font-style:italic">[<?php echo $course->getName(); ?>]</span> bearbeiten</h2>
<?php
}else {
?>
<h2>Kurs hinzuf&uuml;gen</h2>
<?php
}
?>
    <table class="form-table" id="tt-list-courses">
        <?php
        wp_nonce_field("tt-settings-page");
        settings_fields('tt-settings');
        do_settings_sections('tt-settings');

            ?>
            <tr>
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
</table>



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
    </table>
    <?php

    function tt_admin_courses_save() {
        global $wpdb;
        $tablename = $wpdb->prefix . 'time_table_course';
        $success = true;
            $image = stripslashes_deep($_POST['tt-image-course'][0]);
            $description = stripslashes_deep($_POST['tt-content-course'][0]);
            $category = $_POST['tt-category-course'][0];
            $name = $_POST['tt-name-course'][0];
            $link = stripslashes_deep($_POST['tt-link-course'][0]);
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
                if (isset($_GET['course-id'])) {
                    $success = $success && ($wpdb->update(
                                $tablename, $values, array(
                            'id' => $_GET['course-id']
                                )
                        )) !== false;
                }else {
                    $success = $success && ($wpdb->insert(
                                $tablename, $values
                        )) !== false;
                    $_GET['course-id'] = $wpdb->insert_id;
                }
            }

        if ($success) {
            ?>
            <div id="message" class="updated notice is-dismissible">
                <p>Kurs erfolgreich gespeichert</p>
            </div>
            <?php
        } else {
            ?>
            <div id="message" class="error notice is-dismissible">
                <p>Beim Speichern des Kurses trat ein Fehler auf</p>
            </div>
            <?php
        }
    }
