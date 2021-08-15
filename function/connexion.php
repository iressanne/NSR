<?php
    function OpenCon() {
        $dbhost = "localhost";
        $dbuser = "root";
        $dbpass = "root";
        $db = "nsr";
        $port = "3307";

        try {
            $db = new PDO( "mysql:host=$dbhost:$port;dbname=$db;", $dbuser, $dbpass);
            $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        } catch( PDOException $e ) {
            print $e->getMessage();
        }
        return $db;

    }
?>