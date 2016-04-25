<?php
if (isset($_POST['tt-settings-submit']) && $_POST['tt-settings-submit'] == 'Y') {
    tt_admin_categories_save();
}
foreach(Category::getAllCategories() as $category) {
    if(isset($_POST['delete-category-' . $category->getId()])) {
        tt_admin_delete_category($category->getId());
    }
}

global $wpdb;
?>
<form method="post" action="<?php admin_url('time-table.php?page=courses') ?>">

<?php
wp_nonce_field("tt-settings-page");
settings_fields('tt-settings');
do_settings_sections('tt-settings');

?>

    <div class="tt-settings-section">
        <div class="tt-settings-header">
            <h2>Kategorie anlegen</h2>
        </div>
        <br />
        <div class="tt-settings-content">
            <table>
                <tr>
                    <td>
                        <label>Kategoriename</label>
                    </td>
                    <td>
                        <input type="text" id="tt-category-add-name" name="tt-category-name-new" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Farbe wählen</label>
                    </td>
                    <td>
                        <input type="text" id="tt-category-add-color" name="tt-category-color-new" />
                    </td>
                </tr>
            </table>
            <input type="submit" class="button-secondary" value="Kategorie hinzufügen" id="tt-category-add-button" name="tt-category-add" />
           </div>
           <br />
    </div>
        <div class="tt-settings-section">
           <div class="tt-settings-header">
                       <h2>Kurs-Kategorien</h2>
            </div>
            <br />
            <div class="tt-settings-content">
            <div id="tt-categories">

                <?php
                $categories = Category::getAllCategories();
                foreach ($categories as $category) {
                    ?>
                    <div class="tt-category">
                    <input type="text" name="tt-name-category[]" value="<?php echo $category->getName(); ?>" />
                    <input type="hidden" name="tt-id-category[]" value="<?php echo $category->getId(); ?>" />
                    <input id="tt-color-category-id-<?php echo $category->getId(); ?>" type="text" name="tt-color-category[]" value="<?php echo $category->getColor(); ?>" />
                    <input type="submit" id="delete-category-<?php echo $category->getId(); ?>" name="delete-category-<?php echo $category->getId(); ?>" value="Löschen" class="button-secondary" />
                    <script type="text/javascript">
                        (function($){
                            jQuery(window).ready(function() {
                                jQuery("#tt-color-category-id-<?php echo $category->getId(); ?>").wpColorPicker({
                                    change: function() {
                                        ttChangedWithoutSave = true;
                                    }
                                });
                                $("#delete-category-<?php echo $category->getId(); ?>").click(function() {
                                    return confirm("Sind Sie sicher, dass Sie diese Kategorie löschen möchten?");
                                });
                            });
                        })(jQuery)

                    </script>
                    </div>
                    <?php
                }
                if (sizeof($categories) == 0) {
                    ?>
                    <p>
                    Erstellen Sie neue Kategorien mit dem 'Kategorie hinzufügen'-Button.
</p>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <script language="javascript" type="text/javascript">
        (function($){
            jQuery(window).ready(function() {
                jQuery("#tt-category-add-color").wpColorPicker({
                                    change: function() {
                                        ttChangedWithoutSave = true;
                                    }
                                });
            });
        })(jQuery)
        </script>

    <?php
    function tt_admin_categories_save() {
    global $wpdb;
        $tablename = $wpdb->prefix . 'time_table_category';
        $success = true;
        if (isset($_POST['tt-id-category'])){

        foreach ($_POST['tt-id-category'] as $key => $categoryId) {
            $name = $_POST['tt-name-category'][$key];
            $color = $_POST['tt-color-category'][$key];
            if ($name == "") {
                ?>
                <div id="message" class="error notice is-dismissible">
                    <p>Der Name darf nicht leer sein</p>
                </div>
                <?php
                $success = false;
            }else if ($color == "") {
            ?>
                <div id="message" class="error notice is-dismissible">
                    <p>Es muss eine Farbe ausgewählt werden</p>
                </div>
                <?php
                $success = false;
            }else {
                $values = array(
                    'name' => $name,
                    'color' => $color
                );
                $success = $success && ($wpdb->update(
                                $tablename, $values, array(
                            'id' => $categoryId
                                )
                        )) !== false;
            }
        }
        }

        if (isset($_POST['tt-category-add'])) {
        if (isset($_POST['tt-category-name-new']) && isset($_POST['tt-category-color-new'])){
            $name = $_POST['tt-category-name-new'];
            $color = $_POST['tt-category-color-new'];
            if ($name == "") {
                ?>
                <div id="message" class="error notice is-dismissible">
                    <p>Der Name darf nicht leer sein</p>
                </div>
                <?php
                $success = false;
            }else if ($color == "") {
            ?>
                <div id="message" class="error notice is-dismissible">
                    <p>Es muss eine Farbe ausgewählt werden</p>
                </div>
                <?php
                $success = false;
            }else {
                $values = array(
                    'name' => $name,
                    'color' => $color
                );
                $success = $success && ($wpdb->insert(
                                $tablename, $values)) !== false;
            }
            if ($success) {
            ?>
            <div id="message" class="updated notice is-dismissible">
                <p>Kategorien erfolgreich gespeichert</p>
            </div>
            <?php
        } else {
            ?>
            <div id="message" class="error notice is-dismissible">
                <p>Beim Speichern der Kategorien trat ein Fehler auf</p>
            </div>
            <?php
        }
        }
        }
}

    function tt_admin_delete_category($id) {
        global $wpdb;
        $category_usages = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'time_table_course WHERE category = ' . $id);
        if (sizeof($category_usages) > 0) {
            ?>
            <div id="message" class="error notice is-dismissible">
                <p>Die Kategorie kann noch nicht gelöscht werden, da sie in folgenden Kursen verwendet wird: <br />
                <?php
                    foreach ($category_usages as $category_usage) {
                        echo ' - ' . $category_usage->name . '<br />';
                    }
                 ?>
                </p>
            </div>
            <?php
        }else {
            $tablename = $wpdb->prefix . 'time_table_category';
            $wpdb->delete($tablename, array('ID' => $id));
        }
    }