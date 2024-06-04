<?php
class DatabaseConnection {
    public static function connect() {
        $serverName = "193.85.203.188";
        $connectionOptions = array(
            "Database" => "bilek3",
            "Uid" => "bilek3",
            "PWD" => "bilek3data"
        );
        $conn = sqlsrv_connect($serverName, $connectionOptions);
        
        if ($conn === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        
        return $conn;
    }
}

?>