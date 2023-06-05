<?php
function get_experience_price($atts){
    $atts = shortcode_atts(array(
            'key' => '',
            'before' => '',
            'after' => '',
            'idx' => 0
    ), $atts);
    global $wpdb;
    $table_name = $wpdb->prefix . 'agency_experiences_data';
    $existing_row = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT meta_value FROM $table_name WHERE experience_key = %s AND meta_key = 'ha_experience_price'",
            $atts['key'],
        ));
    if($existing_row){
        $experience_price = $existing_row->meta_value;
        if(!is_numeric($experience_price)){
            $experience_price = unserialize($experience_price);
            if($atts['idx'] > count($experience_price)-1 || $atts['idx'] < 0){
                $atts['idx'] = count($experience_price)-1;
            }
            $experience_price = $experience_price[$atts['idx']]->mapValue->fields->price->integerValue;
        }
        $experience_price = '$' . number_format($experience_price,0,',','.');
        if($atts['before'] !== ''){
            $experience_price = $atts['before'] . $experience_price;
        }
        if($atts['after'] !== ''){
            $experience_price .= $atts['after'];
        }
        return $experience_price;
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
