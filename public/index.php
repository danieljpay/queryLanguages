<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Query languages</title>
    <link rel="shortcut icon" href="assets/favicon.png">
    <style type="text/css">@import url("../src/styles/index.css");</style>
</head>
<body>
    <?php
        include("../lib/analyzerInput.php");

        echo "<h1 class='pageTitle'>Query languages</h1>";
        include("../src/components/Searcher.html");

        echo "<hr/>";

        echo "<div class='results'>";

            if(isset( $_GET["inputSearch"] )) {
                $input = $_GET["inputSearch"];
                $words = explode(" ", $input);
                
                analyzerInput($words);

            } else {
                echo "<p>Tus resultados se mostrarán aquí</p>";
            }

        echo "</div>";
    ?>
</body>
</html>