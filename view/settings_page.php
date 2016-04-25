<h1>
    Einstellungen für den Kursplan
</h1>

<form method="post" action="options.php">
    Füge den Kursplan mithilfe des Shortcodes <strong>[time_table]</strong>, <strong>[Kursplan]</strong> oder
    <strong>[kurs_plan]</strong> hinzu
    <br /><br />
    <div class="tt-settings-section">
        <div class="tt-settings-header">
            <h2>Allgemeine Einstellungen</h2>
        </div>
        <div class="tt-settings-content">
            <table class="form-table">
                <?php
                wp_nonce_field("tt-settings-page");
                settings_fields('tt-settings');
                do_settings_sections('tt-settings');
                $image_src = get_option('tt-background-image');
                ?>
                <tr>
                    <th><label for="image_url">Hintergrundbild Kursplan</label></th>
                    <td>
                        <img id="tt-background-img" src="<?php echo $image_src; ?>" width="200" />
                        <?php tt_print_image_dimensions($image_src); ?>
                        <input type="hidden" name="tt-background-image" id="image_url" value="<?php echo get_option('tt-background-image'); ?>">
                        <input type="button" name="upload-btn" id="upload-btn" class="button-secondary" value="Bild hochladen">
                    </td>
                </tr>
                <tr>
                    <th><label for="tt-width">Breite des Kursplans</label></th>
                    <td>
                        <input type="text" name="tt-width" id="tt-width" class="small-text" value="<?php echo get_option('tt-width'); ?>">px
                    </td>
                </tr>
                <!--tr>
                    <th><label for="tt-height">Höhe des Kursplans</label></th>
                    <td>
                        <input type="text" name="tt-height" id="tt-height" class="small-text" value="<?php echo get_option('tt-height'); ?>">px
                    </td>
                </tr-->
            </table>
        </div>
    </div>


    <div class="tt-settings-section">
        <div class="tt-settings-header">
            <h2>Lightbox Einstellungen</h2>
        </div>
        <div class="tt-settings-content">
            <table class="form-table">
                <tr>
                    <th><label for="tt-overlay-box-choose">Lightbox auswählen</label></th>
                    <td>
                        <select id="tt-overlay-box-choose" name="tt-overlay-box-choose">
                            <option value="custom">Benutzerdefiniert</option>
                            <optgroup label="Themes">
                                <?php
                                $current_dir = new WP_Theme('time-table', WP_PLUGIN_DIR);
                                $themes = scandir(WP_PLUGIN_DIR . '/time-table/colorbox-themes');
                                foreach ($themes as $theme) {
                                    if ($theme != '.' && $theme != '..') {
                                        echo '<option value="' . $theme . '"' . selected(get_option('tt-overlay-box-choose') == $theme) . '>' . $theme . '</option>';
                                    }
                                }
                                ?>
                            </optgroup>
                        </select>
                    </td>
                </tr>
                <tr class="tt-overlay-box-custom">
                    <th><label for="tt-overlay-box-background-image">Hintergrundbild der Lightbox</label></th>
                    <td>
                        <img id="tt-overlay-box-background-img" src="<?php echo get_option('tt-overlay-box-background-image'); ?>" width="200" />
                        <input type="hidden" id="tt-overlay-box-background-image"
                               name="tt-overlay-box-background-image" value="<?php echo get_option('tt-overlay-box-background-image'); ?>" />
                        <input type="button" id="tt-overlay-box-background-image-button" class="button-secondary" value="Bild hochladen" />
                        <input type="button" id="tt-overlay-box-background-image-button-delete" class="button-secondary" value="Bild löschen" />
                    </td>
                </tr>
                <tr class="tt-overlay-box-custom">
                    <th><label for="tt-overlay-box-background-color">Hintergrundfarbe der Lightbox</label></th>
                    <td>
                        <input type="text" id="tt-overlay-box-background-color"
                               name="tt-overlay-box-background-color" value="<?php echo get_option('tt-overlay-box-background-color'); ?>" />
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="tt-settings-section">
        <div class="tt-settings-header">
            <h2>PDF - Einstellungen</h2>
        </div>
        <div class="tt-settings-content">
            <table class="form-table">
                <tr>
                    <th><label for="tt-pdf-show-link">Link zur PDF-Datei im Kursplan anzeigen</label></th>
                    <td>
                        <input type="checkbox" id="tt-pdf-show-link" name="tt-pdf-show-link" value="1"<?php checked(1 == get_option('tt-pdf-show-link')); ?> />
                    </td>
                </tr>
                <tr class="tt-pdf-settings">
                    <th><label for="tt-pdf-h1">H1 - Überschrift im PDF</label></th>
                    <td>
                        <input type="text" id="tt-pdf-h1" name="tt-pdf-h1" class="large-text" value="<?php echo get_option('tt-pdf-h1'); ?>" style="max-width:400px" />
                    </td>
                </tr>
                <tr class="tt-pdf-settings">
                    <th><label for="tt-pdf-h2">H2 - Überschrift im PDF</label></th>
                    <td>
                        <input type="text" id="tt-pdf-h2" name="tt-pdf-h2" class="large-text" value="<?php echo get_option('tt-pdf-h2'); ?>" style="max-width:400px" />
                    </td>
                </tr>
                <tr class="tt-pdf-settings">
                    <th><label for="tt-pdf-show-legend">Kategorie-Legende im PDF anzeigen</label></th>
                    <td>
                        <input type="checkbox" id="tt-pdf-show-legend" name="tt-pdf-show-legend" value="1"<?php checked(1 == get_option('tt-pdf-show-legend')); ?> />
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('#upload-btn').click(function(e) {
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
                            $('#image_url').val(image_url).trigger('change');
                            $('#tt-background-img').attr('src', image_url);
                        });
            });
            $('#tt-overlay-box-background-image-button').click(function(e) {
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
                            $('#tt-overlay-box-background-image').val(image_url).trigger('change');
                            $('#tt-overlay-box-background-img').attr('src', image_url);
                        });
            });
            $('#tt-overlay-box-background-image-button-delete').click(function() {
                $('#tt-overlay-box-background-image').val('').trigger('change');
                $('#tt-overlay-box-background-img').attr('src', '');
            });
            $('#tt-overlay-box-background-color').wpColorPicker({
                change: function() {
                    ttChangedWithoutSave = true;
                }
            });
            $('#tt-overlay-box-choose').change(function() {
                if ($(this).val() == 'custom') {
                    $('.tt-overlay-box-custom').fadeIn();
                } else {
                    $('.tt-overlay-box-custom').fadeOut();
                }
            });
            $('#tt-pdf-show-link').change(function() {
                if (this.checked) {
                    $('.tt-pdf-settings').fadeIn();
                } else {
                    $('.tt-pdf-settings').fadeOut();
                }
            })
            $("#tt-category-add-color").wpColorPicker({
                change: function() {
                    ttChangedWithoutSave = true;
                }
            });
        });
    </script>
    <style>
<?php
if (get_option('tt-overlay-box-choose') != 'custom') {
    ?>
            .tt-overlay-box-custom {
                display: none;
            }
    <?php
}
if (get_option('tt-pdf-show-link') != 1) {
?>
    .tt-pdf-settings {
        display: none;
    }
<?php
}
?>
    </style>

