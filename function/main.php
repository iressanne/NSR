<?php


function logged_in() {
    // Check if the user is logged in, if not then redirect him to login page
    if( isset( $_SESSION["loggedin"] ) && $_SESSION["loggedin"] == true ) {
        return true;
    } else { return false; }
}