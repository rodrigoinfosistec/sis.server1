<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

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
     * Valida cadastro.
     * @var array $data
     * 
     * @return <object, bool>
     */
    public static function validateAdd(array $data){
        $message = null;

        // Verifica se o usuário possui Empresa Vinculada.
        if(empty(auth()->user()->company_id)) $message = 'Usuário sem vínculo com alguma Empresa Própria.';

        // Salva o arquivo, caso seja um CSV.
        $CsvArray = Report::csvProduct($data);

        // Verifica se é um arquivo CSV.
        if (empty($CsvArray)) $message = 'Arquivo deve ser um csv de produtos.';

        // Desvio.
        if(!empty($message)):
            session()->flash('message', $message );
            session()->flash('color', 'danger');

            return false;
        endif;

        // Atribui retorno, caso aprovado nas validações anteriores.
        $valid['CsvArray']  = $CsvArray;

        return $valid;
    }

    /**
     * Cadastra.
     * @var array $data
     * 
     * @return bool true
     */
    public static function add(array $data) : bool {
        //Percorre todos os Produtos.
        foreach($data['validatedData']['csvArray'] as $key => $product):
            // Verifica se o Produto não está cadastrado.
            if(Product::where(['code' => $product['code'], 'company_id' => auth()->user()->company_id])->doesntExist()):
                // Cadastra.
                $product_id = Product::create([
                    'name' => Invoicecsv::nameValidate($product['name']),
                    'code' => $product['code'],
                    'reference' => !empty($product['reference']) ? $product['reference'] : null,
                    'ean' => !empty($product['ean']) ? $product['ean'] : null,
                    'cost' => General::encodeFloat($product['cost'], 7),
                    'margin' => General::encodeFloat($product['margin'], 7),
                    'value' => General::encodeFloat($product['value'], 7),
                    'company_id' => auth()->user()->company_id,
                ])->id;
            else:
                // Atualiza.
                $product_id = Product::where(['code' => $product['code'], 'company_id' => auth()->user()->company_id])->first()->id;
                Product::where(['code' => $product['code'], 'company_id' => auth()->user()->company_id])->update([
                    'name' => Invoicecsv::nameValidate($product['name']),
                    'reference' => !empty($product['reference']) ? $product['reference'] : null,
                    'ean' => !empty($product['ean']) ? $product['ean'] : null,
                    'cost' => General::encodeFloat($product['cost'], 7),
                    'margin' => General::encodeFloat($product['margin'], 7),
                    'value' => General::encodeFloat($product['value'], 7),
                ]);
            endif;

            // verifica se o Fornecedor já está associado a este Produto.
            if(Productprovider::where(['product_id' => $product_id, 'provider_id' => $data['validatedData']['provider_id']])->doesntExist()):
                // Associa o Produto ao Fornecedor.
                Productprovider::create([
                    'product_id' => $product_id,
                    'product_code' => (string)$product['code'],
                    'provider_id' => $data['validatedData']['provider_id'],
                ]);
            endif;
        endforeach;

        // Mensagem.
        $message = 'Produtos cadastrados/atualizados com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

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

    /**
     * Valida geração de relatório.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateGenerate(array $data) : bool {
        $message = null;

        // verifica se existe algum item retornado na pesquisa.
        if($list = Product::where([
                ['company_id', auth()->user()->company_id],
                [$data['filter'], 'like', '%'. $data['search'] . '%'],
            ])->doesntExist()):

            $message = 'Nenhum ítem selecionado.';
        endif;

        // Desvio.
        if(!empty($message)):
            session()->flash('message', $message );
            session()->flash('color', 'danger');

            return false;
        endif;

        return true;
    }

    /**
     * Gera relatório.
     * @var array $data
     * 
     * @return bool true
     */
    public static function generate(array $data) : bool {
        // Estende $data.
        $data['path'] = public_path('/storage/pdf/' . $data['config']['name'] . '/');
        $data['file_name'] = $data['config']['name'] . '_' . auth()->user()->id . '_' . Str::random(20) . '.pdf';

        // Gera PDF.
        Report::productGenerate($data);

        // Auditoria.
        Audit::productGenerate($data);

        // Mensagem.
        $message = 'Relatório PDF gerado com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }

    /**
     * Executa dependências de geração de relatório.
     * @var array $data
     * 
     * @return bool true
     */
    public static function dependencyGenerate(array $data) : bool {
        //...

        return true;
    }

}
