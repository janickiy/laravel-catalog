<?php

return [
    'button' => [
        'back' => 'Retour',
        'install' => 'Installer',
        'next' => 'Suivant',
    ],

    'hint' => [
        'database_host' => 'Utilisez le nom du service MySQL dans Docker ; 127.0.0.1 est couramment utilisé pour un serveur MySQL local.',
        'database_name' => 'La base de données dans laquelle les migrations du projet seront appliquées.',
        'database_password' => 'Laissez vide si l’utilisateur de la base de données n’a pas de mot de passe.',
        'database_port' => 'Port MySQL par défaut dans Docker : 3306.',
        'database_username' => 'L’utilisateur doit être autorisé à créer et modifier des tables.',
    ],

    'license' => 'Avant l’installation, assurez-vous que vous êtes autorisé à utiliser My Links Manager, que vous avez accès à la base de données et que vous pouvez modifier le fichier .env. L’installateur exécutera les migrations, créera le premier administrateur et marquera l’application comme installée.',

    'str' => [
        'access_rights' => 'Droits d’accès',
        'administration' => 'Administrateur',
        'app_is_successfully_installed' => 'Le projet a été installé avec succès. Vous pouvez maintenant vous connecter au panneau d’administration.',
        'complete' => 'Terminer',
        'confirm_password' => 'Confirmer le mot de passe',
        'connection_to_database_cannot_be_established' => 'Impossible de se connecter à la base de données. Veuillez vérifier les paramètres saisis.',
        'database_host' => 'Hôte de la base de données',
        'database_info' => 'Base de données',
        'database_information' => 'Paramètres de connexion à la base de données',
        'database_name' => 'Nom de la base de données',
        'database_port' => 'Port',
        'database_username' => 'Nom d’utilisateur de la base de données',
        'error_text' => 'Une erreur est survenue pendant l’installation. Vérifiez les informations de connexion, les droits d’écriture et les journaux de l’application.',
        'error_title' => 'L’installation n’a pas été terminée',
        'important' => 'Important',
        'install' => 'Installer',
        'installation' => 'Assistant d’installation',
        'license_agreement' => 'Contrat de licence',
        'log_in' => 'Se connecter au panneau d’administration',
        'login' => 'Identifiant',
        'password' => 'Mot de passe',
        'permissions' => 'Autorisations',
        'ready_to_install' => 'Tout est prêt. Créez le premier administrateur.',
        'requirements_failed_text' => 'Installez les extensions PHP manquantes ou mettez PHP à jour, puis actualisez cette page.',
        'requirements_failed_title' => 'Certaines exigences ne sont pas satisfaites',
        'select_language' => 'Langue',
        'system_requirements' => 'Configuration requise',
        'try_again' => 'Réessayer',
        'welcome' => 'Bienvenue dans l’assistant d’installation de My Links Manager.',
        'well_done' => 'Bravo',
    ],
];