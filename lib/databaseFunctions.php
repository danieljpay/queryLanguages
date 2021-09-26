<?php
    function connectDB() {
        $connection = mysqli_connect("127.0.0.1", "root", "", "northwind");
        if(!$connection) {
            die("Fail: " . mysqli_connect_error());
        }
        return $connection;
    }

    function closeConnectionBD($connection) {
        mysqli_close($connection);
    }

    function executeQuery($query) {
        $connection = connectDB();
        $queryResults = mysqli_query($connection, $query);
        for ($arrayResults = array(); $line = mysqli_fetch_assoc($queryResults); $arrayResults[] = $line);
        closeConnectionBD($connection);
        return $arrayResults;
    }
?>