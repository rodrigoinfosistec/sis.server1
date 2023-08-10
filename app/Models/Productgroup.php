<?php

namespace App\Models;

use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;

class Productgroup extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'productgroups';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'code',
        'origin',
        'name',

        'created_at',
        'updated_at',
    ];

    /**
     * Verifica se está vazio.
     * @var <null, int> $productgroup_id
     * 
     * @return array $productgroup
     */
    public static function validateNull($productgroup_id) : array {
        // inicializa array.
        $productgroup = [
            'productgroup_id' => null,
            'productgroup'    => null,
        ];
    
        // Verifica se productgroup_id está vazio.
        if(!empty($productgroup_id)):
            $productgroup = [
                'productgroup_id' => $productgroup_id,
                'productgroup'    => Productgroup::find($productgroup_id)->code . '-' . Productgroup::find($productgroup_id)->origin,
            ];
        endif;

        return $productgroup;
    }

    /**
     * Valida cadastro.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateAdd(array $data) : bool {
        $message = null;

        //Verifica se já existe a tupla [cade, origin]
        if(Productgroup::where(['code' => $data['validatedData']['code'], 'origin' => $data['validatedData']['origin']])->exists()):
            $message = $data['config']['title'] . ' ' . $data['validatedData']['code'] . '-' . $data['validatedData']['origin'] . ' já está cadastrado.';
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
     * Cadastra.
     * @var array $data
     * 
     * @return bool true
     */
    public static function add(array $data) : bool {
        // Cadastra.
        Productgroup::create([
            'code'   => $data['validatedData']['code'],
            'origin' => Str::upper($data['validatedData']['origin']),
            'name'   => Str::upper($data['validatedData']['name']),
        ]);

        // After.
        $after = Productgroup::where(['code' => $data['validatedData']['code'], 'origin' => $data['validatedData']['origin']])->first();

        // Auditoria.
        Audit::productgroupAdd($data, $after);

        // Mensagem.
        $message = $data['config']['title'] . ' ' . $after->code . '-' . $after->origin . ' cadastrado com sucesso.';
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
        //...

        return true;
    }

    /**
     * Valida atualização.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateEdit(array $data) : bool {
        $message = null;

        //Verifica se já existe a tupla [cade, origin]
        if(Productgroup::where(['code' => $data['validatedData']['code'], 'origin' => $data['validatedData']['origin']])->exists()):
            if(Productgroup::where(['code' => $data['validatedData']['code'], 'origin' => $data['validatedData']['origin']])->first()->id != $data['validatedData']['productgroup_id']):
                $message = $data['config']['title'] . ' ' . $data['validatedData']['code'] . '-' . $data['validatedData']['origin'] . ' já está cadastrado.';
            endif;
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
     * Atualiza.
     * @var array $data
     * 
     * @return bool true
     */
    public static function edit(array $data) : bool {
        // Before.
        $before = Productgroup::find($data['validatedData']['productgroup_id']);

        // Atualiza.
        Productgroup::find($data['validatedData']['productgroup_id'])->update([
            'code'   => $data['validatedData']['code'],
            'origin' => Str::upper($data['validatedData']['origin']),
            'name'   => Str::upper($data['validatedData']['name']),
        ]);

        // After.
        $after = Productgroup::find($data['validatedData']['productgroup_id']);

        // Auditoria.
        Audit::productgroupEdit($data, $before, $after);

        // Mensagem.
        $message = $data['config']['title'] . ' ' .  $after->code . '-' . $after->origin . ' atualizado com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }

    /**
     * Executa dependências de atualização.
     * @var array $data
     * 
     * @return bool true
     */
    public static function dependencyEdit(array $data) : bool {
        // ...

        return true;
    }

    /**
     * Valida exclusão.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateErase(array $data) : bool {
        $message = null;

        // eFisco.
        if($invoiceefisco = Invoiceefisco::where(['productgroup_id' => $data['validatedData']['productgroup_id']])->first()):
            $message = $data['config']['title'] . ' ' . Productgroup::find($data['validatedData']['productgroup_id'])->code . '-' . Productgroup::find($data['validatedData']['productgroup_id'])->orign . ' utilizado em eFisco de Nota Fiscal ' . $invoiceefisco->number . ' do Fornecedor ' . $invoiceefisco->provider->name  . '.';
        endif;

        // Item de Nota Fiscal.
        if($invoiceitem = Invoiceitem::where(['productgroup_id' => $data['validatedData']['productgroup_id']])->first()):
            $message = $data['config']['title'] . ' ' . Productgroup::find($data['validatedData']['productgroup_id'])->code . '-' . Productgroup::find($data['validatedData']['productgroup_id'])->orign . ' utilizado em Itens de Nota Fiscal ' . $invoiceefisco->number . ' do Fornecedor ' . $invoiceefisco->provider->name  . '.';
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
     * Executa dependências de exclusão.
     * @var array $data
     * 
     * @return bool true
     */
    public static function dependencyErase(array $data) : bool {
        // ...

        return true;
    }

    /**
     * Exclui.
     * @var array $data
     * 
     * @return bool true
     */
    public static function erase(array $data) : bool {
        // Exclui.
        Productgroup::find($data['validatedData']['productgroup_id'])->delete();

        // Auditoria.
        Audit::productgroupErase($data);

        // Mensagem.
        $message = $data['config']['title'] . ' ' .  $data['validatedData']['code'] . '-' . $data['validatedData']['origin'] . ' excluído com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

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
        if($list = Productgroup::where([
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
        $data['path']      = public_path('/storage/pdf/' . $data['config']['name'] . '/');
        $data['file_name'] = $data['config']['name'] . '_' . auth()->user()->id . '_' . Str::random(20) . '.pdf';

        // Gera o PDF.
        Report::productgroupGenerate($data);

        // Auditoria.
        Audit::productgroupGenerate($data);

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

    /**
     * Valida envio de e-mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateMail(array $data) : bool {
        $message = null;

        // Verifica conexão com a internet.
        if(checkdnsrr('google.com') < 1):
            $message = 'Sem conexão com a internet.';
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
     * Envia e-mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function mail(array $data) : bool {
        // Envia e-mail.
        Email::productgroupMail($data);

        // Auditoria.
        Audit::productgroupMail($data);

        // Mensagem.
        $message = 'E-mail para ' . $data['validatedData']['mail'] . ' enviado com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }

    /**
     * Executa dependências de envio de e-mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function dependencyMail(array $data) : bool {
        //...

        return true;
    }
}
