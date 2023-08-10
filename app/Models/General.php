<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class General extends Model
{
    
    /**
     * Converte valor monetário ($ -> R$), 2 casas decimais
     * @var float $float_encode
     * 
     * @return string $float_decode
     */
    public static function decodeFloat2(float $float_encode) : string {
        // Converte o float em string com 2 casas decimais.
        $float_decode = number_format((float)$float_encode, 2, ",", ".");

        return (string)$float_decode;
    }

    /**
     * Converte valor monetário (R$ -> $), 2 casas decimais
     * @var string $float_decode
     * 
     * @return float $float_encode
     */
    public static function encodeFloat2(string $float_decode) : float {
        // Inicializa variável.
        $float_encode = 0.000;

        // Converte a string em float com 2 casas decimais.
        if(!empty($float_decode)) $float_encode = number_format(str_replace(",", ".", str_replace(".", "", $float_decode)), 2, '.', '');

        return (float)$float_encode;
    }

    /**
     * Converte valor monetário ($ -> R$), 3 casas decimais
     * @var float $float_encode
     * 
     * @return string $float_decode
     */
    public static function decodeFloat3(float $float_encode) : string {
        // Converte o float em string com 3 casas decimais.
        $float_decode = number_format((float)$float_encode, 3, ",", ".");

        return (string)$float_decode;
    }

    /**
     * Converte valor monetário (R$ -> $), 3 casas decimais
     * @var string $float_decode
     * 
     * @return float $float_encode
     */
    public static function encodeFloat3(string $float_decode) : float {
        // Inicializa variável.
        $float_encode = 0.000;

        // Converte a string em float com 3 casas decimais.
        if(!empty($float_decode)) $float_encode = number_format(str_replace(",", ".", str_replace(".", "", $float_decode)), 3, '.', '');
        
        return (float)$float_encode;
    }

}
