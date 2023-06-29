<?php
function customize_string($string, $before, $after){
    if($before !== ''){
        $string = $before . $string;
    }
    if($after !== ''){
        $string .= $after;
    }
    return $string;
}

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
            "SELECT meta_value FROM $table_name WHERE experience_key = %s AND meta_key = 'ha_experience_price' LIMIT 1",
            $atts['key'],
        ));
    if($existing_row){
        $experience_price = $existing_row->meta_value;
        if(!is_numeric($experience_price)){
            $experience_price = unserialize($experience_price);
            if($atts['idx'] > count($experience_price)-1 || $atts['idx'] <= 0){
                $atts['idx'] = count($experience_price)-1;
            }
            $experience_price = $experience_price[$atts['idx']]->mapValue->fields->price->integerValue;
        }
        $experience_price = '$' . number_format($experience_price,0,',','.');
        return customize_string($experience_price, $atts['before'], $atts['after']);
    }
    return;
}

function get_experience_name($atts){
    $atts = shortcode_atts(array(
            'key' => '',
            'before' => '',
            'after' => ''
    ), $atts);
    global $wpdb;
    $table_name = $wpdb->prefix . 'agency_experiences_data';
    $existing_row = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT meta_value FROM $table_name WHERE experience_key = %s AND meta_key = 'ha_experience_name' LIMIT 1",
            $atts['key'],
    ));
    if($existing_row){
        $experience_name = $existing_row->meta_value;
        return customize_string($experience_name, $atts['before'], $atts['after']);
    }
    return;
}

function get_experience_addons($atts){
    $atts = shortcode_atts(array(
        'key' => '',
        'idx' => 0,
        'before' => '',
        'after' => '',
        'all' => 0
    ), $atts);
    global $wpdb;
    $table_name = $wpdb->prefix . 'agency_experiences_data';
    $existing_row = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT meta_value FROM $table_name WHERE experience_key = %s AND meta_key = 'ha_experience_addons' LIMIT 1",
            $atts['key'],
    ));
    if($existing_row){
        $experience_addons = unserialize($existing_row->meta_value);
        if(empty($experience_addons)){
            return;
        }
        if($atts['all']){
            $addons = '';
            foreach($experience_addons as $addon){
                $addons .= $addon->mapValue->fields->name->stringValue . customize_string(' $'. number_format($addon->mapValue->fields->price->integerValue,0,',','.'), $atts['before'], $atts['after']) . '<br>';
            }
        }
        if($atts['idx'] > count($experience_addons) || $atts['idx'] < 0){
            return $experience_addons[0]->mapValue->fields->name->stringValue . customize_string(' $'. number_format($experience_addons[0]->mapValue->fields->price->integerValue,0,',','.'), $atts['before'], $atts['after']) . '<br>';;
        }
        return $experience_addons[$atts['idx']]->mapValue->fields->name->stringValue . customize_string(' $'. number_format($experience_addons[$atts['idx']]->mapValue->fields->price->integerValue,0,',','.'), $atts['before'], $atts['after']) . '<br>';
    }
    return;
}

function get_experience_elements($atts){
    $atts = shortcode_atts(array(
        'key' => '',
        'includes' => 0,
        'required' => 0,
        'idx' => 0,
        'all' => 0
    ), $atts);
    if($atts['includes'] === 0 && $atts['required'] === 0){
        return;
    }
    if($atts['includes']){
        $atribute = 'ha_experience_includes';
    }else{
        $atribute = 'ha_experience_equipment_required';
    }
    global $wpdb;
    $table_name = $wpdb->prefix . 'agency_experiences_data';
    $existing_row = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT meta_value FROM $table_name WHERE experience_key = %s AND meta_key = %s LIMIT 1",
            $atts['key'],
            $atribute
    ));
    if($existing_row){
        $experience_elements = unserialize($existing_row->meta_value);
        if(empty($experience_elements)){
            return;
        }
        if($atts['all']){
            $elements = '';
            foreach ($experience_elements as $$element){
                $elements .= $include->stringValue . '<br>';
            }
            return $elements;
        }
        if($atts['idx'] > count($experience_elements)-1 || $atts['idx'] < 0){
            return $experience_elements[0]->stringValue;
        }
        return $experience_elements[$atts['idx']]->stringValue;
    }
    return;
}

function get_experience_description($atts){
    $atts = shortcode_atts(array(
        'key' => '',
        'short' => 0,
    ), $atts);
    if($atts['short']){
        $desc_type = 'ha_experience_short_desc';
    }else{
        $desc_type = 'ha_experience_desc';
    }
    global $wpdb;
    $table_name = $wpdb->prefix . 'agency_experiences_data';
    $existing_row = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT meta_value FROM $table_name WHERE experience_key = %s AND meta_key = %s LIMIT 1",
            $atts['key'],
            $desc_type,
    ));
    if($existing_row){
        return $existing_row->meta_value;
    }
    return;
}

function get_experience_duration($atts){
    //Podria tener propiedades para poder agregarle alguna etiqueta con iconos.
    $atts = shortcode_atts(array(
        'key' => '',
        'before' => '',
        'after' => ''
    ));
    global $wpdb;
    $table_name = $wpdb->prefix . 'agency_experiences_data';
    $existing_row = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT meta_value FROM $table_name WHERE experience_key = %s AND meta_key = 'ha_duration' LIMIT 1",
            $atts['key']
    ));
    if($existing_row){
        return customize_string($existing_row->meta_value, $atts['before'], $atts['after']);
    }
}

function get_experience_destination($atts){
    $atts = shortcode_atts(array(
        'key' => '',
    ));
    global $wpdb;
    $table_name = $wpdb->prefix . 'agency_experiences_data';
    $existing_row = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT meta_value FROM $table_name WHERE experience_key = %s AND meta_key = 'ha_destination' LIMIT 1",
            $atts['key']
    ));
    if($existing_row){
        return;
    }
}

function get_experience_meeting($atts){
    $atts = shortcode_atts(array(
        'key' => '',
        'before' => '',
        'after' => '',
    ), $atts);
    global $wpdb;
    $table_name = $wpdb->prefix . 'agency_experiences_data';
    $existing_row = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT meta_value FROM $table_name WHERE experience_key = %s AND meta_key = 'ha_experience_meeting_point' LIMIT 1",
            $atts['key'],
    ));
    if($existing_row){
        return customize_string($existing_row->meta_value, $atts['before'], $atts['after']);
    }
    return;
}

function shortcodes_register(){
    add_shortcode('experience_name', 'get_experience_name');
    add_shortcode('experience_price', 'get_experience_price');
    add_shortcode('experience_addons', 'get_experience_addons');
    add_shortcode('experience_elements', 'get_experience_elements');
    add_shortcode('experience_desc', 'get_experience_description');
    add_shortcode('experience_meeting', 'get_experience_meeting');
    add_shortcode('experience_destination','get_experience_destination');
    add_shortcode('experience_duration', 'get_experience_duration');
}
?>
