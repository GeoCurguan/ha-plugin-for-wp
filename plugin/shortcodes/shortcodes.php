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
            'idx' => 0,
            'coupon' => 1
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
            if($atts['idx'] > count($experience_price)-1 || $atts['idx'] < 0){
                $atts['idx'] = count($experience_price)-1;
            }
            $experience_price = $experience_price[$atts['idx']]->mapValue->fields->price->integerValue;
        }
        if($atts['coupon']){
            $get_coupon_percent = $wpdb->get_row(
                $wpdb->prepare(
                "SELECT meta_value FROM $table_name WHERE experience_key = %s AND meta_key = 'ha_discount_coupon' LIMIT 1",
                $atts['key'],
            ));
            if($get_coupon_percent){
                $coupon_percent = $get_coupon_percent->meta_value;
                $new_price = $experience_price*(1-$coupon_percent/100);
                $new_price = '$' . number_format($new_price,0,',','.');
                $experience_price = '$' . number_format($experience_price,0,',','.');
                return '<sub><del>'. $experience_price . '</sub></del>' . customize_string($new_price, $atts['before'], $atts['after']);
            }
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
        if($atts['idx'] > count($experience_addons)){
            return $experience_addons[0]->mapValue->fields->name->stringValue;
        }
        return $experience_addons[$atts['idx']]->mapValue->fields->name->stringValue . customize_string(' $'. number_format($experience_addons[$atts['idx']]->mapValue->fields->price->integerValue,0,',','.'), $atts['before'], $atts['after']) . '<br>';;
    }
    return;
}

function get_experience_includes($atts){
    $atts = shortcode_atts(array(
        'key' => '',
        'idx' => 0,
        'all' => 0
    ), $atts);
    global $wpdb;
    $table_name = $wpdb->prefix . 'agency_experiences_data';
    $existing_row = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT meta_value FROM $table_name WHERE experience_key = %s AND meta_key = 'ha_experience_includes' LIMIT 1",
            $atts['key'],
    ));
    if($existing_row){
        $experience_includes = unserialize($existing_row->meta_value);
        if(empty($experience_includes)){
            return;
        }
        if($atts['all']){
            $includes = '';
            foreach ($experience_includes as $include){
                $includes .= $include->stringValue . '<br>';
            }
            return $includes;
        }
        if($atts['idx'] > count($experience_includes)-1){
            return $experience_includes[0]->stringValue;
        }
        return $experience_includes[$atts['idx']]->stringValue;
    }
    return;
}

//Esta funcion podria devolver tanto la descripcion corta o larga, a partir del parametro de entrada
//meta_key aun no se ingresa a la bd de wp
function get_experience_short_description($atts){
    $atts = shortcode_atts(array(
        'key' => '',
    ), $atts);
    global $wpdb;
    $table_name = $wpdb->prefix . 'agency_experiences_data';
    $existing_row = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT meta_value FROM $table_name WHERE experience_key = %s AND meta_key = 'ha_experience_short_description' LIMIT 1",
            $atts['key'],
    ));
    if($existing_row){
        return $existing_row->meta_value;
    }
    return 'This is an short description';
}

function shortcodes_register(){
    add_shortcode('experience_name', 'get_experience_name');
    add_shortcode('experience_price', 'get_experience_price');
    add_shortcode('experience_addons', 'get_experience_addons');
    add_shortcode('experience_includes', 'get_experience_includes');
    add_shortcode('experience_short_desc', 'get_experience_short_description');
}
?>
