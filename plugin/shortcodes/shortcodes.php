<?php
function get_experience_price($atts){
    $atts = shortcode_atts(array(
            'key' => '',
            'style' => 'normal'
    ), $atts);
    global $wpdb;
    $table_name = $wpdb->prefix . 'agency_experiences_data';
    $existing_row = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT meta_value FROM $table_name WHERE experience_key = %s AND meta_key = 'ha_experience_price'",
            $atts['key'],
        ));
    if($existing_row){
        //Podria trabajarse un string que se vaya concatenando para cada caso (si es que sequiere usar sub por ejemplo), para hacerlo limpio
        if($atts['style'] == 'normal'){
            return '$' . number_format($existing_row->meta_value,0,',','.');
        }else{
            return '$'. number_format($existing_row->meta_value,0,',','.') . $atts['style'] ;
        }
    }else{
        return;
    }

}
function get_experience_name(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'agency_experiences_data';
    return 'This is a experience name';
}

function shotcodes_register(){
    add_shortcode('experience_price', 'get_experience_price');
    add_shortcode('experience_name', 'get_experience_name');
}
?>
