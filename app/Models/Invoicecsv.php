<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Invoicecsv extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'invoicecsvs';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'invoice_id',

        'code',
        'reference',
        'ean',
        'name',

        'cost',
        'margin',
        'value',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function invoice(){return $this->belongsTo(Invoice::class);}

    /**
     * Verifica se Referência está vazia.
     * @var <null, int> $reference
     * 
     * @return string $reference
     */
    public static function referenceEmpty($reference) : string {
        // Verifica se Referência está vazia.
        if (!$reference) $reference = null;

        return (string)$reference;
    }

    /**
     * Verifica se Ean está vazio.
     * @var <null, int> $ean
     * 
     * @return string $ean
     */
    public static function eanEmpty($ean) : string {
        // Verifica se Ean está vazio.
        if (!$ean) $ean = null;

        return (string)$ean;
    }

    /**
     * Trata o nome do item csv.
     * @var <null, int> $name
     * 
     * @return string $name
     */
    public static function nameValidate(string $name) : string {
        // Define acentuações e/ou caracteres especiais a serem substituídas.
        $name_decode = [
            'Š'=>'S',  'š'=>'s', 'Ð'=>'Dj', 'Â'=>'Z', 'Â'=>'z', 'À'=>'A',  'Á'=>'A', 'Â'=>'A', 'Ã'=>'A',  'Ä'=>'A',
            'Å'=>'A',  'Æ'=>'A', 'Ç'=>'C',  'È'=>'E',  'É'=>'E',  'Ê'=>'E',  'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I',  'Î'=>'I',
            'Ï'=>'I',  'Ñ'=>'N', 'Å'=>'N', 'Ò'=>'O',  'Ó'=>'O',  'Ô'=>'O',  'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O',  'Ù'=>'U',  'Ú'=>'U',
            'Û'=>'U',  'Ü'=>'U', 'Ý'=>'Y',  'Þ'=>'B',  'ß'=>'Ss', 'à'=>'a',  'á'=>'a', 'â'=>'a', 'ã'=>'a',  'ä'=>'a',
            'å'=>'a',  'æ'=>'a', 'ç'=>'c',  'è'=>'e',  'é'=>'e',  'ê'=>'e',  'ë'=>'e', 'ì'=>'i', 'í'=>'i',  'î'=>'i',
            'ï'=>'i',  'ð'=>'o', 'ñ'=>'n',  'Å'=>'n', 'ò'=>'o',  'ó'=>'o',  'ô'=>'o', 'õ'=>'o', 'ö'=>'o',  'ø'=>'o',  'ù'=>'u',
            'ú'=>'u',  'û'=>'u', 'ü'=>'u',  'ý'=>'y',  'ý'=>'y',  'þ'=>'b',  'ÿ'=>'y', 'ƒ'=>'f',
            'Ä'=>'a', 'î'=>'i', 'â'=>'a',  'È'=>'s', 'È'=>'t', 'Ä'=>'A', 'Î'=>'I', 'Â'=>'A', 'È'=>'S', 'È'=>'T',
        ];

        // Substitui acentuações e/ou caracteres especiais.
        $name = strtr($name, $name_decode);

        // Coloca o nome em maiúsculas.
        $name = Str::upper((string)$name);

        return $name;
    }

    /**
     * Valida cadastro.
     * @var array $data
     * 
     * @return bool
     */
    public static function validateAdd(array $data){
        $message = null;

        // ...

        // Desvio.
        if(!empty($message)):
            session()->flash('message', $message );
            session()->flash('color', 'danger');

            return false;
        endif;

        return true;
    }

    /**
     * Cadastra.
     * @var array $data
     * 
     * @return bool true
     */
    public static function add(array $data) : bool {
        // Nota Fiscal..
        $invoice = Invoice::where('key', $data['validatedData']['key'])->first();
 
        // Cadastra.
        foreach($data['validatedData']['CsvArray'] as $invoicecsv):
            Invoicecsv::create([
                'invoice_id' => $invoice->id,
                'code'       => $invoicecsv['code'],
                'reference'  => Invoicecsv::referenceEmpty($invoicecsv['reference']),
                'ean'        => Invoicecsv::eanEmpty($invoicecsv['ean']),
                'name'       => Invoicecsv::nameValidate($invoicecsv['name']),
                'cost'       => General::encodeFloat2($invoicecsv['cost']),
                'margin'     => General::encodeFloat2($invoicecsv['margin']),
                'value'      => General::encodeFloat2($invoicecsv['value']),
            ]);
        endforeach;

        return true;
    }

    /**
     * Executa dependências de cadastro.
     * @var array $data
     * 
     * @return bool true
     */
    public static function dependencyAdd(array $data) : bool {
        // ...

        return true;
    }
}
