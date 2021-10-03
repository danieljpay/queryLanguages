<?php
    include("databaseFunctions.php");
    include("printResults.php");

    function analyzerInput($words) {
        //Detección de campos
        $camposInput = lookCamposInput($words);
        $hasCamposInput = $camposInput ? true : false;
        if($camposInput) {
            $words = deleteCamposFromWords($words);
        }
        
        if($hasCamposInput) {
            withCampos($words, $camposInput);
        } else {
            withoutCampos($words);
        }
    }

    function withCampos($words, $camposInput) {
        $camposArray = explode(",", $camposInput);
        foreach($camposArray as $campo) {
            $temp = explode(".", $campo);
            $tableToSearch = $temp[0];
            $campoToSearch = $temp[1];
        }

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
                            if(strpos($words[$j], ")")) {
                                $wordToSearch = substr(strstr($words[$j], '('), 1, -1);
                                $query .= $campoToSearch . " = '" . $wordToSearch . "'";
                            } else {
                                $wordToSearch = substr(strstr($words[$j], '('), 1); //elimina caracter "("
                                while(!strpos($words[$j], ")")) {
                                    $j++;
                                    $wordToSearch .= " " . $words[$j];
                                }
                                $wordToSearch = substr($wordToSearch, 0 , -1); //elimina caracter ")"
                                $query .= $campoToSearch . " = '" . $wordToSearch . "'";
                            }
                            break;
                        case 'PATRON':
                            //echo "encontré un patrón()";
                            $wordToSearch = substr(strstr($words[$j], '('), 1, -1);
                            $query .= $campoToSearch . " LIKE '%" . $wordToSearch . "%'";
                            break;
                        default:
                            //echo "encontré una palabra";
                            $query .= $campoToSearch . " LIKE '%" . $words[$j] . "%'";
                            break;
                    }
                    break;
            }
        }
        echo $query . "<br/><br/>";
        $results = executeQuery($query);
        printResults($results);
    }

    function withoutCampos($words) {
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

    function lookCamposInput($words) {
        for ($i=0; $i < count($words); $i++) { 
            if(strstr($words[$i], '(', true) == "CAMPOS") {
                $camposValue = substr(strstr($words[$i], '('), 1); //elimina caracter "("
                while(!strpos($words[$i], ")")) {
                    $i++;
                    $camposValue .= " " . $words[$i];
                }
                $camposValue = substr($camposValue, 0 , -1); //elimina caracter ")"
                return $camposValue;
            }
        }
        return "";
    }

    function deleteCamposFromWords($words) {
        for ($i=0; $i < count($words); $i++) { 
            if(strstr($words[$i], '(', true) == "CAMPOS") {
                while(!strpos($words[$i], ")")) {
                    $i++;
                    unset($words[$i-1]);
                }
                if(strpos($words[$i], ')')) { //para eliminar el último elemento
                    unset($words[$i]);   
                }
            }
        }
        return $words;
    }
?>