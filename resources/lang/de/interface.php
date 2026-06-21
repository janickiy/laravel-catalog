<?php

return [
    'language' => [
        'label' => 'Sprache der Benutzeroberfläche',
        'ru' => 'Русский',
        'en' => 'English',
    ],

    'common' => [
        'actions' => 'Aktionen',
        'add' => 'Hinzufügen',
        'add_subcategory' => 'Unterkategorie hinzufügen',
        'administrator' => 'Administrator',
        'all' => 'Alle',
        'all_categories' => 'Alle Kategorien',
        'archive' => 'Archiv',
        'author' => 'Autor',
        'back' => 'Zurück',
        'category' => 'Kategorie',
        'catalog' => 'Katalog',
        'choose' => 'Auswählen',
        'city' => 'Stadt',
        'close' => 'Schließen',
        'contacts' => 'Kontakte',
        'created_at' => 'Erstellt am',
        'date' => 'Datum',
        'delete' => 'Löschen',
        'description' => 'Beschreibung',
        'edit' => 'Bearbeiten',
        'email' => 'E-Mail',
        'export' => 'Exportieren',
        'file_formats' => 'Formate',
        'full_description' => 'Vollständige Beschreibung',
        'html_banner' => 'HTML-Bannercode',
        'icon' => 'Symbol',
        'id' => 'ID',
        'import' => 'Importieren',
        'ip' => 'IP',
        'keywords' => 'Schlüsselwörter',
        'login' => 'Login',
        'max_size' => 'Maximale Größe',
        'message' => 'Nachricht',
        'misc' => 'Sonstiges',
        'name' => 'Name',
        'no_admins' => 'Keine Administratoren',
        'no_data' => 'Keine Daten',
        'no_links' => 'Keine Links',
        'no_messages' => 'Noch keine Nachrichten',
        'optional' => 'Optional',
        'password' => 'Passwort',
        'password_repeat' => 'Passwort wiederholen',
        'phone' => 'Telefon',
        'required_fields' => '* Pflichtfelder',
        'required_fields_frontend' => 'Mit einem Sternchen markierte Felder sind Pflichtfelder.',
        'save' => 'Speichern',
        'site' => 'Website',
        'site_address' => 'Website-Adresse',
        'status' => 'Status',
        'submit' => 'Absenden',
        'updated_at' => 'Aktualisiert am',
        'url' => 'URL',
        'value' => 'Wert',
        'view' => 'Ansehen',
        'views' => 'Aufrufe',
    ],

    'auth' => [
        'page_title' => 'Login',
        'title' => 'Anmeldung im Administrationsbereich',
        'login_placeholder' => 'Login eingeben',
        'password_placeholder' => 'Passwort eingeben',
        'remember' => 'Angemeldet bleiben',
        'submit' => 'Anmelden',
    ],

    'admin' => [
        'panel' => 'Systemsteuerung',
        'dashboard' => 'Dashboard',
        'categories' => 'Kategorien',
        'feedback' => 'Website-Nachrichten',
        'administrators' => 'Administratoren',
        'logout' => 'Abmelden',
        'breadcrumb_panel' => 'Systemsteuerung',

        'dashboard_cards' => [
            'total_links' => 'Links insgesamt',
            'categories' => 'Kategorien',
            'messages' => 'Nachrichten',
            'administrators' => 'Administratoren',
            'settings' => 'Einstellungen',
            'pending' => 'Ausstehende Prüfung',
        ],
        'dashboard_card_descriptions' => [
            'total_links' => 'Katalogeinträge',
            'categories' => 'Abschnitte und Themen',
            'messages' => 'Benutzeranfragen',
            'administrators' => 'Panel-Benutzer',
            'settings' => 'Projektoptionen',
            'pending' => 'Neue Einreichungen',
        ],
        'dashboard_sections' => [
            'link_statuses' => 'Link-Status',
            'quick_actions' => 'Schnellaktionen',
            'top_views' => 'Top-Aufrufe',
            'latest_links' => 'Neueste Links',
            'latest_messages' => 'Neueste Nachrichten',
            'administrators' => 'Administratoren',
            'all_links' => 'Alle Links',
            'open_section' => 'Abschnitt öffnen',
        ],
        'quick_actions' => [
            'add_link' => 'Link hinzufügen',
            'import_links' => 'Links importieren',
            'export_links' => 'Links exportieren',
            'add_category' => 'Kategorie hinzufügen',
        ],

        'links' => [
            'title' => 'Links',
            'subtitle' => 'Katalog-Links verwalten',
            'create_title' => 'Link hinzufügen',
            'edit_title' => 'Link bearbeiten',
            'show_title' => 'Link ansehen',
            'form_create_title' => 'Link hinzufügen',
            'form_edit_title' => 'Link bearbeiten',
            'main_data' => 'Hauptdaten',
            'contacts' => 'Kontakte',
            'description_section' => 'Beschreibung',
            'state' => 'Status',
            'existing' => 'Eintrag vorhanden',
            'new' => 'Neuer Eintrag',
            'will_publish_after_save' => 'Wird nach dem Speichern veröffentlicht',
            'screenshot' => 'Website-Screenshot',
            'screenshot_missing' => 'Screenshot wurde nicht hochgeladen',
            'bulk_status' => 'Massenänderung des Status',
            'choose_status' => 'Status auswählen',
            'apply' => 'Anwenden',
            'import_title' => 'Import',
            'import_heading' => 'Links importieren',
            'import_file' => 'Importdatei',
            'import_submit' => 'Importieren',
            'import_processing' => 'Import läuft...',
            'import_archive_heading' => 'Aus Archiv importieren',
            'import_archive_file' => 'ZIP-Archiv für den Import',
            'import_archive_hint' => 'Das Archiv kann CSV-, TXT-, XLS- oder XLSX-Dateien enthalten.',
            'import_archive_submit' => 'Archiv importieren',
            'import_archive_processing' => 'Archiv wird importiert...',
            'archive_format' => 'Archivformat',
            'file_parameters' => 'Dateiparameter',
            'export_title' => 'Export',
            'export_heading' => 'Links exportieren',
            'export_submit' => 'Exportieren',
            'export_parameters' => 'Exportparameter',
            'file_format' => 'Dateiformat',
            'text' => 'Text',
            'archive_type' => 'Archivierung',
            'without_archive' => 'Kein Archiv',
            'regular_file' => 'Normale Datei',
        ],

        'catalog' => [
            'title' => 'Kategorien',
            'create_title' => 'Kategorie hinzufügen',
            'edit_title' => 'Kategorie bearbeiten',
            'form_create_title' => 'Kategorie hinzufügen',
            'form_edit_title' => 'Kategorie bearbeiten',
            'main_data' => 'Hauptdaten',
            'description_section' => 'Beschreibung',
            'icon' => 'Symbol',
            'icon_file' => 'Symboldatei',
            'icon_hint' => 'JPG, GIF oder PNG. Maximale Größe: :size.',
            'parent' => 'Abschnitt',
            'parent_category' => 'Übergeordneter Abschnitt',
            'state' => 'Status',
            'existing' => 'Kategorie vorhanden',
            'new' => 'Neue Kategorie',
            'will_create_after_save' => 'Wird nach dem Speichern erstellt',
            'current_icon' => 'Aktuelles Symbol',
            'icon_missing' => 'Symbol wurde nicht hochgeladen',
        ],

        'admin_users' => [
            'title' => 'Administratoren',
            'subtitle' => 'Benutzer der Systemsteuerung verwalten',
            'create_title' => 'Administrator hinzufügen',
            'edit_title' => 'Administrator bearbeiten',
        ],

        'feedback_messages' => [
            'subtitle' => 'Nachrichten, die über das Feedbackformular gesendet wurden',
            'show_title' => 'Nachricht ansehen',
            'message_text' => 'Nachrichtentext',
        ],

        'settings' => [
            'title' => 'Einstellungen',
            'subtitle' => 'Projektoptionen verwalten',
            'create_title' => 'Einstellung hinzufügen',
            'edit_title' => 'Einstellung bearbeiten',
        ],
    ],

    'frontend' => [
        'site_title' => 'Weißer Website-Katalog',
        'main_nav' => 'Hauptnavigation',
        'home' => 'Startseite',
        'rules' => 'Regeln',
        'feedback' => 'Feedback',
        'add_site' => 'Website hinzufügen',
        'catalog' => 'Katalog',
        'home_page' => 'Startseite',
        'home_description' => 'Weißer Website-Katalog',
        'home_keywords' => 'weißer Website-Katalog, Website hinzufügen',
        'catalog_intro' => 'Wählen Sie einen Abschnitt und finden Sie relevante Websites in einem strukturierten Katalog.',
        'sites' => 'Websites',
        'recent_sites' => 'Kürzlich hinzugefügte Websites',
        'more' => 'Mehr',
        'no_site_image' => 'Kein Website-Bild',
        'footer_rules' => 'Katalogregeln',

        'new_record' => 'Neuer Eintrag',
        'add_site_title' => 'Website hinzufügen',
        'add_site_description' => 'Website hinzufügen',
        'add_site_keywords' => 'weißer Website-Katalog, URL hinzufügen, Website hinzufügen',
        'add_site_intro' => 'Füllen Sie die Website-Daten aus, wählen Sie einen Abschnitt und senden Sie den Eintrag zur Moderation.',
        'site_name' => 'Name*',
        'site_name_placeholder' => 'Website-Name',
        'section' => 'Abschnitt*',
        'choose_section' => 'Abschnitt auswählen',
        'url_address' => 'Website-URL*',
        'short_description' => 'Beschreibung*',
        'short_description_placeholder' => 'Kurze Website-Beschreibung',
        'full_description' => 'Vollständige Beschreibung*',
        'full_description_placeholder' => 'Ausführliche Website-Beschreibung',
        'keywords_placeholder' => 'Schlüsselwörter durch Kommas getrennt',
        'html_banner_placeholder' => 'HTML-Bannercode',
        'captcha' => 'Sicherheitscode*',
        'captcha_placeholder' => 'Code aus dem Bild eingeben',
        'agree_prefix' => 'Ich stimme den',
        'agree_link' => 'Teilnahmeregeln',
        'agree_suffix' => 'zu',
        'add_submit' => 'Hinzufügen',
        'link_added' => 'Die Website wurde dem Katalog hinzugefügt',
        'link_added_pending' => 'Die Website wurde dem Katalog hinzugefügt und wird nach der Prüfung verfügbar sein',

        'contact_eyebrow' => 'Kontakt',
        'contact_title' => 'Feedback',
        'contact_intro' => 'Senden Sie über das Feedbackformular eine Nachricht an die Katalogadministration.',
        'your_name' => 'Ihr Name*',
        'your_name_placeholder' => 'Ihr Name',
        'message' => 'Nachricht*',
        'message_placeholder' => 'Ihre Nachricht',
        'message_sent' => 'Ihre Nachricht wurde erfolgreich gesendet',

        'rules_eyebrow' => 'Bedingungen',
        'rules_title' => 'Regeln des Website-Katalogs',
        'site_card' => 'Website-Karte',
        'catalog_section' => 'Katalogabschnitt',
        'visits' => 'Besuche',
        'more_in_category' => 'Mehr in diesem Abschnitt',
        'similar_sites' => 'Ähnliche Websites',
        'rules_html' => <<<'HTML'
<p>Diese Katalogregeln gelten vor dem Hinzufügen einer Website und während der Nutzung des Katalogs.</p>
<ol>
    <li>Allgemeine Bedingungen
        <ol>
            <li>catalog.janicky.com bietet Benutzern die Möglichkeit, Websites in den Katalog einzutragen.</li>
            <li>Die Administration kann Websites entfernen oder eingereichte Daten bearbeiten, um ungenaue Informationen oder Inhalte zu verhindern, die gegen Gesetze, Rechte Dritter, die öffentliche Moral oder ethische Standards verstoßen.</li>
            <li>Die Administration kann die Bereitstellung der Dienste einseitig ohne Angabe von Gründen einstellen.</li>
            <li>Die Administration ist nicht verantwortlich für Website-Inhalte, deren Richtigkeit, Verfügbarkeit, Unterbrechungen des Katalogs oder Folgen der Nutzung veröffentlichter Informationen.</li>
            <li>Die Administration kann Werbung platzieren und Benutzern Informationen im Zusammenhang mit der Website senden.</li>
        </ol>
    </li>
    <li>Rechte und Pflichten der Benutzer
        <ol>
            <li>Der Benutzer ist für die Richtigkeit der eingereichten Daten und die Rechtmäßigkeit der Website-Inhalte verantwortlich.</li>
            <li>Der Benutzer kann über das Feedbackformular Änderungen der Regeln vorschlagen.</li>
            <li>Der Benutzer kann Erläuterungen zu den Nutzungsbedingungen des Katalogdienstes anfordern.</li>
        </ol>
    </li>
    <li>Hinzufügen einer Website
        <ol>
            <li>Jeder Benutzer kann eine Website zur Aufnahme in den Katalog einreichen.</li>
            <li>Zum Einreichen einer Website muss der Benutzer die Website-Adresse, den Namen, die Kategorie, die E-Mail-Adresse und die Beschreibung angeben.</li>
            <li>Nach der Einreichung kann der Benutzer die übermittelten Daten nicht selbstständig bearbeiten.</li>
        </ol>
    </li>
    <li>Ablehnung des Dienstes
        <ol>
            <li>Eine Website kann abgelehnt werden, wenn sie gegen Gesetze, Rechte Dritter oder die öffentliche Moral verstößt.</li>
            <li>Eine Website kann wegen doppelter Inhalte, übermäßiger Werbung oder technischer Probleme, die eine normale Anzeige verhindern, abgelehnt werden.</li>
            <li>Die Administration kann die Veröffentlichung verweigern, wenn diese Regeln verletzt werden.</li>
        </ol>
    </li>
    <li>Benutzersupport
        <ol>
            <li>Support für Fragen zu catalog.janicky.com erfolgt über das Kontaktformular der Administration.</li>
            <li>Beschwerden im Zusammenhang mit der Nutzung des Katalogs werden über das Feedbackformular entgegengenommen.</li>
            <li>Anonyme oder unklare Beschwerden werden nicht geprüft.</li>
        </ol>
    </li>
</ol>
HTML,
    ],

    'link_status' => [
        'pending' => 'ausstehende Prüfung',
        'published' => 'veröffentlicht',
        'blocked' => 'gesperrt',
    ],

    'messages' => [
        'data_updated' => 'Daten aktualisiert',
        'data_deleted' => 'Daten gelöscht',
        'information_successfully_added' => 'Information erfolgreich hinzugefügt',
        'import_completed' => 'Import abgeschlossen. :count Links importiert',
        'import_failed' => 'Links konnten nicht importiert werden. Überprüfen Sie die Datei und versuchen Sie es erneut.',
        'import_success_title' => 'Import abgeschlossen',
        'import_error_title' => 'Importfehler',
    ],

    'datatable' => [
        'lengthMenu' => '_MENU_ Einträge anzeigen',
        'zeroRecords' => 'Nichts gefunden',
        'info' => 'Zeige _START_ bis _END_ von _TOTAL_ Einträgen',
        'infoEmpty' => 'Zeige 0 bis 0 von 0 Einträgen',
        'infoFiltered' => '(gefiltert aus _MAX_ Einträgen)',
        'search' => 'Suchen',
        'processing' => 'Wird geladen...',
        'paginate' => [
            'first' => 'Erste',
            'last' => 'Letzte',
            'next' => 'Weiter',
            'previous' => 'Zurück',
        ],
    ],

    'datatable_legacy' => [
        'sLengthMenu' => '_MENU_ Einträge pro Seite anzeigen',
        'sZeroRecords' => 'Nichts gefunden',
        'sInfo' => 'Zeige _START_ bis _END_ von _TOTAL_ Einträgen',
        'sInfoEmpty' => 'Zeige 0 bis 0 von 0 Einträgen',
        'sInfoFiltered' => '(gefiltert aus insgesamt _MAX_ Einträgen)',
        'oPaginate' => [
            'sFirst' => 'Erste',
            'sLast' => 'Letzte',
            'sNext' => 'Weiter',
            'sPrevious' => 'Zurück',
        ],
        'sSearch' => '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>',
    ],

    'confirm' => [
        'title' => 'Sind Sie sicher?',
        'text' => 'Sie können diese Informationen nicht wiederherstellen!',
        'confirm' => 'Ja',
        'cancel' => 'Abbrechen',
        'done_title' => 'Erledigt!',
        'delete_success' => 'Daten erfolgreich gelöscht!',
        'delete_error_title' => 'Löschfehler!',
        'delete_error_text' => 'Bitte versuchen Sie es erneut',
    ],

    'validation' => [
        'required' => 'Dieses Feld ist erforderlich!',
        'import_required' => 'Wählen Sie eine Importdatei oder ein ZIP-Archiv aus.',
        'import_mimes' => 'Erlaubte Dateitypen: csv,xlsx,xls,txt!',
        'import_archive_mimes' => 'Nur ZIP-Archive sind erlaubt!',
        'url' => 'Die URL-Adresse ist ungültig',
        'url_unique' => 'Eine Website mit dieser URL existiert bereits im Katalog!',
        'name_required' => 'Name ist erforderlich!',
        'frontend_url_required' => 'Website-URL ist erforderlich!',
        'frontend_url' => 'Die Website-URL ist ungültig!',
        'frontend_url_unique' => 'Diese Website existiert bereits im Katalog!',
        'email_required' => 'E-Mail ist erforderlich!',
        'email' => 'Die E-Mail-Adresse ist ungültig!',
        'description_required' => 'Beschreibung ist erforderlich!',
        'description_min' => 'Die Beschreibung muss mindestens :min Zeichen enthalten',
        'description_max' => 'Die Beschreibung darf höchstens :max Zeichen enthalten',
        'full_description_required' => 'Vollständige Beschreibung ist erforderlich!',
        'full_description_min' => 'Die vollständige Beschreibung muss mindestens :min Zeichen enthalten',
        'full_description_max' => 'Die vollständige Beschreibung darf höchstens :max Zeichen enthalten',
        'catalog_required' => 'Wählen Sie einen Abschnitt aus!',
        'captcha_required' => 'Sicherheitscode ist erforderlich!',
        'agree_required' => 'Sie müssen die Katalogregeln akzeptieren',
        'feedback_name_required' => 'Geben Sie Ihren Namen ein!',
        'feedback_message_required' => 'Geben Sie eine Nachricht ein',
    ],
];