<?php
    include('../wp-load.php');
    //include('header.php');
    $args = array();
    $args['echo'] = FALSE;
    $login_form = wp_login_form($args);

    echo $login_form;
?>