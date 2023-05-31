<?php
/*
Plugin Name: HA Plugin
Plugin URI: URL del sitio web del plugin
Description: Conecta los datos de tu agencia en HeyAndes con tu sitio de Wordpress
Version: 1.0.0
Author: Hey Andes SPA
Author URI: URL de tu sitio web o empresa
License: Licencia del plugin
*/

function experiencies_management_main(){
	   if (isset($_POST['save_agency'])) {
		// Guardar el valor del input en la opción 'dato_guardado'
		update_option('key_agency', sanitize_text_field($_POST['agency_key']));
		echo '<div class="updated"><p>Valor guardado correctamente.</p></div>';
	    }
    
	$agency_key = get_option('key_agency');
	if($agency_key){
		//Mejorar a que esta información sólo se guarde cuando se conecta con la key, y no cada vez que ingresa al dashboard
		$url = "https://firestore.googleapis.com/v1/projects/heyandes-web/databases/(default)/documents/agency/" . $agency_key;
		$json_data = file_get_contents($url);
		$data = json_decode($json_data);	
		if(isset($data)){
			echo "Agencia conectada correctamente";
			global $wbdb;
			$resultados = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}posts");
			var_dump($resultados);
			//$agency_name = $data->fields->name->stringValue;
		}else{
		
			echo "Key de Agencia No registrada <br>";
		}
		//echo $agency_name;
	}
    
    	echo '<form method="post" action="">';
    	echo '<label for="dato_input">Key de agencia: </label>';
        echo '<input type="text" id="agency_key" name="agency_key" value="' . esc_attr($agency_key) . '" />';
        echo '<input type="submit" name="save_agency" value="Guardar" class="button button-primary" /><br>';
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
