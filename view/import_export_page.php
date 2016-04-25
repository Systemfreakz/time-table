
<?php
function execute_multiline_sql($sql) {
    global $wpdb;
    $sqlParts = array_filter(explode(TT_MYSQL_QUERY_SEPARATOR, $sql));
    foreach($sqlParts as $part) {
        $part = preg_replace('/DROP TABLE IF EXISTS (.*)/Usi', 'TRUNCATE $1', $part);
        if (!strstr($part, 'CREATE TABLE')) {
            $wpdb->query($part);
            if($wpdb->last_error != '') {
                $error = new WP_Error("dberror", __("Database query error"), $wpdb->last_error);
                return $error;
            }
        }
    }
    return true;
}

    if (isset($_POST['tt-import-submit']) && $_POST['tt-action'] == 'import') {
        $filePath = $_FILES['tt-import-file']['tmp_name'];
        if ($filePath == '') {
            ?>
            <div id="message" class="error notice is-dismissible">
                <p>Es muss eine SQL-Datei zum Hochladen ausgewählt werden!</p>
            </div>
            <?php
        } else {
            $backup = tt_admin_export_tables();
            $sql = file_get_contents($_FILES['tt-import-file']['tmp_name']);
            $sql = preg_replace("/\\n/","\n",$sql);
            global $wpdb;
            $result = execute_multiline_sql($sql);
            if ($result !== TRUE) {
                $result_backup = execute_multiline_sql($backup);
                ?>
                <div id="message" class="error notice is-dismissible">
                    <p>Beim Importieren ist ein Fehler aufgetreten! Es wurde der alte Zustand wieder hergestellt.</p> <br />
                    <strong>Fehlermeldung: </strong> <br />
                    <code>
                        <?php
                        echo $result->error_data['dberror'];
                        ?>
                    </code>
                </div>
                <?php
                if ($result_backup !== TRUE) {
                    ?>
                    <div id="message" class="error notice is-dismissible">
                        <p>Beim Wiederherstellen der Daten ist ein Fehler aufgetreten! Kontaktieren Sie den Autor des Plugins.</p>
                        <strong>Fehlermeldung: </strong> <br />
                        <code>
                            <?php
                            echo $result_backup->error_data['dberror'];
                            ?>
                        </code>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div id="message" class="updated notice is-dismissible">
                    <p>Kursplan erfolgreich importiert</p>
                </div>
                <?php
            }
        }
    }
?>

<div class="tt-settings-section">
    <div class="tt-settings-header">
        <h2>Kursplan exportieren</h2>
    </div>
    <br />
    <div class="tt-settings-content">
        Exportiert alle Daten des Kursplans in eine .sql-Datei. <br />
        Auch für <span style="font-style:italic">Backups</span> geeignet.<br/><br />
        <form action="<?php admin_url('time-table.php?page=import-export') ?>" method="post">
            <input type="hidden" name="tt-action" value="export" />
            <input type="submit" name="tt-export-submit" class="button-primary" value="Exportieren" />
        </form>
    </div>
</div>
<br /><br />
<div class="tt-settings-section">
    <div class="tt-settings-header">
        <h2>Kursplan importieren</h2>
    </div>
    <br />
    <div class="tt-settings-content">
        Übernimmt die Daten aus einem zuvor exportierten Kursplan. <br />
        <span style="font-weight: bold">Achtung:</span> Die Daten des jetztigen Kursplans werden <span style="font-weight: bold">überschrieben</span>! <br /><br />
        <form action="<?php admin_url('time-table.php?page=import-export') ?>" enctype="multipart/form-data" method="post">
            <input type="hidden" name="tt-action" value="import" />
            <input type="file" name="tt-import-file" /><br />
            <input type="submit" name="tt-import-submit" class="button-primary" value="Importieren" />
        </form>
    </div>
</div>




<?php

?>