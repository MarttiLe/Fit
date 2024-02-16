<?php

// Change sender email
function theme_sender_email( $original_email_address ) {
    return 'sten@logic.ee';
}
//add_filter( 'wp_mail_from', 'theme_sender_email' );

// Change sender name
function theme_sender_name( $original_email_from ) {
    return 'Logic';
}
//add_filter( 'wp_mail_from_name', 'theme_sender_name' );