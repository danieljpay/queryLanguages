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
        // echo $query . "<br/><br/>";
        $connection = connectDB();
        $queryResults = mysqli_query($connection, $query);
        if($queryResults) {
            for ($arrayResults = array(); $line = mysqli_fetch_assoc($queryResults); $arrayResults[] = $line);
        } else {
            $queryResults="";
        }
        closeConnectionBD($connection);
        return $arrayResults;
    }
?>