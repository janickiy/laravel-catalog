<?php

return [
    'button' => [
        'back' => 'Back',
        'install' => 'Install',
        'next' => 'Next',
    ],

    'hint' => [
        'database_host' => 'Use the mysql service name in Docker; 127.0.0.1 is common for a local MySQL server.',
        'database_name' => 'The database where project migrations will be applied.',
        'database_password' => 'Leave empty if the database user has no password.',
        'database_port' => 'Default MySQL port inside Docker: 3306.',
        'database_username' => 'The user must be allowed to create and modify tables.',
    ],

    'license' => 'Before installing, make sure you are allowed to use My Links Manager, can access the database, and can modify the .env file. The installer will apply migrations, create the first administrator, and mark the application as installed.',

    'str' => [
        'access_rights' => 'Access rights',
        'administration' => 'Administrator',
        'app_is_successfully_installed' => 'The project has been installed successfully. You can now sign in to the admin panel.',
        'complete' => 'Complete',
        'confirm_password' => 'Confirm password',
        'connection_to_database_cannot_be_established' => 'Could not connect to the database. Please check the entered settings.',
        'database_host' => 'Database host',
        'database_info' => 'Database',
        'database_information' => 'Database connection settings',
        'database_name' => 'Database name',
        'database_port' => 'Port',
        'database_username' => 'Database username',
        'error_text' => 'An error occurred during installation. Check the connection details, write permissions, and application logs.',
        'error_title' => 'Installation was not completed',
        'important' => 'Important',
        'install' => 'Install',
        'installation' => 'Installation wizard',
        'license_agreement' => 'License agreement',
        'log_in' => 'Log in to admin panel',
        'login' => 'Login',
        'password' => 'Password',
        'permissions' => 'Permissions',
        'ready_to_install' => 'Everything is ready. Create the first administrator.',
        'requirements_failed_text' => 'Install the missing PHP extensions or update PHP, then refresh this page.',
        'requirements_failed_title' => 'Some requirements are missing',
        'select_language' => 'Language',
        'system_requirements' => 'System requirements',
        'try_again' => 'Try again',
        'welcome' => 'Welcome to the My Links Manager installation wizard.',
        'well_done' => 'Well done',
    ],
];
