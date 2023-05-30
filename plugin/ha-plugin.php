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

function agregar_pagina_menu() {
  add_menu_page(
      'HeyAndes Dashboard', // Título de la página
      'HeyAndes Dashboard', // Texto del menú
      'manage_options', // Capacidad requerida para verla
      'mi-plugin', // Slug
      'hola_mundo', // Nombre de la función de devolución de llamada
      'dashicons-admin-plugins',
      99 // Posición
  );
}
add_action('admin_menu', 'agregar_pagina_menu');

?>
