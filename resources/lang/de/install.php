<?php

return [
    'button' => [
        'back' => 'Zurück',
        'install' => 'Installieren',
        'next' => 'Weiter',
    ],

    'hint' => [
        'database_host' => 'Verwenden Sie den MySQL-Servicenamen in Docker; für einen lokalen MySQL-Server wird häufig 127.0.0.1 verwendet.',
        'database_name' => 'Die Datenbank, auf die die Projektmigrationen angewendet werden.',
        'database_password' => 'Leer lassen, wenn der Datenbankbenutzer kein Passwort hat.',
        'database_port' => 'Standard-MySQL-Port innerhalb von Docker: 3306.',
        'database_username' => 'Der Benutzer muss berechtigt sein, Tabellen zu erstellen und zu ändern.',
    ],

    'license' => 'Stellen Sie vor der Installation sicher, dass Sie berechtigt sind, My Links Manager zu verwenden, Zugriff auf die Datenbank haben und die .env-Datei bearbeiten können. Das Installationsprogramm führt die Migrationen aus, erstellt den ersten Administrator und markiert die Anwendung als installiert.',

    'str' => [
        'access_rights' => 'Zugriffsrechte',
        'administration' => 'Administrator',
        'app_is_successfully_installed' => 'Das Projekt wurde erfolgreich installiert. Sie können sich nun im Administrationsbereich anmelden.',
        'complete' => 'Abschließen',
        'confirm_password' => 'Passwort bestätigen',
        'connection_to_database_cannot_be_established' => 'Es konnte keine Verbindung zur Datenbank hergestellt werden. Bitte überprüfen Sie die eingegebenen Einstellungen.',
        'database_host' => 'Datenbank-Host',
        'database_info' => 'Datenbank',
        'database_information' => 'Datenbankverbindungseinstellungen',
        'database_name' => 'Datenbankname',
        'database_port' => 'Port',
        'database_username' => 'Datenbank-Benutzername',
        'error_text' => 'Während der Installation ist ein Fehler aufgetreten. Überprüfen Sie die Verbindungsdaten, Schreibberechtigungen und die Anwendungsprotokolle.',
        'error_title' => 'Die Installation wurde nicht abgeschlossen',
        'important' => 'Wichtig',
        'install' => 'Installieren',
        'installation' => 'Installationsassistent',
        'license_agreement' => 'Lizenzvereinbarung',
        'log_in' => 'Im Administrationsbereich anmelden',
        'login' => 'Benutzername',
        'password' => 'Passwort',
        'permissions' => 'Berechtigungen',
        'ready_to_install' => 'Alles ist bereit. Erstellen Sie den ersten Administrator.',
        'requirements_failed_text' => 'Installieren Sie die fehlenden PHP-Erweiterungen oder aktualisieren Sie PHP und laden Sie diese Seite anschließend neu.',
        'requirements_failed_title' => 'Einige Anforderungen werden nicht erfüllt',
        'select_language' => 'Sprache',
        'system_requirements' => 'Systemanforderungen',
        'try_again' => 'Erneut versuchen',
        'welcome' => 'Willkommen beim Installationsassistenten von My Links Manager.',
        'well_done' => 'Gut gemacht',
    ],
];