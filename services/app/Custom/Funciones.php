<?php

namespace App\Custom;

use App\Models\Folio;

class Funciones
{

    public static function getMeFolio($tipoFolio) {
        try {
            $folio = Folio::query()
                ->where('TipoFolio','=',$tipoFolio)
                ->first();

            if(!empty($folio)) {
                $arr = [
                    'IdFolio'   => $folio->IdFolio,
                    'Serie'     => $folio->Serie,
                    'Numero'    => ($folio->Numero + 1),
                ];

                return [
                    'status' => true,
                    'data' => $arr
                ];
            }else {
                return [
                    'status' => false,
                ];
            }


        }catch (\Exception $exception){
            ResponseManager::createLog($exception,'Funciones','getMeFolio');
        }

    }

    public static function updateFolio($IdFolio = 0, $numero = 0) {
        try {
            $folio = Folio::query()->find($IdFolio);
            if ( !empty($folio) ){
                $folio->Numero = $numero;

                if($folio->save()) {
                    return [
                        'status' => true
                    ];
                }else {
                    return [
                        'status' => false
                    ];
                }
            }
        }catch (\Exception $exception){
            ResponseManager::createLog($exception,'Funciones','updateFolio');
        }

    }

    /* Funcion encargada de eliminar cualquier caracter extraño */

    public static function quitarCaracteres($string = '')
    {
        $string = trim($string);
        $string = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $string
        );

        $string = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $string
        );

        $string = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $string
        );

        $string = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $string
        );

        $string = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $string
        );

        $string = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C',),
            $string
        );

        //Esta parte se encarga de eliminar cualquier caracter extraño
       $string = str_replace(
            array("\"", "¨", "º", "-", "~",
                "#", "@", "|", "!", '"',
                "·", "$", "%", "&", "/",
                "(", ")", "?", "'", "¡",
                "¿", "[", "^", "<code>", "]",
                "+", "}", "{", "¨", "´",
                ">", "< ", ";", ",", ":",
                "."),
            '',
            $string
        );

        return $string;
    }
}
