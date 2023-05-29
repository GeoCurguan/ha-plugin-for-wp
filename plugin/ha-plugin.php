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

function hola_mundo(){
  echo 'Hola desde el Dashboard de Wordpress';
}

function registrar_widget_hola_mundo() {
    wp_add_dashboard_widget('widget_hola_mundo', 'Mi Widget Hola Mundo', 'hola_mundo');
}
add_action('wp_dashboard_setup', 'registrar_widget_hola_mundo');

?>
