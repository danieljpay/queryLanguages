<?php 
    function structureQuerywithCampos($words, $camposInput) {
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

?>