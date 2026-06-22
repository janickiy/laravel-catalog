<?php

return [
    'language' => [
        'label' => 'Langue de l’interface',
        'ru' => 'Русский',
        'en' => 'English',
        'fr' => 'Français',
        'de' => 'Deutsch',
        'es' => 'Español',
    ],

    'common' => [
        'actions' => 'Actions',
        'add' => 'Ajouter',
        'add_subcategory' => 'Ajouter une sous-catégorie',
        'administrator' => 'Administrateur',
        'all' => 'Tout',
        'all_categories' => 'Toutes les catégories',
        'archive' => 'Archive',
        'author' => 'Auteur',
        'back' => 'Retour',
        'category' => 'Catégorie',
        'catalog' => 'Catalogue',
        'choose' => 'Choisir',
        'city' => 'Ville',
        'close' => 'Fermer',
        'contacts' => 'Contacts',
        'created_at' => 'Créé le',
        'date' => 'Date',
        'delete' => 'Supprimer',
        'description' => 'Description',
        'edit' => 'Modifier',
        'email' => 'E-mail',
        'export' => 'Exporter',
        'file_formats' => 'Formats',
        'full_description' => 'Description complète',
        'html_banner' => 'Code HTML de la bannière',
        'icon' => 'Icône',
        'id' => 'ID',
        'import' => 'Importer',
        'ip' => 'IP',
        'keywords' => 'Mots-clés',
        'login' => 'Identifiant',
        'max_size' => 'Taille maximale',
        'message' => 'Message',
        'misc' => 'Divers',
        'name' => 'Nom',
        'no_admins' => 'Aucun administrateur',
        'no_data' => 'Aucune donnée',
        'no_links' => 'Aucun lien',
        'no_messages' => 'Aucun message pour le moment',
        'optional' => 'Facultatif',
        'password' => 'Mot de passe',
        'password_repeat' => 'Répéter le mot de passe',
        'phone' => 'Téléphone',
        'required_fields' => '* champs obligatoires',
        'required_fields_frontend' => 'Les champs marqués d’un astérisque sont obligatoires.',
        'save' => 'Enregistrer',
        'site' => 'Site',
        'site_address' => 'Adresse du site',
        'status' => 'Statut',
        'submit' => 'Envoyer',
        'updated_at' => 'Mis à jour le',
        'url' => 'URL',
        'value' => 'Valeur',
        'view' => 'Voir',
        'views' => 'Vues',
    ],

    'auth' => [
        'page_title' => 'Connexion',
        'title' => 'Connexion au panneau d’administration',
        'login_placeholder' => 'Saisir l’identifiant',
        'password_placeholder' => 'Saisir le mot de passe',
        'remember' => 'Se souvenir de moi',
        'submit' => 'Se connecter',
    ],

    'admin' => [
        'panel' => 'Panneau de contrôle',
        'dashboard' => 'Tableau de bord',
        'categories' => 'Catégories',
        'feedback' => 'Messages du site',
        'administrators' => 'Administrateurs',
        'logout' => 'Se déconnecter',
        'breadcrumb_panel' => 'Panneau de contrôle',

        'updates' => [
            'title' => 'Mise à jour',
            'subtitle' => 'Vérifier et installer les nouvelles versions du projet',
            'current_version' => 'Version actuelle',
            'latest_version' => 'Version disponible',
            'released_at' => 'Date de publication',
            'available' => 'Une nouvelle version est disponible',
            'not_available' => 'Aucune mise à jour trouvée',
            'check_failed' => 'Vérification indisponible',
            'unknown' => 'Inconnu',
            'source' => 'Source des mises à jour',
            'source_description' => 'Les informations de version sont chargées depuis le serveur de licences Janickiy.',
            'open_source' => 'Ouvrir les informations',
            'release_notes' => 'Nouveautés',
            'run_to' => 'Mettre à jour vers :version',
            'up_to_date' => 'La version actuelle est installée',
            'processing' => 'Mise à jour en cours...',
            'info_text' => 'Le système vérifie le serveur de licences et installe le paquet de mise à jour disponible.',
            'completed' => 'Mise à jour terminée. La version :version est installée.',
            'failed' => 'Erreur de mise à jour : :message',
            'cannot_connect' => 'Impossible de se connecter au serveur de mises à jour.',
            'download_missing' => 'Le lien du paquet de mise à jour est indisponible.',
            'write_error' => 'Le répertoire du projet n’est pas accessible en écriture.',
            'archive_error' => 'Impossible de lire l’archive :file.',
            'unsafe_archive' => 'L’archive :file contient des chemins non sécurisés.',
            'no_update' => 'Vous avez déjà la dernière version :version.',
            'downloading' => 'Téléchargement de :file ...',
            'extracting' => 'Extraction de :file ...',
            'database' => 'Mise à jour de la base de données ...',
            'cache' => 'Nettoyage du cache ...',
            'download_completed' => 'Archive :file téléchargée',
            'extract_completed' => 'Archive :file extraite',
            'database_completed' => 'Migrations appliquées',
        ],

        'dashboard_cards' => [
            'total_links' => 'Total des liens',
            'categories' => 'Catégories',
            'messages' => 'Messages',
            'administrators' => 'Administrateurs',
            'settings' => 'Paramètres',
            'pending' => 'En attente de vérification',
        ],
        'dashboard_card_descriptions' => [
            'total_links' => 'Enregistrements du catalogue',
            'categories' => 'Sections et thèmes',
            'messages' => 'Demandes des utilisateurs',
            'administrators' => 'Utilisateurs du panneau',
            'settings' => 'Options du projet',
            'pending' => 'Nouvelles soumissions',
        ],
        'dashboard_sections' => [
            'link_statuses' => 'Statuts des liens',
            'quick_actions' => 'Actions rapides',
            'top_views' => 'Les plus consultés',
            'latest_links' => 'Derniers liens',
            'latest_messages' => 'Derniers messages',
            'administrators' => 'Administrateurs',
            'all_links' => 'Tous les liens',
            'open_section' => 'Ouvrir la section',
        ],
        'quick_actions' => [
            'add_link' => 'Ajouter un lien',
            'import_links' => 'Importer des liens',
            'export_links' => 'Exporter des liens',
            'add_category' => 'Ajouter une catégorie',
        ],

        'links' => [
            'title' => 'Liens',
            'subtitle' => 'Gérer les liens du catalogue',
            'create_title' => 'Ajouter un lien',
            'edit_title' => 'Modifier le lien',
            'show_title' => 'Voir le lien',
            'form_create_title' => 'Ajouter un lien',
            'form_edit_title' => 'Modifier le lien',
            'main_data' => 'Données principales',
            'contacts' => 'Contacts',
            'description_section' => 'Description',
            'state' => 'État',
            'existing' => 'L’enregistrement existe',
            'new' => 'Nouvel enregistrement',
            'will_publish_after_save' => 'Sera publié après l’enregistrement',
            'screenshot' => 'Capture d’écran du site',
            'screenshot_missing' => 'La capture d’écran n’a pas été téléchargée',
            'bulk_status' => 'Modification groupée du statut',
            'choose_status' => 'Choisir le statut',
            'apply' => 'Appliquer',
            'import_title' => 'Importation',
            'import_heading' => 'Importer des liens',
            'import_file' => 'Fichier d’importation',
            'import_submit' => 'Importer',
            'import_processing' => 'Importation...',
            'import_archive_heading' => 'Importer depuis une archive',
            'import_archive_file' => 'Archive ZIP pour l’importation',
            'import_archive_hint' => 'L’archive peut contenir des fichiers CSV, TXT, XLS ou XLSX.',
            'import_archive_submit' => 'Importer l’archive',
            'import_archive_processing' => 'Importation de l’archive...',
            'archive_format' => 'Format d’archive',
            'file_parameters' => 'Paramètres du fichier',
            'export_title' => 'Exportation',
            'export_heading' => 'Exporter les liens',
            'export_submit' => 'Exporter',
            'export_parameters' => 'Paramètres d’exportation',
            'file_format' => 'Format de fichier',
            'text' => 'Texte',
            'archive_type' => 'Archivage',
            'without_archive' => 'Sans archive',
            'regular_file' => 'Fichier normal',
        ],

        'catalog' => [
            'title' => 'Catégories',
            'create_title' => 'Ajouter une catégorie',
            'edit_title' => 'Modifier la catégorie',
            'form_create_title' => 'Ajouter une catégorie',
            'form_edit_title' => 'Modifier la catégorie',
            'main_data' => 'Données principales',
            'description_section' => 'Description',
            'icon' => 'Icône',
            'icon_file' => 'Fichier d’icône',
            'icon_hint' => 'JPG, GIF ou PNG. Taille maximale : :size.',
            'parent' => 'Section',
            'parent_category' => 'Section parente',
            'state' => 'État',
            'existing' => 'La catégorie existe',
            'new' => 'Nouvelle catégorie',
            'will_create_after_save' => 'Sera créée après l’enregistrement',
            'current_icon' => 'Icône actuelle',
            'icon_missing' => 'L’icône n’a pas été téléchargée',
        ],

        'admin_users' => [
            'title' => 'Administrateurs',
            'subtitle' => 'Gérer les utilisateurs du panneau de contrôle',
            'create_title' => 'Ajouter un administrateur',
            'edit_title' => 'Modifier l’administrateur',
        ],

        'feedback_messages' => [
            'subtitle' => 'Messages envoyés via le formulaire de contact',
            'show_title' => 'Voir le message',
            'message_text' => 'Texte du message',
        ],

        'settings' => [
            'title' => 'Paramètres',
            'subtitle' => 'Gérer les options du projet',
            'create_title' => 'Ajouter un paramètre',
            'edit_title' => 'Modifier le paramètre',
        ],
    ],

    'frontend' => [
        'site_title' => 'Catalogue blanc de sites web',
        'main_nav' => 'Navigation principale',
        'home' => 'Accueil',
        'rules' => 'Règles',
        'feedback' => 'Contact',
        'add_site' => 'Ajouter un site',
        'catalog' => 'Catalogue',
        'home_page' => 'Page d’accueil',
        'home_description' => 'Catalogue blanc de sites web',
        'home_keywords' => 'catalogue blanc de sites web, ajouter un site',
        'catalog_intro' => 'Choisissez une section et trouvez des sites pertinents dans un catalogue structuré.',
        'sites' => 'Sites',
        'recent_sites' => 'Sites récemment ajoutés',
        'more' => 'Plus',
        'no_site_image' => 'Aucune image du site',
        'footer_rules' => 'Règles du catalogue',

        'new_record' => 'Nouvel enregistrement',
        'add_site_title' => 'Ajouter un site',
        'add_site_description' => 'Ajouter un site',
        'add_site_keywords' => 'catalogue blanc de sites web, ajouter une URL, ajouter un site',
        'add_site_intro' => 'Remplissez les informations du site, choisissez une section et envoyez l’enregistrement pour modération.',
        'site_name' => 'Nom*',
        'site_name_placeholder' => 'Nom du site',
        'section' => 'Section*',
        'choose_section' => 'Choisir une section',
        'url_address' => 'URL du site*',
        'short_description' => 'Description*',
        'short_description_placeholder' => 'Brève description du site',
        'full_description' => 'Description complète*',
        'full_description_placeholder' => 'Description détaillée du site',
        'keywords_placeholder' => 'Mots-clés séparés par des virgules',
        'html_banner_placeholder' => 'Code HTML de la bannière',
        'captcha' => 'Code de sécurité*',
        'captcha_placeholder' => 'Saisissez le code de l’image',
        'agree_prefix' => 'J’accepte les',
        'agree_link' => 'règles de participation',
        'agree_suffix' => '',
        'add_submit' => 'Ajouter',
        'link_added' => 'Le site a été ajouté au catalogue',
        'link_added_pending' => 'Le site a été ajouté au catalogue et sera disponible après vérification',

        'contact_eyebrow' => 'Contact',
        'contact_title' => 'Contact',
        'contact_intro' => 'Envoyez un message à l’administration du catalogue via le formulaire de contact.',
        'your_name' => 'Votre nom*',
        'your_name_placeholder' => 'Votre nom',
        'message' => 'Message*',
        'message_placeholder' => 'Votre message',
        'message_sent' => 'Votre message a été envoyé avec succès',

        'rules_eyebrow' => 'Conditions',
        'rules_title' => 'Règles du catalogue de sites web',
        'site_card' => 'Fiche du site',
        'catalog_section' => 'Section du catalogue',
        'visits' => 'Visites',
        'more_in_category' => 'Plus dans cette section',
        'similar_sites' => 'Sites similaires',
        'rules_html' => <<<'HTML'
<p>Ces règles du catalogue s’appliquent avant l’ajout d’un site web et pendant l’utilisation du catalogue.</p>
<ol>
    <li>Conditions générales
        <ol>
            <li>catalog.janicky.com offre aux utilisateurs la possibilité de soumettre des sites web au catalogue.</li>
            <li>L’administration peut supprimer des sites ou modifier les données soumises afin d’éviter les informations inexactes ou les contenus qui enfreignent la loi, les droits de tiers, la morale publique ou les normes éthiques.</li>
            <li>L’administration peut cesser de fournir les services unilatéralement sans en indiquer la raison.</li>
            <li>L’administration n’est pas responsable du contenu des sites web, de leur exactitude, de leur disponibilité, des interruptions du catalogue ni des conséquences de l’utilisation des informations publiées.</li>
            <li>L’administration peut placer de la publicité et envoyer aux utilisateurs des informations liées au site web.</li>
        </ol>
    </li>
    <li>Droits et responsabilités des utilisateurs
        <ol>
            <li>L’utilisateur est responsable de l’exactitude des données soumises et de la légalité du contenu du site web.</li>
            <li>L’utilisateur peut proposer des modifications des règles via le formulaire de contact.</li>
            <li>L’utilisateur peut demander des éclaircissements concernant les conditions du service du catalogue.</li>
        </ol>
    </li>
    <li>Ajout d’un site
        <ol>
            <li>Tout utilisateur peut soumettre un site web au catalogue.</li>
            <li>Pour soumettre un site, l’utilisateur doit fournir l’adresse du site, le nom, la catégorie, l’e-mail et la description.</li>
            <li>Après la soumission, l’utilisateur ne peut pas modifier les données envoyées de manière autonome.</li>
        </ol>
    </li>
    <li>Refus de service
        <ol>
            <li>Un site web peut être refusé s’il enfreint la loi, les droits de tiers ou la morale publique.</li>
            <li>Un site web peut être refusé en cas de contenus dupliqués, de publicité excessive ou de problèmes techniques empêchant une consultation normale.</li>
            <li>L’administration peut refuser la publication lorsque ces règles sont enfreintes.</li>
        </ol>
    </li>
    <li>Assistance aux utilisateurs
        <ol>
            <li>L’assistance pour les questions liées à catalog.janicky.com est fournie via le formulaire de contact de l’administration.</li>
            <li>Les réclamations liées à l’utilisation du catalogue sont acceptées via le formulaire de contact.</li>
            <li>Les réclamations anonymes ou vagues ne sont pas examinées.</li>
        </ol>
    </li>
</ol>
HTML,
    ],

    'link_status' => [
        'pending' => 'en attente de vérification',
        'published' => 'publié',
        'blocked' => 'bloqué',
    ],

    'messages' => [
        'data_updated' => 'Données mises à jour',
        'data_deleted' => 'Données supprimées',
        'information_successfully_added' => 'Information ajoutée avec succès',
        'import_completed' => 'Importation terminée. :count liens importés',
        'import_failed' => 'Impossible d’importer les liens. Vérifiez le fichier et réessayez.',
        'import_success_title' => 'Importation terminée',
        'import_error_title' => 'Erreur d’importation',
    ],

    'datatable' => [
        'lengthMenu' => 'Afficher _MENU_ enregistrements',
        'zeroRecords' => 'Aucun résultat trouvé',
        'info' => 'Affichage de _START_ à _END_ sur _TOTAL_ enregistrements',
        'infoEmpty' => 'Affichage de 0 à 0 sur 0 enregistrements',
        'infoFiltered' => '(filtré à partir de _MAX_ enregistrements)',
        'search' => 'Rechercher',
        'processing' => 'Chargement...',
        'paginate' => [
            'first' => 'Premier',
            'last' => 'Dernier',
            'next' => 'Suivant',
            'previous' => 'Précédent',
        ],
    ],

    'datatable_legacy' => [
        'sLengthMenu' => 'Afficher _MENU_ enregistrements par page',
        'sZeroRecords' => 'Aucun résultat trouvé',
        'sInfo' => 'Affichage de _START_ à _END_ sur _TOTAL_ enregistrements',
        'sInfoEmpty' => 'Affichage de 0 à 0 sur 0 enregistrements',
        'sInfoFiltered' => '(filtré à partir de _MAX_ enregistrements au total)',
        'oPaginate' => [
            'sFirst' => 'Premier',
            'sLast' => 'Dernier',
            'sNext' => 'Suivant',
            'sPrevious' => 'Précédent',
        ],
        'sSearch' => '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>',
    ],

    'confirm' => [
        'title' => 'Êtes-vous sûr ?',
        'text' => 'Vous ne pourrez pas restaurer ces informations !',
        'confirm' => 'Oui',
        'cancel' => 'Annuler',
        'done_title' => 'Terminé !',
        'delete_success' => 'Données supprimées avec succès !',
        'delete_error_title' => 'Erreur de suppression !',
        'delete_error_text' => 'Veuillez réessayer',
    ],

    'validation' => [
        'required' => 'Ce champ est obligatoire !',
        'import_required' => 'Choisissez un fichier d’importation ou une archive ZIP.',
        'import_mimes' => 'Types de fichiers autorisés : csv,xlsx,xls,txt !',
        'import_archive_mimes' => 'Seules les archives ZIP sont autorisées !',
        'url' => 'L’adresse URL est invalide',
        'url_unique' => 'Un site avec cette URL existe déjà dans le catalogue !',
        'name_required' => 'Le nom est obligatoire !',
        'frontend_url_required' => 'L’URL du site est obligatoire !',
        'frontend_url' => 'L’URL du site est invalide !',
        'frontend_url_unique' => 'Ce site existe déjà dans le catalogue !',
        'email_required' => 'L’e-mail est obligatoire !',
        'email' => 'L’adresse e-mail est invalide !',
        'description_required' => 'La description est obligatoire !',
        'description_min' => 'La description doit contenir au moins :min caractères',
        'description_max' => 'La description ne doit pas dépasser :max caractères',
        'full_description_required' => 'La description complète est obligatoire !',
        'full_description_min' => 'La description complète doit contenir au moins :min caractères',
        'full_description_max' => 'La description complète ne doit pas dépasser :max caractères',
        'catalog_required' => 'Choisissez une section !',
        'captcha_required' => 'Le code de sécurité est obligatoire !',
        'agree_required' => 'Vous devez accepter les règles du catalogue',
        'feedback_name_required' => 'Saisissez votre nom !',
        'feedback_message_required' => 'Saisissez un message',
    ],
];
