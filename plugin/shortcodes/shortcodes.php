<?php
function get_experience_price(){
    return 'This is price';
}
function get_experience_name(){
    return 'This is a experience name';
}

function shotcodes_register(){
    add_shortcode('experience_price', 'get_experience_price');
    add_shortcode('experience_name', 'get_experience_name');
}
?>
