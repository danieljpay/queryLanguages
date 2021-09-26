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
        echo "<h1 class='pageTitle'>Query languages</h1>";
        include("../src/components/Searcher.html");

        echo "<hr/>";

        echo "<div class='results'>";

            if(isset( $_GET["inputSearch"] )) {
                $input = $_GET["inputSearch"];
                $words = explode(" ", $input);
                //var_dump($words);
                
                $categoriasBusqueda = ["ProductName","Quantity","Category"];
                $query = "";
                //Detección de operadores
                for ($i=0; $i < count($categoriasBusqueda); $i++) {
                    $query = "SELECT products.ProductName, products.QuantityPerUnit, products.CategoryID FROM products WHERE ";
                    for ($j=0; $j < count($words); $j++) { 
                        switch ($words[$j]) {
                            case "AND":
                                //echo "encontré un AND";
                                $query .= " AND ";
                                break;
                            case "OR":
                                //echo "encontré un OR";
                                $query .= " OR ";
                                break;
                            case "NOT":
                                //echo "encontré un NOT";
                                $query .= "NOT ";
                                break;
                            default:
                                //echo "no encontré ningún operador";
                                $query .= $categoriasBusqueda[$i] . " LIKE '%" . $words[$j] . "%'";
                                break;
                        }
                    }
                    echo $query . "<br/><br/>";
                }
                
            } else {
                echo "<p>Tus resultados se mostrarán aquí</p>";
            }

        echo "</div>";
    ?>
</body>
</html>