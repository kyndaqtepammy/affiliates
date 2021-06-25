<?php

function affiliate_login_form() {
    if(!is_user_logged_in()) {
        global $load_affiliate_css;
        //set to true so css is loaded
        $load_affiliate_css = true;
        $output = sdds_login_form_fields();
    }
    return $output;
}