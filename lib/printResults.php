<?php
    function printResults($results) {
        foreach($results as $coincidence) {
            echo "<div class='results-card'>";
            foreach ($coincidence as $key) {
                echo "<p>";
                echo $key;
                echo "</p>";
            }
            echo "</div>";
        }
        echo "<br/>";
    }
?>