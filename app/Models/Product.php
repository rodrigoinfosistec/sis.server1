<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'products';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'name',

        'code',
        'reference',
        'ean',

        'cost',
        'margin',
        'value',

        'company_id',
        'productgroup_id',
        'productmeasure_id',

        'status',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function company(){return $this->belongsTo(Company::class);}
    public function productgroup(){return $this->belongsTo(Productgroup::class);}
    public function productmeasure(){return $this->belongsTo(Productmeasure::class);}

    /**
     * Valida Arquivo CSV.
     * @var array $data
     * 
     * @return @return <array, faslse> $CSV
     */
    public static function validateFileCsv(array $data){
        // Inicializa variáveis.
        $file_name = $data['validatedData']['file_name'];
        $path = public_path('/storage/product/csv/');
        $csvArray = false;

        // Salva o arquivo CSV.
        File::makeDirectory($path, $mode = 0777, true, true);
        $data['validatedData']['csv']->storeAs('public/product/csv/',  $file_name);

        // Instancia dados do CSV.
        @$data = file($path .  $file_name); 

        // Verifica se é um CSV.
        if($data):
            // Verifica se é um CSV válido.
            if($data[0][0] == 'C' && $data[0][12] == 'R' && $data[0][23] == 'C'):
                // Percorre as linhas do arquivo CSV.
                foreach($data as $key => $line):
                    // Desconsidera a linha de cabeçalho (primeira linha).
                    if($key != 0):
                        // Separa dados em cada linha.
                        $l = explode(';', $line);

                        // Verifica se o arquivo possui as aspas "".
                        if($l[0][0] == '"'):
                            $l[0] = str_replace('"', '', $l[0]);
                            $l[1] = str_replace('"', '', $l[1]);
                            $l[2] = str_replace('"', '', $l[2]);
                            $l[3] = str_replace('"', '', $l[3]);
                            $l[4] = str_replace('"', '', $l[4]);
                            $l[5] = str_replace('"', '', $l[5]);
                            $l[6] = str_replace('"', '', $l[6]);
                        endif;

                        // Monta array CSV.
                        $csvArray[] = [
                            'identifier' => $key,
                            'code'       => $l[0],
                            'reference'  => $l[1],
                            'ean'        => $l[2],
                            'name'       => $l[3],
                            'cost'       => $l[4],
                            'margin'     => $l[5],
                            'value'      => $l[6],
                        ];
                    endif;
                endforeach;
            endif;
        endif;

        return  $csvArray;
    }

    /**
     * Valida Cadastro.
     * @var array $data
     * 
     * @return <object, bool>
     */
    public static function validateAdd(array $data){
        // Inicializa variáveis.
        $message = null;
        $valid['csvArray'] = false;
        $file_name = $data['validatedData']['file_name'];
        $path = public_path('/storage/product/csv/');

        // verifica se existe CSV.
        if($data['validatedData']['csv']):
            // Salva CSV.
            $csvArray = Product::validateFileCsv($data);

            // Verifica se o CSV é válido.
            if($csvArray):
                // Inicializa array CSV.
                $valid['csvArray'] = $csvArray;
            else:
                // Mensagem.
                $message = 'Arquivo deve ser um CSV válido (Automação).';

                // Exclui o arquivo.
                @unlink($path . $file_name);
            endif;
        endif;

        // Atribui retorno negativo, caso reprovado em alguma das validações anteriores.
        if(!empty($message)):
            session()->flash('message', $message );
            session()->flash('color', 'danger');

            return false;
        endif;

        // Retorno positivo, caso aprovado nas validações anteriores.
        return $valid;
    }

}
