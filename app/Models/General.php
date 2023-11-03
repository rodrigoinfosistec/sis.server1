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
    
    /**
     * Traduz dia da semana.
     * @var string $week_encode
     * 
     * @return string $week_decode
     */
    public static  function decodeWeek($week_encode) : string {
        // Verifica o dia a ser traduzido.
        switch($week_encode):
            case 'Sunday'   : $week_decode = 'Domingo';       break;
            case 'Monday'   : $week_decode = 'Segunda-feira'; break;
            case 'Tuesday'  : $week_decode = 'Terça-feira';   break;
            case 'Wednesday': $week_decode = 'Quarta-feira';  break;
            case 'Thursday' : $week_decode = 'Quinta-feira';  break;
            case 'Friday'   : $week_decode = 'Sexta-feira';   break;
            case 'Saturday' : $week_decode = 'Sábado';        break;
        endswitch;

        return $week_decode;
    }

    /**
     * Traduz dia da semana.
     * @var string $week_encode
     * 
     * @return string $week_decode
     */
    public static  function decodeWeekAbreviate($week_encode) : string {
        // Verifica o dia a ser traduzido.
        switch($week_encode):
            case 'Sunday'   : $week_decode = 'Dom'; break;
            case 'Monday'   : $week_decode = 'Seg'; break;
            case 'Tuesday'  : $week_decode = 'Ter'; break;
            case 'Wednesday': $week_decode = 'Qua'; break;
            case 'Thursday' : $week_decode = 'Qui'; break;
            case 'Friday'   : $week_decode = 'Sex'; break;
            case 'Saturday' : $week_decode = 'Sab'; break;
        endswitch;

        return $week_decode;
    }

    /**
     * Converte data.
     * @var string $date_encode
     * 
     * @return string $date_decode
     */
    public static  function decodeDate($date_encode) : string {
        // Converte data.
        $date = explode('-', $date_encode);
        $date_decode = 
            $date[2] . 
            '/' . 
            $date[1] . 
            '/' . 
            $date[0]
        ;

        return (string)$date_decode;
    }

    /**
     * Descreve mês e ano (Em português).
     * 
     * @var string $month
     * 
     * @return string $mes
     */
    public static function describeMonth(string $month) : string {
        // Divide a string.
        $x = explode('-', $month);

        // Define o ano.
        $year = $x[0];

        // Define o mês.
        switch($x[1]):
            case '01': $describe = 'Jan'; break;
            case '02': $describe = 'Fev'; break;
            case '03': $describe = 'Mar'; break;
            case '04': $describe = 'Abr'; break;
            case '05': $describe = 'Mai'; break;
            case '06': $describe = 'Jun'; break;
            case '07': $describe = 'Jul'; break;
            case '08': $describe = 'Ago'; break;
            case '09': $describe = 'Set'; break;
            case '10': $describe = 'Out'; break;
            case '11': $describe = 'Nov'; break;
            case '12': $describe = 'Dez'; break;
        endswitch;

        // Formata a string.
        (string)$mes = (string)$describe . '/' . (string)$year;

        return $mes;
    }

}
