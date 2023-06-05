<?php
/*
Plugin Name: HA Plugin
Plugin URI: URL del sitio web del plugin
Description: Conecta los datos de tu agencia en HeyAndes con tu sitio de Wordpress
Version: 1.0.0
Author: Geovanni Curguan
Author URI: URL de tu sitio web o empresa
License: Licencia del plugin
*/

include_once(plugin_dir_path(__FILE__) . './shortcodes/shortcodes.php');
shotcodes_register();

function experiencies_management_main(){
	if (isset($_POST['save_agency'])) {
		// Guardar el valor del input en la opción 'dato_guardado'
		update_option('key_agency', sanitize_text_field($_POST['agency_key']));
		echo '<div class="updated"><p>Valor guardado correctamente.</p></div>';
	}
    global $wpdb;
    $table_name = $wpdb->prefix . 'agency_experiences_data';
    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'");
    //Creamos la tabla para almacenar la data para los shortcodes
    if ($table_exists !== $table_name) {
        $query = "CREATE TABLE $table_name (
            meta_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            experience_key VARCHAR(255) NOT NULL,
            meta_key VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
            meta_value LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
            PRIMARY KEY (meta_id)
        ) ENGINE = InnoDB;";
        $wpdb->query($query);
    }

	$agency_key = get_option('key_agency');
	if($agency_key){
		//Mejorar a que esta información sólo se guarde cuando se conecta con la key, y no cada vez que ingresa al dashboard
	    
        $url = "https://firestore.googleapis.com/v1/projects/heyandesbooker-88007/databases/(default)/documents/agency/" . $agency_key . "/experiences";
        $json_data = file_get_contents($url);
		$data = json_decode($json_data);
		if(isset($data)){
			echo "Agencia conectada Correctamente <br>";
			foreach ($data->documents as $document) {
				//Acá recolectamos la información de las experiencias para subirla a la base de datos de Wordpress
				$fields = $document->fields;
				$condition = ($fields->key->stringValue != "string") &&
                (isset($fields->isDisable->booleanValue) ? ($fields->isDisable->booleanValue === false) : true) &&
                (isset($fields->isActive->booleanValue) ? ($fields->isActive->booleanValue === true) : true);
                if($condition){
                    $experience_key = $fields->key->stringValue;
                    $experience_name = $fields->name->stringValue;

                    $experience_price = @$fields->priceQuantity->arrayValue->values;
                    if(isset($experience_price) && is_array($experience_price) && count($experience_price) >= 2){
                        $experience_price = serialize($experience_price);
                    }else{
                        $experience_price = $fields->valuePerPerson->integerValue;
                    }
                    $rows = array(
                        array('meta_key' => 'ha_experience_price', 'meta_value' => $experience_price),
                        array('meta_key' => 'ha_experience_name', 'meta_value' => $experience_name)
                    );

                    foreach($rows as $row){
                        $existing_row = $wpdb->get_row(
                            $wpdb->prepare(
                                "SELECT meta_id FROM $table_name WHERE experience_key = %s AND meta_key = %s",
                                $experience_key,
                                $row['meta_key']
                            )
                        );
                        if($existing_row){
                            $wpdb->update(
                                $table_name,
                                array('meta_value' => $row['meta_value']),
                                array('meta_id' => $existing_row->meta_id)
                            );
                        } else{
                            $wpdb->insert(
                                $table_name,
                                array(
                                    'experience_key' => $experience_key,
                                    'meta_key' => $row['meta_key'],
                                    'meta_value' => $row['meta_value']
                                )
                            );
                        }
                    }
                }
			}
		}else{
			echo "Key de Agencia No registrada <br>";
		}
	}
    echo '<form method="post" action="">';
    echo '<label for="dato_input">Key de agencia: </label>';
    echo '<input type="text" id="agency_key" name="agency_key" value="' . esc_attr($agency_key) . '" />';
    echo '<input type="submit" name="save_agency" value="Guardar" class="button button-primary" /><br>';
    //Crear boton para que actualice y no ejecute cada vez que se abra la pestaña
    echo '</form>';
}

function agregar_pagina_menu() {
    add_menu_page(
            'HeyAndes Dashboard', // Título de la página
            'HeyAndes Dashboard', // Texto del menú
            'manage_options', // Capacidad requerida para verla
            'mi-plugin', // Slug
            'experiencies_management_main', // Nombre de la función de devolución de llamada
            'dashicons-admin-plugins',
        99 // Posición
    );
}
add_action('admin_menu', 'agregar_pagina_menu');

?>
