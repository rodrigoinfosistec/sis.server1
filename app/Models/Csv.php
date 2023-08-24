<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use File;
use ZipArchive;

class Csv extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'csvs';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'user_id',

        'folder',
        'file',

        'reference_1',
        'reference_2',
        'reference_3',
        'reference_4',
        'reference_5',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function user(){return $this->belongsTo(User::class);}

    /**
     * Invoice Price Generate
     * @var array $data
     * 
     * @return bool true
     */
    public static function invoicePriceGenerate(array $data) : bool {
        dd($data);
        // Gera o arquivo CSV.
        $pdf = PDF::loadView('components.invoice.pdf-price', [
            'user'                 => auth()->user()->name,
            'title'                => 'Preços',
            'date'                 => date('d/m/Y H:i:s'),
            'invoice_id'           => $data['invoice_id'],
            'efiscos'              => Invoiceefisco::where('invoice_id', $data['invoice_id'])->get(),
            'efisco_icms'          => General::decodeFloat2(Invoiceefisco::where('invoice_id', $data['invoice_id'])->get()->sum('icms')),
            'efisco_value'         => General::decodeFloat2(Invoiceefisco::where('invoice_id', $data['invoice_id'])->get()->sum('value')),
            'efisco_value_invoice' => General::decodeFloat2(Invoiceefisco::where('invoice_id', $data['invoice_id'])->get()->sum('value_invoice')),
            'efisco_value_final'   => General::decodeFloat2(Invoiceefisco::where('invoice_id', $data['invoice_id'])->get()->sum('value_final')),
            'efisco_ipi_invoice'   => General::decodeFloat2(Invoiceefisco::where('invoice_id', $data['invoice_id'])->get()->sum('ipi_invoice')),
            'efisco_ipi_final'     => General::decodeFloat2(Invoiceefisco::where('invoice_id', $data['invoice_id'])->get()->sum('ipi_final')),
            'list'                 => $list = Invoiceitem::where('invoice_id', $data['invoice_id'])->orderBy('identifier', 'ASC')->get(), 
        ])->set_option('isPhpEnabled', true)->setPaper('A4', 'landscape');

        // Salva o arquivo PDF.
        File::makeDirectory($data['path'], $mode = 0777, true, true);
        $pdf->save($data['path'] . $data['file_name']);

        // Registra os dados do arquivo CSV.
        Csv::create([
            'user_id'     => auth()->user()->id,
            'folder'      => 'price',
            'file'        => $data['file_name'],
            'reference_1' => $data['invoice_id'],
        ]);

        return true;
    }
}
