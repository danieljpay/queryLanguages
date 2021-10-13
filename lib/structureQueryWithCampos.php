<?php 
    function structureQuerywithCampos($words, $camposInput) {
        $camposArray = explode(",", $camposInput);
        $camposToSearch = [];
        for ($i=0; $i < count($camposArray); $i++) { 
            $temp = explode(".", $camposArray[$i]);
            array_push($camposToSearch, $temp[1]);
        }
        $tableToSearch = $temp[0];

        for ($i=0; $i < count($camposToSearch); $i++) { 
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
                                    $query .= $camposToSearch[$i] . " = '" . $wordToSearch . "'";
                                } else {
                                    $wordToSearch = substr(strstr($words[$j], '('), 1); //elimina caracter "("
                                    while(!strpos($words[$j], ")")) {
                                        $j++;
                                        $wordToSearch .= " " . $words[$j];
                                    }
                                    $wordToSearch = substr($wordToSearch, 0 , -1); //elimina caracter ")"
                                    $query .= $camposToSearch[$i] . " = '" . $wordToSearch . "'";
                                }
                                break;
                            case 'PATRON':
                                //echo "encontré un patrón()";
                                $wordToSearch = substr(strstr($words[$j], '('), 1, -1);
                                $query .= $camposToSearch[$i] . " LIKE '%" . $wordToSearch . "%'";
                                break;
                            default:
                                //echo "encontré una palabra";
                                $query .= $camposToSearch[$i] . " LIKE '%" . $words[$j] . "%'";
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