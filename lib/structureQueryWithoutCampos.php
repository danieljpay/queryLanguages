<?php
    function structureQueryWithoutCampos($words) {
        $categoriasBusqueda = ["ProductName", "QuantityPerUnit", "CategoryID"];
        $tableToSearch = "products";
        for ($i=0; $i < count($categoriasBusqueda); $i++) {
            $query = "SELECT " . "products.ProductName, products.QuantityPerUnit, products.CategoryID" . " FROM " . $tableToSearch . " WHERE ";
            for ($j=0; $j < count($words); $j++) { 
                switch ($words[$j]) {
                    case "AND":
                        $query .= " AND ";
                        break;
                    case "OR":
                        $query .= " OR ";
                        break;
                    case "NOT":
                        $query .= "NOT ";
                        break; 
                    default:
                        switch ( strstr($words[$j], '(', true) ) {
                            case 'CADENA':
                                //echo "encontré una cadena()";
                                if(strpos($words[$j], ")")) {
                                    $wordToSearch = substr(strstr($words[$j], '('), 1, -1);
                                    $query .= $categoriasBusqueda[$i] . " = '" . $wordToSearch ."'";
                                } else {
                                    $wordToSearch = substr(strstr($words[$j], '('), 1); //elimina caracter "("
                                    while(!strpos($words[$j], ")")) {
                                        $j++;
                                        $wordToSearch .= " " . $words[$j];
                                    }
                                    $wordToSearch = substr($wordToSearch, 0 , -1); //elimina caracter ")"
                                    $query .= $categoriasBusqueda[$i] . " = '" . $wordToSearch . "'";
                                }
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
            printResults($results);
        }
    }
?>