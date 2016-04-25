<?php add_thickbox(); ?>

<h1 id="top">Dokumentation des Kursplan-Plugins</h1>
<figure id="tt-help-image-time-table">
    <a href="#TB_inline?width=600&height=450&inlineId=tt-help-image-time-table" class="thickbox">
        <img src="<?php echo WP_PLUGIN_URL; ?>/time-table/images/help-time-table.png" height="200" title="Beispiele eines Kursplans" /> <br />
    </a>
    <figcaption>Beispiele eines Kursplans</figcaption>
</figure>
<p>
    Diese Hilfe-Seite erklärt die Funktionen des <i>Kursplan-Plugins</i>. <br /><br />
    Das Kursplan-Plugin ermöglicht es einen <strong>wöchentlichen Stundenplan</strong> mit verschiedenen <strong>Kursen</strong> zu erstellen. <br />
    Die Kurse können zu unterschiedlichen <strong>Kategorien</strong> gehören, die farblich hervorgehoben werden. <br />
    Es ist weiterhin möglich Beschreibungstexte und Bilder zu den Kursen anzugeben, die bei einem Klick auf den jeweiligen <br />
    Kurs im Kursplan in einer individualisierbaren <strong>Vorschaubox (Lightbox)</strong> angezeigt werden. <br />
    Der Kursplan ist selbstverständlich für <strong>mobile Endgeräte</strong> optimiert, sodass alle Funktionen auch auf <br />
    Handys und Tablets bequem abrufbar sind. <br />
    Außerdem kann eine <strong>PDF-Datei</strong> zum Ausdrucken generiert werden, die den kompletten Kursplan beinhaltet.
</p>

<hr />

<h2>Inhalte:</h2>
<ol>
    <li><a href="#settings-page">Einstellungen</a></li>
    <ol>
        <li><a href="#settings-page-general">Allgemeine Einstellungen</a></li>
        <li><a href="#settings-page-lightbox">Lightbox</a></li>
        <li><a href="#settings-page-pdf">PDF</a></li>
    </ol>

    <li><a href="#categories-page">Kategorien verwalten</a></li>
    <ol>
        <li><a href="#categories-page-add">Kategorien anlegen</a></li>
        <li><a href="#categories-page-modify">Kategorien ändern und löschen</a></li>
    </ol>

    <li><a href="#courses-page">Kurse verwalten</a></li>
    <ol>
        <li><a href="#courses-page-add">Kurse hinzufügen</a></li>
        <li><a href="#courses-page-modify">Kurse ändern und löschen</a></li>
    </ol>

    <li><a href="#time-table-page">Den Kursplan verwalten</a></li>
    <ol>
        <li><a href="#time-table-page-add">Kurse eintragen</a></li>
        <li><a href="#time-table-page-modify">Eingetragene Kurse löschen</a></li>
    </ol>

    <li><a href="#import-export-page">Importieren und Exportieren</a></li>
    <ol>
        <li><a href="#import-export-page-export">Exportieren</a></li>
        <li><a href="#import-export-page-export">Importieren</a></li>
    </ol>

    <li><a href="#usage">Verwendung des Kursplans</a></li>
</ol>

<hr />

<h2 id="settings-page">1. Einstellungen</h2>
<section>
    Auf der Einstellungs-Seite kann die Darstellung des Kursplans angepasst werden.

    <h3 id="settings-page-general">1.1 Allgemeine Einstellungen</h3>
    <section>
        <p>
            In den allgemeinen Einstellungen lässt sich ein <strong>Hintergrundbild</strong> für den Kursplan festlegen. <br />
            Das Hintergrundbild wird zentriert und nicht verzerrt dargestellt. Deswegen empfiehlt es sich ein größeres Bild zu verwenden. <br />
        </p>
        <p>
            Es kann eine Breite für den Kursplan angegeben werden, die die maximale Ausdehnung des Kursplans definiert. <br />
            Sollte der Bildschirm kleiner sein als die festgelegte Breite des Kursplans, passt dieser sich an die Breite <br  />
            des Bildschirms an (responsiveness). <br />
            Da für die Darstellung der Kurse im Kursplan etwas Platz benötigt wird, empfiehlt es sich eine große Breite zu verwenden (1000px bspw.) <br />
            Wird keine Breitenangabe gemacht, verwendet der Kursplan die volle zur Verfügung stehende Breite.
        </p>
    </section>

    <h3 id="settings-page-lightbox">1.2 Lightbox-Einstellungen</h3>
    <section>
        <figure id="tt-help-image-lightbox" style="margin-top: -50px">
            <a href="#TB_inline?width=600&height=350&inlineId=tt-help-image-lightbox" class="thickbox">
                <img src="<?php echo WP_PLUGIN_URL; ?>/time-table/images/help-lightbox.png" height="200" title="Beispiele einiger Lightboxes" /> <br />
            </a>
            <figcaption>Beispiele einiger Lightboxes</figcaption>
        </figure>
        <p>
            Die Lightbox, die beim Klicken auf einen Kurs im Kursplan angezeigt wird ist <strong>individualisierbar.</strong> <br />
            Zur Auswahl stehen 11 vorgefertigte Themes sowie die Möglichkeit im benutzerdefinierten Modus ein eigenes Hintergrundbild <br />
            beziehungsweise eine Hintergrundfarbe anzugeben.<br /><br />
        </p>
    </section>

    <h3 id="settings-page-pdf">1.3 PDF-Einstellungen</h3>
    <section>
        <p>
            Standardmäßig wird im Kursplan unten rechts ein Link zu einer <strong>PDF-Datei</strong>, die den kompletten Kursplan beinhaltet, angezeigt <br />
            Beim Klick auf den Link wird die PDF-Datei erzeugt und zum Download angeboten. <br />
            Der Link kann in den Einstellungen auch verborgen werden. <br />
            Außerdem gibt es die Möglichkeit die Inhalte der ersten und zweiten Überschrift in der PDF-Datei anzupassen <br />
            sowie die Kategorie-Legende ein- und auszublenden.
        </p>
        <p>
            <figure id="tt-help-image-pdf">
                <a href="#TB_inline?width=200&height=500&inlineId=tt-help-image-pdf" class="thickbox">
                    <img src="<?php echo WP_PLUGIN_URL; ?>/time-table/images/help-pdf.png" height="200" title="Beispiele der generierten PDF-Datei, die den kompletten Kursplan beinhaltet" /> <br />
                </a>
                <figcaption>Beispiel der generierten <br />PDF-Datei, die den <br />kompletten Kursplan beinhaltet</figcaption>
            </figure>
        </p>

    </section>
    <p>
        Änderungen an den Einstellungen werden mit einem Klick auf <i>Speichern</i> übernommmen.
    </p>
</section>

<hr />

<h2 id="categories-page">2. Kategorien verwalten</h2>
<section>
    <p>
        Kurse können unterschiedlichen <i>Kategorien</i> zugeordnet werden. Beispielsweise, um welche Art von Fitnesskurs es sich <br />
        handelt (Ausdauer, Kraft, Entspannung, etc...). <br />
        Den Kategorien können <strong>Farben</strong> gegeben werden, die auch in der Darstellung des Kursplans angezeigt werden. <br />
        Es empfielht sich zunächst ein paar Kategorien anzulegen, da beim Hinzufügen eines Kurses bereits eine Kategorie mit angegeben werden muss. <br />
        Alle bereits angelegten Kategorien werden im Reiter <i>Kategorien</i> aufgelistet.
    </p>

    <h3 id="categories-page-add">2.1 Kategorien anlegen</h3>
    <section>
        <p>
            Beim Erstellen einer neuen Kategorie muss ein Name angegeben und eine Farbe ausgewählt werden. <br />
            Mit einem Klick auf <i>Kategorie hinzufügen</i> wird die neue Kategorie angelegt.
        </p>
    </section>

    <h3 id="categories-page-modify">2.2 Kategorien ändern und löschen</h3>
    <section>
        <p>
            Angelegte Kategorien können auch <strong>bearbeitet</strong> werden. Hierbei lassen sich der Name und die ausgewählte Farbe ändern. <br />
            Mit einem Klick auf <i>Speichern</i> werden die Änderungen übernommen. <br />
            Hierbei ist ein leerer Kategorie-Name nicht erlaubt.
        </p>
        <p>
            Es ist ebenfalls möglich Kategorien wieder zu <strong>löschen</strong>. Dies geschieht mit einem Klick auf <i>Löschen</i>. <br />
            Beim Löschen ist zu beachten, dass dies nur möglich ist, wenn <strong>kein Kurs zu dieser Kategorie zugeordnet</strong> ist. <br />
            Ist dies doch der Fall, wird die Kategorie nicht gelöscht und eine Fehlermeldung weißt darauf hin, <br />
            in welchen Kursen die Kategorie noch verwendet wird.
        </p>
    </section>
</section>

<hr />

<h2 id="courses-page">3. Kurse verwalten</h2>
<section>
    <p>
        Mit dem <i>Kursplan-Plugin</i> lassen sich Kurse mitsamt Beschreibung, Vorschaubild und einem Link zu einer Detailseite anlegen. <br />
        Alle bereits erstellten Kurse werden im Reiter <i>Kurse</i> aufgelistet.
    </p>

    <h3 id="courses-page-add">3.1 Kurse hinzufügen</h3>
    <section>
        <p>
            <figure id="tt-help-image-course-add" style="margin-top: -50px">
                <a href="#TB_inline?width=900&height=300&inlineId=tt-help-image-course-add" class="thickbox">
                    <img src="<?php echo WP_PLUGIN_URL; ?>/time-table/images/help-course-add.png" height="200" title="Das Formular, mit dem ein neuer Kurs hinzugefüt werden kann" /> <br />
                </a>
                <figcaption>Das Formular, mit dem ein neuer Kurs hinzugefüt werden kann</figcaption>
            </figure>
        </p>
        <p>
            Mit einem Klick auf <i>Kurs hinzufügen</i> im Reiter <i>Kurse</i> gelangt man zu einem Formular,
            in dem die Daten für einen neuen Kurs angegeben werden können. <br />
            In dem Formular lässt sich ein Name für den Kurs vergeben, eine Kategorie und ein Vorschaubild festlegen, <br />
            ein Link zu einer Detailseite setzen sowie ein kurzer Beschreibungstext angeben. <br />
            Ein Kurs <strong>muss zumindest einen Namen und eine Kategorie besitzen</strong>. <br />
        </p>
        <p>
            Wird ein Link zu einer Detailseite angegeben, erscheint beim Klicken auf einen Kurs im Kursplan in der Lightbox <br />
            ein Button <i>Mehr Infos</i>, der auf die Detailseite verweist. Im Abschnitt <a href="#usage">Verwendung des Kursplans</a> <br />
            wird beschrieben wie sich der Button individualisieren lässt. <br />
            Weiterhin kann in dem großen Textfeld ein Text, der den Kurs beschreibt, angegeben werden. Dieser stellt <br />
            optimalerweise eine Zusammenfassung der Inhalte auf der Detailseite dar.
        </p>
    </section>

    <h3 id="courses-page-modify">3.2 Kurse ändern und löschen</h3>
    <section>
        <p>
            Im Reiter <i>Kurse</i> werden alle bereits angelegten Kurse aufgelistet. <br />
            Name, Kategorie, Vorschaubild, Link zur Kursseite und der Beschreibungstext sind veränderbar. <br />
            Änderungen können mit einem Klick auf <i>Speichern</i> übernommen werden. <br />
        </p>
        <p>
            Mit einem Klick auf <i>Kurs löschen</i> wird der Kurs gelöscht.
            Hierbei ist zu beachten, dass der Kurs auch gelöscht wird, falls er im Kursplan verwendet wird. <br />
            In dem Fall werden die Eintragungen im Kursplan ebenfalls entfernt.
        </p>
    </section>
</section>

<hr />

<h2 id="time-table-page">4. Den Kursplan verwalten</h2>
<section>
    <p>
        Im Reiter <i>Kursplan</i> werden alle eingetragenen Kurse mit ihren jeweiligen Zeiten nach Wochentagen aufgelistet.
    </p>

    <h3 id="time-table-page-add">4.1 Kurse eintragen</h3>
    <section>
        <p>
            Im oberen Bereich kann ein Kurs an einem Zeitraum in den Kursplan eingetragen werden. <br />
            Eine Dropdown-Liste, die viertelstündliche Uhrzeiten anzeigt, bietet dabei zusätzlich Unterstützung. <br />
            Es können aber auch Uhrzeiten, die nicht an den Viertelstundentakt gebunden sind, angegeben werden. <br />
            Diese schreibt man einfach manuell in das Textfeld, das Format ist hierbei hh:mm also beispielsweise 08:30 oder 14:15. <br />
            Prinzipiell kann jede Uhrzeit angegeben werden, zu beachten ist jedoch, dass nur Kurse im Zeitraum 08:00 - 22:00 angezeigt werden. <br />
            Mit einem Klick auf <i>Kurs eintragen</i> wird der Kurs zu dem angegebenen Zeitraum eingetragen. <br />
            Fehlerfälle wie keinen Kurs angegeben, keine Uhrzeit angegeben, Uhrzeit im falschen Format oder Endzeit vor Anfangszeit <br />
            werden in Fehlermeldungen abgefangen. Außerdem dürfen sich <strong>keine Kurse überschneiden</strong>. Versucht man also <br />
            einen Kurs an einem Zeitraum, zu dem am selben Tag bereits ein anderer Kurs stattfindet, einzutragen, so wird der Kurs nicht übernommen. <br />
        </p>
        <p>
            Im unteren Bereich werden die eingetragenen Kurse aufgelistet. ein Button <i>Bearbeiten</i> bietet zusätzlich <br />
            die Möglichkeit die Daten eines Kurses in einem Formaular zu ändern.
        </p>
    </section>

    <h3 id="time-table-page-modify">4.2 Eingetragene Kurse löschen</h3>
    <section>
        <p>
            Eingetragene Kurse können auch wieder gelöscht werden. Dies geschieht über den Button <i>Löschen</i>.
        </p>
    </section>
</section>

<hr />

<h2 id="import-export-page">5. Importieren und Exportieren</h2>
<section>
    <p>
        Alle eingetragenen Kurse, Kategorien und Zeiten können bequem in eine einzige Datei exportiert werden. <br />
        Diese Daten können später in einen anderen Kursplan wieder importiert werden, was eine <strong>einfache Migration</strong> <br />
        <strong>von Kursplänen</strong> ermöglicht.
    </p>

    <h3 id="import-export-page-export">5.1 Exportieren</h3>
    <section>
        <p>
            Mit einem Klick auf <i>Exportieren</i> wird eine sql-Datei erzeugt, die alle Daten des Kursplanes beinhaltet, <br />
            und diese zum Download angeboten. <strong>Diese Datei sollte auf keinen Fall verändert werden!</strong> <br />
            Das Export-Feature eignet sich auch um <strong>Backups</strong> anzulegen oder um <strong>zwischen unterschiedlichen Kursplänen bequem zu wechseln</strong>.
        </p>
    </section>

    <h3 id="import-export-page-import">5.2 Importieren</h3>
    <section>
        <p>
            Die Daten eines exportierten Kursplans können wieder importiert werden, indem die zugehörige .sql-Datei ausgewählt <br />
            und mit einem Klick auf <i>Importieren</i> hochgeladen wird. <br />
            <strong>Hier sollte nichts anderes als ein exportierter Kursplan ausgewählt werden! Das Hochladen von anderen Dateien </strong><br />
            <strong>kann ungewolltes Verhalten verursachen!</strong><br />
            Sollte beim Importieren etwas fehlschlagen, so wird versucht den ursprünglichen Zustand wiederherzustellen. <br />
            Da dies nicht immer möglich ist, sollte man vor jedem Import zuerst die Daten des Kursplans <a href="#import-export-page-export">exportieren</a>. <br />
            Beim Importieren ist außerdem zu beachten, dass Daten die noch im Kursplan enthalten sind, <strong>überschrieben</strong> werden!
        </p>
    </section>
</section>

<hr />

<h2 id="usage">6. Verwendung des Kursplans</h2>
<section>
    <p>
        Der Kursplan kann ganz einfach mithilfe der Shortcodes
    </p>
    <ul style="padding-left: 20px">
        <li><strong>[time_table]</strong></li>
        <li><strong>[kurs_plan]</strong></li>
        <li>oder <strong>[Kursplan]</strong></li>
    </ul>
    <p>
        in eine Seite eingebunden werden. <br />
        Dabei gibt es die Möglichkeit die Darstellung mittels Parameter zusätzlich abzuändern. <br />
        Parameter können dem Kursplan im Format [Kursplan <i>Parametername</i>="<i>Wert</i>"] übergeben werden, wobei <br />
        <i>Parametername</i> und <i>Wert</i> der folgenden Tabelle zu entnehmen sind. <br />
    </p>
    <table cellpadding="5" class="tt-params-table">
        <tr>
            <td width="100"><strong>Parametername</strong></td>
            <td width="100"><strong>Wert</strong></td>
            <td width="200"><strong>Beschreibung</strong></td>
        </tr>
        <tr class="tt-params-table-new-param">
            <td rowspan="4">more-info-link</td>
            <td colspan="2">Mit diesem Parameter kann der <i>Mehr Infos</i>-Button in der Lightbox verändert werden</td>
        </tr>
        <tr valign="top">
            <td>show <i>(default)</i></td>
            <td>Der Button wird angezeigt</td>
        </tr>
        <tr valign="top">
            <td>new-page</td>
            <td>Der Button wird angezeigt und beim Klicken auf den Link wird die Detailseite in einem neuen Tab geöffnet</td>
        </tr>
        <tr valign="top">
            <td>hide</td>
            <td>Der Button wird verborgen</td>
        </tr>
    </table>
    <p>
        <strong>Beispiel: </strong><br />
        <code>
            [Kursplan more-info-link="new-page"]
        </code><br />
        erzeugt einen Kursplan, in dem die <i>Mehr Infos</i>-Buttons angezeigt werden und die Detailseite in einem neuen Tab <br />
        geöffnet werden.
    </p>
</section>

<a href="#top">nach oben</a>