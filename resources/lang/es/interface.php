<?php

return [
    'language' => [
        'label' => 'Idioma de la interfaz',
        'ru' => 'Русский',
        'en' => 'English',
        'fr' => 'Français',
        'de' => 'Deutsch',
        'es' => 'Español',
    ],

    'common' => [
        'actions' => 'Acciones',
        'add' => 'Añadir',
        'add_subcategory' => 'Añadir subcategoría',
        'administrator' => 'Administrador',
        'all' => 'Todo',
        'all_categories' => 'Todas las categorías',
        'archive' => 'Archivo',
        'author' => 'Autor',
        'back' => 'Atrás',
        'category' => 'Categoría',
        'catalog' => 'Catálogo',
        'choose' => 'Elegir',
        'city' => 'Ciudad',
        'close' => 'Cerrar',
        'contacts' => 'Contactos',
        'created_at' => 'Creado el',
        'date' => 'Fecha',
        'delete' => 'Eliminar',
        'description' => 'Descripción',
        'edit' => 'Editar',
        'email' => 'Correo electrónico',
        'export' => 'Exportar',
        'file_formats' => 'Formatos',
        'full_description' => 'Descripción completa',
        'html_banner' => 'Código HTML del banner',
        'icon' => 'Icono',
        'id' => 'ID',
        'import' => 'Importar',
        'ip' => 'IP',
        'keywords' => 'Palabras clave',
        'login' => 'Usuario',
        'max_size' => 'Tamaño máximo',
        'message' => 'Mensaje',
        'misc' => 'Varios',
        'name' => 'Nombre',
        'no_admins' => 'No hay administradores',
        'no_data' => 'No hay datos',
        'no_links' => 'No hay enlaces',
        'no_messages' => 'Aún no hay mensajes',
        'optional' => 'Opcional',
        'password' => 'Contraseña',
        'password_repeat' => 'Repetir contraseña',
        'phone' => 'Teléfono',
        'required_fields' => '* campos obligatorios',
        'required_fields_frontend' => 'Los campos marcados con un asterisco son obligatorios.',
        'save' => 'Guardar',
        'site' => 'Sitio',
        'site_address' => 'Dirección del sitio',
        'status' => 'Estado',
        'submit' => 'Enviar',
        'updated_at' => 'Actualizado el',
        'url' => 'URL',
        'value' => 'Valor',
        'view' => 'Ver',
        'views' => 'Vistas',
    ],

    'auth' => [
        'page_title' => 'Inicio de sesión',
        'title' => 'Inicio de sesión del panel de administración',
        'login_placeholder' => 'Introduzca el usuario',
        'password_placeholder' => 'Introduzca la contraseña',
        'remember' => 'Recordarme',
        'submit' => 'Iniciar sesión',
    ],

    'admin' => [
        'panel' => 'Panel de control',
        'dashboard' => 'Panel principal',
        'categories' => 'Categorías',
        'feedback' => 'Mensajes del sitio web',
        'administrators' => 'Administradores',
        'logout' => 'Cerrar sesión',
        'breadcrumb_panel' => 'Panel de control',

        'updates' => [
            'title' => 'Actualización',
            'subtitle' => 'Comprobar e instalar nuevas versiones del proyecto',
            'current_version' => 'Versión actual',
            'latest_version' => 'Versión disponible',
            'released_at' => 'Fecha de lanzamiento',
            'available' => 'Hay una nueva versión disponible',
            'not_available' => 'No se encontraron actualizaciones',
            'check_failed' => 'Comprobación no disponible',
            'unknown' => 'Desconocido',
            'source' => 'Fuente de actualizaciones',
            'source_description' => 'La información de versión se carga desde el servidor de licencias Janickiy.',
            'open_source' => 'Abrir información',
            'release_notes' => 'Novedades',
            'run_to' => 'Actualizar a :version',
            'up_to_date' => 'La versión actual está instalada',
            'processing' => 'Actualizando...',
            'info_text' => 'El sistema comprueba el servidor de licencias e instala el paquete de actualización disponible.',
            'completed' => 'Actualización completada. La versión :version está instalada.',
            'failed' => 'Error de actualización: :message',
            'cannot_connect' => 'No se pudo conectar al servidor de actualizaciones.',
            'download_missing' => 'El enlace del paquete de actualización no está disponible.',
            'write_error' => 'El directorio del proyecto no tiene permisos de escritura.',
            'archive_error' => 'No se pudo leer el archivo :file.',
            'unsafe_archive' => 'El archivo :file contiene rutas no seguras.',
            'no_update' => 'Ya tiene la última versión :version.',
            'downloading' => 'Descargando :file ...',
            'extracting' => 'Extrayendo :file ...',
            'database' => 'Actualizando la base de datos ...',
            'cache' => 'Limpiando caché ...',
            'download_completed' => 'Archivo :file descargado',
            'extract_completed' => 'Archivo :file extraído',
            'database_completed' => 'Migraciones aplicadas',
        ],

        'dashboard_cards' => [
            'total_links' => 'Enlaces totales',
            'categories' => 'Categorías',
            'messages' => 'Mensajes',
            'administrators' => 'Administradores',
            'settings' => 'Configuración',
            'pending' => 'Pendiente de revisión',
        ],
        'dashboard_card_descriptions' => [
            'total_links' => 'Registros del catálogo',
            'categories' => 'Secciones y temas',
            'messages' => 'Solicitudes de usuarios',
            'administrators' => 'Usuarios del panel',
            'settings' => 'Opciones del proyecto',
            'pending' => 'Nuevos envíos',
        ],
        'dashboard_sections' => [
            'link_statuses' => 'Estados de los enlaces',
            'quick_actions' => 'Acciones rápidas',
            'top_views' => 'Más vistos',
            'latest_links' => 'Últimos enlaces',
            'latest_messages' => 'Últimos mensajes',
            'administrators' => 'Administradores',
            'all_links' => 'Todos los enlaces',
            'open_section' => 'Abrir sección',
        ],
        'quick_actions' => [
            'add_link' => 'Añadir enlace',
            'import_links' => 'Importar enlaces',
            'export_links' => 'Exportar enlaces',
            'add_category' => 'Añadir categoría',
        ],

        'links' => [
            'title' => 'Enlaces',
            'subtitle' => 'Gestionar enlaces del catálogo',
            'create_title' => 'Añadir enlace',
            'edit_title' => 'Editar enlace',
            'show_title' => 'Ver enlace',
            'form_create_title' => 'Añadir enlace',
            'form_edit_title' => 'Editar enlace',
            'main_data' => 'Datos principales',
            'contacts' => 'Contactos',
            'description_section' => 'Descripción',
            'state' => 'Estado',
            'existing' => 'El registro existe',
            'new' => 'Nuevo registro',
            'will_publish_after_save' => 'Se publicará después de guardar',
            'screenshot' => 'Captura de pantalla del sitio',
            'screenshot_missing' => 'La captura de pantalla no se ha subido',
            'bulk_status' => 'Cambio masivo de estado',
            'choose_status' => 'Elegir estado',
            'apply' => 'Aplicar',
            'import_title' => 'Importar',
            'import_heading' => 'Importar enlaces',
            'import_file' => 'Archivo de importación',
            'import_submit' => 'Importar',
            'import_processing' => 'Importando...',
            'import_archive_heading' => 'Importar desde archivo',
            'import_archive_file' => 'Archivo ZIP para importar',
            'import_archive_hint' => 'El archivo puede contener archivos CSV, TXT, XLS o XLSX.',
            'import_archive_submit' => 'Importar archivo',
            'import_archive_processing' => 'Importando archivo...',
            'archive_format' => 'Formato de archivo',
            'file_parameters' => 'Parámetros del archivo',
            'export_title' => 'Exportar',
            'export_heading' => 'Exportar enlaces',
            'export_submit' => 'Exportar',
            'export_parameters' => 'Parámetros de exportación',
            'file_format' => 'Formato de archivo',
            'text' => 'Texto',
            'archive_type' => 'Archivado',
            'without_archive' => 'Sin archivo',
            'regular_file' => 'Archivo normal',
        ],

        'catalog' => [
            'title' => 'Categorías',
            'create_title' => 'Añadir categoría',
            'edit_title' => 'Editar categoría',
            'form_create_title' => 'Añadir categoría',
            'form_edit_title' => 'Editar categoría',
            'main_data' => 'Datos principales',
            'description_section' => 'Descripción',
            'icon' => 'Icono',
            'icon_file' => 'Archivo de icono',
            'icon_hint' => 'JPG, GIF o PNG. Tamaño máximo: :size.',
            'parent' => 'Sección',
            'parent_category' => 'Sección principal',
            'state' => 'Estado',
            'existing' => 'La categoría existe',
            'new' => 'Nueva categoría',
            'will_create_after_save' => 'Se creará después de guardar',
            'current_icon' => 'Icono actual',
            'icon_missing' => 'El icono no se ha subido',
        ],

        'admin_users' => [
            'title' => 'Administradores',
            'subtitle' => 'Gestionar usuarios del panel de control',
            'create_title' => 'Añadir administrador',
            'edit_title' => 'Editar administrador',
        ],

        'feedback_messages' => [
            'subtitle' => 'Mensajes enviados a través del formulario de feedback',
            'show_title' => 'Ver mensaje',
            'message_text' => 'Texto del mensaje',
        ],

        'settings' => [
            'title' => 'Configuración',
            'subtitle' => 'Gestionar opciones del proyecto',
            'create_title' => 'Añadir configuración',
            'edit_title' => 'Editar configuración',
        ],
    ],

    'frontend' => [
        'site_title' => 'Catálogo blanco de sitios web',
        'main_nav' => 'Navegación principal',
        'home' => 'Inicio',
        'rules' => 'Reglas',
        'feedback' => 'Feedback',
        'add_site' => 'Añadir sitio',
        'catalog' => 'Catálogo',
        'home_page' => 'Página de inicio',
        'home_description' => 'Catálogo blanco de sitios web',
        'home_keywords' => 'catálogo blanco de sitios web, añadir sitio',
        'catalog_intro' => 'Elija una sección y encuentre sitios relevantes en un catálogo estructurado.',
        'sites' => 'Sitios',
        'recent_sites' => 'Sitios añadidos recientemente',
        'more' => 'Más',
        'no_site_image' => 'Sin imagen del sitio',
        'footer_rules' => 'Reglas del catálogo',

        'new_record' => 'Nuevo registro',
        'add_site_title' => 'Añadir sitio',
        'add_site_description' => 'Añadir sitio',
        'add_site_keywords' => 'catálogo blanco de sitios web, añadir url, añadir sitio',
        'add_site_intro' => 'Complete los datos del sitio, elija una sección y envíe el registro para moderación.',
        'site_name' => 'Nombre*',
        'site_name_placeholder' => 'Nombre del sitio',
        'section' => 'Sección*',
        'choose_section' => 'Elegir sección',
        'url_address' => 'URL del sitio*',
        'short_description' => 'Descripción*',
        'short_description_placeholder' => 'Descripción breve del sitio',
        'full_description' => 'Descripción completa*',
        'full_description_placeholder' => 'Descripción detallada del sitio',
        'keywords_placeholder' => 'Palabras clave separadas por comas',
        'html_banner_placeholder' => 'Código HTML del banner',
        'captcha' => 'Código de seguridad*',
        'captcha_placeholder' => 'Introduzca el código de la imagen',
        'agree_prefix' => 'Acepto las',
        'agree_link' => 'reglas de participación',
        'agree_suffix' => '',
        'add_submit' => 'Añadir',
        'link_added' => 'El sitio ha sido añadido al catálogo',
        'link_added_pending' => 'El sitio ha sido añadido al catálogo y estará disponible después de la revisión',

        'contact_eyebrow' => 'Contacto',
        'contact_title' => 'Feedback',
        'contact_intro' => 'Envíe un mensaje a la administración del catálogo a través del formulario de feedback.',
        'your_name' => 'Su nombre*',
        'your_name_placeholder' => 'Su nombre',
        'message' => 'Mensaje*',
        'message_placeholder' => 'Su mensaje',
        'message_sent' => 'Su mensaje se ha enviado correctamente',

        'rules_eyebrow' => 'Condiciones',
        'rules_title' => 'Reglas del catálogo de sitios web',
        'site_card' => 'Tarjeta del sitio',
        'catalog_section' => 'Sección del catálogo',
        'visits' => 'Visitas',
        'more_in_category' => 'Más en esta sección',
        'similar_sites' => 'Sitios similares',
        'rules_html' => <<<'HTML'
<p>Estas reglas del catálogo se aplican antes de añadir un sitio web y durante el uso del catálogo.</p>
<ol>
    <li>Condiciones generales
        <ol>
            <li>catalog.janicky.com ofrece a los usuarios la posibilidad de enviar sitios web al catálogo.</li>
            <li>La administración puede eliminar sitios o editar los datos enviados para evitar información inexacta o contenido que infrinja la ley, derechos de terceros, la moral pública o normas éticas.</li>
            <li>La administración puede dejar de prestar servicios unilateralmente sin indicar el motivo.</li>
            <li>La administración no se hace responsable del contenido de los sitios web, su exactitud, disponibilidad, interrupciones del catálogo ni consecuencias del uso de la información publicada.</li>
            <li>La administración puede colocar publicidad y enviar a los usuarios información relacionada con el sitio web.</li>
        </ol>
    </li>
    <li>Derechos y responsabilidades del usuario
        <ol>
            <li>El usuario es responsable de la exactitud de los datos enviados y de la legalidad del contenido del sitio web.</li>
            <li>El usuario puede proponer cambios en las reglas a través del formulario de feedback.</li>
            <li>El usuario puede solicitar aclaraciones sobre las condiciones del servicio del catálogo.</li>
        </ol>
    </li>
    <li>Añadir un sitio
        <ol>
            <li>Cualquier usuario puede enviar un sitio web al catálogo.</li>
            <li>Para enviar un sitio, el usuario debe proporcionar la dirección del sitio, el nombre, la categoría, el correo electrónico y la descripción.</li>
            <li>Después del envío, el usuario no puede editar los datos enviados por sí mismo.</li>
        </ol>
    </li>
    <li>Rechazo del servicio
        <ol>
            <li>Un sitio web puede ser rechazado si infringe la ley, derechos de terceros o la moral pública.</li>
            <li>Un sitio web puede ser rechazado por materiales duplicados, publicidad excesiva o problemas técnicos que impidan su visualización normal.</li>
            <li>La administración puede rechazar la publicación cuando se infrinjan estas reglas.</li>
        </ol>
    </li>
    <li>Soporte al usuario
        <ol>
            <li>El soporte para preguntas relacionadas con catalog.janicky.com se proporciona a través del formulario de contacto de la administración.</li>
            <li>Las reclamaciones relacionadas con el uso del catálogo se aceptan a través del formulario de feedback.</li>
            <li>Las reclamaciones anónimas o poco claras no se revisan.</li>
        </ol>
    </li>
</ol>
HTML,
    ],

    'link_status' => [
        'pending' => 'pendiente de revisión',
        'published' => 'publicado',
        'blocked' => 'bloqueado',
    ],

    'messages' => [
        'data_updated' => 'Datos actualizados',
        'data_deleted' => 'Datos eliminados',
        'information_successfully_added' => 'Información añadida correctamente',
        'import_completed' => 'Importación completada. Enlaces importados: :count',
        'import_failed' => 'No se pudieron importar los enlaces. Compruebe el archivo e inténtelo de nuevo.',
        'import_success_title' => 'Importación completada',
        'import_error_title' => 'Error de importación',
    ],

    'datatable' => [
        'lengthMenu' => 'Mostrar _MENU_ registros',
        'zeroRecords' => 'No se encontró nada',
        'info' => 'Mostrando _START_ a _END_ de _TOTAL_ registros',
        'infoEmpty' => 'Mostrando 0 a 0 de 0 registros',
        'infoFiltered' => '(filtrado de _MAX_ registros)',
        'search' => 'Buscar',
        'processing' => 'Cargando...',
        'paginate' => [
            'first' => 'Primero',
            'last' => 'Último',
            'next' => 'Siguiente',
            'previous' => 'Anterior',
        ],
    ],

    'datatable_legacy' => [
        'sLengthMenu' => 'Mostrar _MENU_ registros por página',
        'sZeroRecords' => 'No se encontró nada',
        'sInfo' => 'Mostrando _START_ a _END_ de _TOTAL_ registros',
        'sInfoEmpty' => 'Mostrando 0 a 0 de 0 registros',
        'sInfoFiltered' => '(filtrado de _MAX_ registros en total)',
        'oPaginate' => [
            'sFirst' => 'Primero',
            'sLast' => 'Último',
            'sNext' => 'Siguiente',
            'sPrevious' => 'Anterior',
        ],
        'sSearch' => '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>',
    ],

    'confirm' => [
        'title' => '¿Está seguro?',
        'text' => '¡No podrá restaurar esta información!',
        'confirm' => 'Sí',
        'cancel' => 'Cancelar',
        'done_title' => '¡Hecho!',
        'delete_success' => '¡Datos eliminados correctamente!',
        'delete_error_title' => '¡Error al eliminar!',
        'delete_error_text' => 'Por favor, inténtelo de nuevo',
    ],

    'validation' => [
        'required' => '¡Este campo es obligatorio!',
        'import_required' => 'Elija un archivo de importación o un archivo ZIP.',
        'import_mimes' => 'Tipos de archivo permitidos: csv,xlsx,xls,txt.',
        'import_archive_mimes' => '¡Solo se permiten archivos ZIP!',
        'url' => 'La dirección URL no es válida',
        'url_unique' => '¡Ya existe un sitio con esta URL en el catálogo!',
        'name_required' => '¡El nombre es obligatorio!',
        'frontend_url_required' => '¡La URL del sitio es obligatoria!',
        'frontend_url' => '¡La URL del sitio no es válida!',
        'frontend_url_unique' => '¡Este sitio ya existe en el catálogo!',
        'email_required' => '¡El correo electrónico es obligatorio!',
        'email' => '¡La dirección de correo electrónico no es válida!',
        'description_required' => '¡La descripción es obligatoria!',
        'description_min' => 'La descripción debe contener al menos :min caracteres',
        'description_max' => 'La descripción no debe superar :max caracteres',
        'full_description_required' => '¡La descripción completa es obligatoria!',
        'full_description_min' => 'La descripción completa debe contener al menos :min caracteres',
        'full_description_max' => 'La descripción completa no debe superar :max caracteres',
        'catalog_required' => '¡Elija una sección!',
        'captcha_required' => '¡El código de seguridad es obligatorio!',
        'agree_required' => 'Debe aceptar las reglas del catálogo',
        'feedback_name_required' => '¡Introduzca su nombre!',
        'feedback_message_required' => 'Introduzca un mensaje',
    ],
];
