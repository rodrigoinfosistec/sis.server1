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
        // Inicializa variáveis.
        $txt_price  = '';
        $txt_card   = '';
        $txt_retail = '';

        // Conteúdo dos CSV's.
        foreach(Invoiceitem::where('invoice_id', $data['invoice_id'])->get() as $key => $item):
            // Preço Final.
            $txt_price = $txt_price .
            '"' . $item->invoicecsv->code                                      . '";'.
            '"' . $item->invoicecsv->reference                                 . '";'.
            '"' . $item->invoicecsv->ean                                       . '";'.
            '"' . $item->invoicecsv->name                                      . '";'.
            '"' . General::decodeFloat2($item->invoicecsv->cost)   . '";'.
            '"' . General::decodeFloat2($item->invoicecsv->margin) . '";'.
            '"' . General::decodeFloat2($item->price)              . '";'.
        "\n";

        // Preço Cartão.
        $txt_card = $txt_card .
            '"' . $item->invoicecsv->code                                      . '";'.
            '"' . $item->invoicecsv->reference                                 . '";'.
            '"' . $item->invoicecsv->ean                                       . '";'.
            '"' . $item->invoicecsv->name                                      . '";'.
            '"' . General::decodeFloat2($item->invoicecsv->cost)   . '";'.
            '"' . General::decodeFloat2($item->invoicecsv->margin) . '";'.
            '"' . General::decodeFloat2($item->card)              . '";'.
        "\n";

        // Preço Varejo.
        $txt_retail = $txt_retail .
            '"' . $item->invoicecsv->code                                      . '";'.
            '"' . $item->invoicecsv->reference                                 . '";'.
            '"' . $item->invoicecsv->ean                                       . '";'.
            '"' . $item->invoicecsv->name                                      . '";'.
            '"' . General::decodeFloat2($item->invoicecsv->cost)   . '";'.
            '"' . General::decodeFloat2($item->invoicecsv->margin) . '";'.
            '"' . General::decodeFloat2($item->retail)              . '";'.
        "\n";
        endforeach;

        // Concede permissão para gravar no diretório.
        File::makeDirectory($data['path_csv'], $mode = 0777, true, true);

        // Gera Arquivo CSV com Preço Final.
        $file_price = fopen($data['path_csv'] . $data['file_name_price'], "a");
        fwrite($file_price, "Cod Produto;Referencia;Cod. Barras;Descricao;Custo;Margem Lucro;Vl. Unitario\n" . $txt_price);
        fclose($file_price);
        
        // Gera Arquivo CSV com Preço Cartão.
        $file_card = fopen($data['path_csv'] . $data['file_name_card'], "a");
        fwrite($file_card, "Cod Produto;Referencia;Cod. Barras;Descricao;Custo;Margem Lucro;Vl. Unitario\n" . $txt_card);
        fclose($file_card);
        
        // Gera Arquivo CSV com Preço Varejo.
        $file_retail = fopen($data['path_csv'] . $data['file_name_retail'], "a");
        fwrite($file_retail, "Cod Produto;Referencia;Cod. Barras;Descricao;Custo;Margem Lucro;Vl. Unitario\n" . $txt_retail);
        fclose($file_retail);

        // Concede permissão para gravar no diretório.
        File::makeDirectory($data['path_zip'], $mode = 0777, true, true);

        // Registra os dados do arquivo CSV.
        Csv::create([
            'user_id'     => auth()->user()->id,
            'folder'      => 'zip/price',
            'file'        => $data['file_name_zip'],
            'reference_1' => $data['invoice_id'],
        ]);

        // Gera Arquivo ZIP contendo os Arquivos de Preço CSV.
        //dd(ZipArchive::open($data['path_zip'] . $data['file_name_zip'], ZipArchive::CREATE));

        $zip = new ZipArchive();
        if($zip->open($data['path_zip'] . $data['file_name_zip'], ZipArchive::CREATE)  === true){
            $zip->addFile($data['path_csv'] . $data['file_name_price'] , $data['file_name_price']);
            $zip->addFile($data['path_csv'] . $data['file_name_card']  , $data['file_name_card']);
            $zip->addFile($data['path_csv'] . $data['file_name_retail'], $data['file_name_retail']);
            $zip->close();
        }

        return true;
    }
}
