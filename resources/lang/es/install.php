<?php

return [
    'button' => [
        'back' => 'Atrás',
        'install' => 'Instalar',
        'next' => 'Siguiente',
    ],

    'hint' => [
        'database_host' => 'Utilice el nombre del servicio MySQL en Docker; 127.0.0.1 es habitual para un servidor MySQL local.',
        'database_name' => 'La base de datos donde se aplicarán las migraciones del proyecto.',
        'database_password' => 'Déjelo vacío si el usuario de la base de datos no tiene contraseña.',
        'database_port' => 'Puerto MySQL predeterminado dentro de Docker: 3306.',
        'database_username' => 'El usuario debe tener permisos para crear y modificar tablas.',
    ],

    'license' => 'Antes de instalar, asegúrese de que tiene autorización para utilizar My Links Manager, acceso a la base de datos y permisos para modificar el archivo .env. El instalador ejecutará las migraciones, creará el primer administrador y marcará la aplicación como instalada.',

    'str' => [
        'access_rights' => 'Derechos de acceso',
        'administration' => 'Administrador',
        'app_is_successfully_installed' => 'El proyecto se ha instalado correctamente. Ahora puede iniciar sesión en el panel de administración.',
        'complete' => 'Finalizar',
        'confirm_password' => 'Confirmar contraseña',
        'connection_to_database_cannot_be_established' => 'No se pudo establecer conexión con la base de datos. Compruebe la configuración introducida.',
        'database_host' => 'Servidor de base de datos',
        'database_info' => 'Base de datos',
        'database_information' => 'Configuración de conexión a la base de datos',
        'database_name' => 'Nombre de la base de datos',
        'database_port' => 'Puerto',
        'database_username' => 'Usuario de la base de datos',
        'error_text' => 'Se produjo un error durante la instalación. Compruebe los datos de conexión, los permisos de escritura y los registros de la aplicación.',
        'error_title' => 'La instalación no se completó',
        'important' => 'Importante',
        'install' => 'Instalar',
        'installation' => 'Asistente de instalación',
        'license_agreement' => 'Acuerdo de licencia',
        'log_in' => 'Iniciar sesión en el panel de administración',
        'login' => 'Usuario',
        'password' => 'Contraseña',
        'permissions' => 'Permisos',
        'ready_to_install' => 'Todo está listo. Cree el primer administrador.',
        'requirements_failed_text' => 'Instale las extensiones PHP que faltan o actualice PHP y, a continuación, recargue esta página.',
        'requirements_failed_title' => 'Faltan algunos requisitos',
        'select_language' => 'Idioma',
        'system_requirements' => 'Requisitos del sistema',
        'try_again' => 'Intentar de nuevo',
        'welcome' => 'Bienvenido al asistente de instalación de My Links Manager.',
        'well_done' => 'Bien hecho',
    ],
];