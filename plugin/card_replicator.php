use Elementor\Plugin;

function searchProperties(&$data, $values_search){
    foreach($data as $key => &$value){
        if(is_array($value) && $key !== '__dynamic__'){
            searchProperties($value, $values_search);
        } else{
            if($key === '__dynamic__'){
                print_r($value);
                echo "<br>";
            }
            if($key === 'custom_css'){
                print_r($value);
                echo "<br>";
            }
            if(in_array($key, $values_search)){
                //print_r($value);
                //echo "Valor encontrado: " . $value . "<br>";
            }
        }
    }
}

function card_replicator() {
    // Obtén el contenido del template
    $template_id = 240; // ID del template de Elementor
    //$content = Plugin::instance()->frontend->get_builder_content_for_display($template_id);
    $content = Plugin::instance()->documents->get($template_id);

    //Para la imagen puede tener como parametro de entrada una url
    //Obtenemos el json de la seccion template
    $section = $content->get_elements_data();

    $experience_key = "DOpjqYgkPPEfZG1PUaHZ";
    //$section[0] => Data de la seccion como pagina, dentro de ella hay 5 keys del array asociativo, elements es el que nos interesa
    //$section[0]['elements'] => Cada elemento dentro es una columna (En la ver. nueva son containers). Por defecto la primera columa sera
    //                           $section[0]['elements'][0]

    $columncopy = $section[0]['elements'][0];
    foreach($columncopy['elements'] as &$col_element){
        if(isset($col_element['settings']['background_image'])){
            //Chequear porque no se cambia la imagen
            $col_element['settings']['background_image']['url'] = "http://localhost/wordpress/wp-content/uploads/2022/12/skyairline_skyairline_image_562-1536x1024-1.jpeg";
            echo $col_element['settings']['background_image']['url'];
            echo "<br>";
            $col_element['settings']['background_image']['id'] = 32;
            echo $col_element['settings']['background_image']['id'];
        }
        if(isset($col_element['elements'])){
            foreach($col_element['elements'] as &$text){
                if(isset($text['__dynamic__'])){
                    echo $text['__dynamic__']['editor'];
                }
                //Profundidad maxima detectada que puede tener un elemento
                foreach($text['elements'] as &$text2){
                    if(isset($text2['settings']['__dynamic__']['editor'])){
                        $copia = $text2['settings']['__dynamic__']['editor'];
                        $textoModificado = str_replace('5HK1tpfZ37j6OsjZIbzk',$experience_key, $copia);
                        $text2['settings']['__dynamic__']['editor'] = $textoModificado;
                    }
                }
            }
        }
        //
    }

    //Borramos los elementos del array para no generar una card nueva por cada ejecucion
    array_splice($section[0]['elements'], 1);
    //Agregamos la card a la otras que estaban
    array_push($section[0]['elements'], $columncopy);
    //Para agregar columnas sirve update_json_meta('_elementor_data', $var) o update_meta('_elementor_data', $var), estos cambios se aplican con cada ejecucion
    $content->update_json_meta('_elementor_data',$section);

    //print_r($content->get_elements_data());

    /*
    //Parametros utiles
    $methods = get_class_methods($content);
    echo "<pre>";
    print_r($methods);
    echo "</pre>";
    */
    return;
}
