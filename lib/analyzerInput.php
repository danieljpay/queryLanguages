<?php
    include("databaseFunctions.php");
    include("printResults.php");
    include("structureQueryWithCampos.php");
    include("structureQueryWithoutCampos.php");

    function analyzerInput($words) {
        //Detección de campos
        $camposInput = lookCamposInput($words);
        $hasCamposInput = $camposInput ? true : false;
        
        if($hasCamposInput) {
            $words = deleteCamposFromWords($words);
            structureQueryWithCampos($words, $camposInput);
        } else {
            structureQueryWithoutCampos($words);
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