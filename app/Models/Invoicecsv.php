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
        $name = Str::upper((string)$name);

        // ... Validações aqui! ...

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
