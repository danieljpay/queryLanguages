<?php
    include("databaseFunctions.php");
    include("printResults.php");

    function analyzerInput($words) {
        $categoriasBusqueda = ["ProductName", "QuantityPerUnit", "CategoryID"];
        $query = "";
        $tableToSearch = "";
        $campoToSearch = "";
        $camposInput = lookCamposInput($words);
        $hasCamposInput = $camposInput ? true : false;
        if($camposInput) {
            $camposArray = explode(",", $camposInput);
            foreach($camposArray as $campo) {
                $temp = explode(".", $campo);
                // var_dump($temp);+
                $tableToSearch = $temp[0];
                $campoToSearch = $temp[1];
            }
        } else {
            $tableToSearch = "products";
            $camposInput = "products.ProductName, products.QuantityPerUnit, products.CategoryID";
        }

        //Detección de operadores
        for ($i=0; $i < count($categoriasBusqueda); $i++) {
            $query = "SELECT " . $camposInput . " FROM " . $tableToSearch . " WHERE ";
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
                                if($hasCamposInput) {
                                    $wordToSearch = substr(strstr($words[$j], '('), 1, -1);
                                    $query .= $campoToSearch . " LIKE '%" . $wordToSearch . "%'";
                                } else {
                                    $wordToSearch = substr(strstr($words[$j], '('), 1, -1);
                                    $query .= $categoriasBusqueda[$i] . " = '" . $wordToSearch ."'";
                                }
                                break;
                            case 'PATRON':
                                //echo "encontré un patrón()";
                                if ($hasCamposInput) {
                                    $wordToSearch = substr(strstr($words[$j], '('), 1, -1);
                                    $query .= $campoToSearch . " LIKE '%" . $wordToSearch . "%'";
                                } else {
                                    $wordToSearch = substr(strstr($words[$j], '('), 1, -1);
                                    $query .= $categoriasBusqueda[$i] . " LIKE '%" . $wordToSearch . "%'";
                                }
                                break;
                            case 'CAMPOS':
                                break;
                            default:
                                //echo "encontré una palabra";
                                if($hasCamposInput) {
                                    $query .= $campoToSearch . " LIKE '%" . $words[$j] . "%'";
                                } else {
                                    $query .= $categoriasBusqueda[$i] . " LIKE '%" . $words[$j] . "%'";
                                }
                                break;
                        }
                        break;
                }
                $i = $hasCamposInput ? count($words)-1 : $i; //para salir del array de categorías si el usuario puso CAMPOS() o mantenerse 
            }

            echo $query . "<br/><br/>";
            $results = executeQuery($query);
            printResults($results);
        }
    }

    function lookCamposInput($words) {
        for ($i=0; $i < count($words); $i++) { 
            if(strstr($words[$i], '(', true) == "CAMPOS") {
                // var_dump(substr(strstr($words[$i], '('), 1, -1));
                return substr(strstr($words[$i], '('), 1, -1);
            }
        }
        return "";
    }
?>