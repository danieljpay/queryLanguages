<?php
    include("analyzerInput.php");

    if(isset( $_GET["inputSearch"] )) {
        $input = $_GET["inputSearch"];
        $words = explode(" ", $input);
        // var_dump($words);
        
        analyzerInput($words);
        
    } else {
        echo "<p>Tus resultados se mostrarán aquí</p>";
    }
?>