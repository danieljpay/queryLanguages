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
                                if(strpos($words[$j], ")")) {
                                    $wordToSearch = substr(strstr($words[$j], '('), 1, -1);
                                    $query .= $categoriasBusqueda[$i] . " = '" . $wordToSearch ."'";
                                } else {
                                    $wordToSearch = substr(strstr($words[$j], '('), 1);
                                    while(!strpos($words[$j], ")")) {
                                        $j++;
                                        $wordToSearch .= " " . $words[$j];
                                    }
                                    $wordToSearch = substr($wordToSearch, 0 , -1);
                                    $query .= $categoriasBusqueda[$i] . " = '" . $wordToSearch . "'";
                                }
                                break;
                            case 'PATRON':
                                $wordToSearch = substr(strstr($words[$j], '('), 1, -1);
                                $query .= $categoriasBusqueda[$i] . " LIKE '%" . $wordToSearch . "%'";  
                                break;
                            default:
                                $query .= $categoriasBusqueda[$i] . " LIKE '%" . $words[$j] . "%'";
                                break;
                        }
                        break;
                }
            }
            $results = executeQuery($query);
            printResults($results);
        }
    }
?>