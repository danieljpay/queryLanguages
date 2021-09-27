<?php
    include("../lib/databaseFunctions.php");

    function analyzerInput($words) {
        $categoriasBusqueda = ["ProductName", "QuantityPerUnit", "CategoryID"];
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
                        switch ( strstr($words[$j], '(', true) ) {
                            case 'CADENA':
                                //echo "encontré una cadena()";
                                $wordToSearch = substr(strstr($words[$j], '('), 1, -1);
                                $query .= $categoriasBusqueda[$i] . " = '" . $wordToSearch ."'";
                                break;
                            case 'PATRON':
                                //echo "encontré un patrón()";
                                $wordToSearch = substr(strstr($words[$j], '('), 1, -1);
                                $query .= $categoriasBusqueda[$i] . " LIKE '%" . $wordToSearch . "%'";
                                break;
                            default:
                                //echo "encontré una palabra";
                                $query .= $categoriasBusqueda[$i] . " LIKE '%" . $words[$j] . "%'";
                                break;
                        }
                        break;
                }
            }
            echo $query . "<br/><br/>";
            $results = executeQuery($query);

            foreach($results as $coincidence) {
                echo "<div class='results-card'>";
                echo "<p>" . "ProductName: " . $coincidence["ProductName"] . " </p>";
                echo "<p>" . "QuantityPerUnit: " . $coincidence["QuantityPerUnit"] . " </p>"; 
                echo "<p>" . "CategoryID: " . $coincidence["CategoryID"] . " </p>";
                echo "</div>";
            }

            echo "<br/>";
        }
    }
?>