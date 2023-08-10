<?php

namespace App\Models;

use Illuminate\Support\Str;

use PDF;
use File;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'reports';

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
     * Usergroup Generate
     * @var array $data
     * 
     * @return bool true
     */
    public static function usergroupGenerate(array $data) : bool {
        // Gera o arquivo PDF.
        $pdf = PDF::loadView('components.' . $data['config']['name'] . '.pdf', [
            'user'  => auth()->user()->name,
            'title' => $data['config']['title'],
            'date'  => date('d/m/Y H:i:s'),
            'list'  => $list = Usergroup::where([
                            [$data['filter'], 'like', '%'. $data['search'] . '%'],
                            ['name', '!=', 'DEVELOPMENT'],
                        ])->orderBy('name', 'ASC')->get(), 
        ])->set_option('isPhpEnabled', true)->setPaper('A4', 'portrait');

        // Salva o arquivo PDF.
        File::makeDirectory($data['path'], $mode = 0777, true, true);
        $pdf->save($data['path'] . $data['file_name']);

        // Registra os dados do arquivo PDF.
        Report::create([
            'user_id' => auth()->user()->id,
            'folder'  => $data['config']['name'],
            'file'    => $data['file_name']
        ]);

        return true;
    }

    /**
     * User Generate
     * @var array $data
     * 
     * @return bool true
     */
    public static function userGenerate(array $data) : bool {
        // Gera o arquivo PDF.
        $pdf = PDF::loadView('components.' . $data['config']['name'] . '.pdf', [
            'user'  => auth()->user()->name,
            'title' => $data['config']['title'],
            'date'  => date('d/m/Y H:i:s'),
            'list'  => $list = User::where([
                            [$data['filter'], 'like', '%'. $data['search'] . '%'],
                            ['name', '!=', 'MASTER'],
                        ])->orderBy('name', 'ASC')->get(), 
        ])->set_option('isPhpEnabled', true)->setPaper('A4', 'portrait');

        // Salva o arquivo PDF.
        File::makeDirectory($data['path'], $mode = 0777, true, true);
        $pdf->save($data['path'] . $data['file_name']);

        // Registra os dados do arquivo PDF.
        Report::create([
            'user_id' => auth()->user()->id,
            'folder'  => $data['config']['name'],
            'file'    => $data['file_name']
        ]);

        return true;
    }

    /**
     * Audit Generate
     * @var array $data
     * 
     * @return bool true
     */
    public static function auditGenerate(array $data) : bool {
        // Gera o arquivo PDF.
        $pdf = PDF::loadView('components.' . $data['config']['name'] . '.pdf', [
            'user'  => auth()->user()->name,
            'title' => $data['config']['title'],
            'date'  => date('d/m/Y H:i:s'),
            'list'  => $list = Audit::where([
                            [$data['filter'], 'like', '%'. $data['search'] . '%'],
                            ['user_name', '!=', 'MASTER'],
                        ])->orderBy('id', 'DESC')->get(), 
        ])->set_option('isPhpEnabled', true)->setPaper('A4', 'portrait');

        // Salva o arquivo PDF.
        File::makeDirectory($data['path'], $mode = 0777, true, true);
        $pdf->save($data['path'] . $data['file_name']);

        // Registra os dados do arquivo PDF.
        Report::create([
            'user_id' => auth()->user()->id,
            'folder'  => $data['config']['name'],
            'file'    => $data['file_name']
        ]);

        return true;
    }

    /**
     * Company Generate
     * @var array $data
     * 
     * @return bool true
     */
    public static function companyGenerate(array $data) : bool {
        // Gera o arquivo PDF.
        $pdf = PDF::loadView('components.' . $data['config']['name'] . '.pdf', [
            'user'  => auth()->user()->name,
            'title' => $data['config']['title'],
            'date'  => date('d/m/Y H:i:s'),
            'list'  => $list = Company::where([
                            [$data['filter'], 'like', '%'. $data['search'] . '%'],
                        ])->orderBy('name', 'ASC')->get(), 
        ])->set_option('isPhpEnabled', true)->setPaper('A4', 'portrait');

        // Salva o arquivo PDF.
        File::makeDirectory($data['path'], $mode = 0777, true, true);
        $pdf->save($data['path'] . $data['file_name']);

        // Registra os dados do arquivo PDF.
        Report::create([
            'user_id' => auth()->user()->id,
            'folder'  => $data['config']['name'],
            'file'    => $data['file_name']
        ]);

        return true;
    }

    /**
     * Company Xml
     * @var array $data
     * 
     * @return <object, null> $xml 
     */
    public static function xmlCompany(array $data){
        // Salva o arquivo xml.
        $file_name = $data['config']['name'] . '_' . auth()->user()->id . '_' . Str::random(20) . '.xml';
        $path      = public_path('/storage/xml/' . $data['config']['name'] . '/');
        File::makeDirectory($path, $mode = 0777, true, true);
        $data['validatedData']['xml']->storeAs('public/xml/' . $data['config']['name'] . '/', $file_name);

        // Instancia o objeto Xml.
        $xmlFile    = file_get_contents($path . $file_name);
        @$xmlObject = simplexml_load_string($xmlFile);

        // verifica se é um xml.
        if($xmlObject):
            // "Renomeia" o arquivo com a chave do xml.
            $data['validatedData']['xml']->storeAs('public/xml/' . $data['config']['name'] . '/', $xmlObject->protNFe->infProt->chNFe . '.xml');
            unlink($path . $file_name);

            // Areibui à variável.
            $xml = $xmlObject;
        else:
            // Exclui o arquivo.
            unlink($path . $file_name);

            // Areibui à variável.
            $xml = null;
        endif;

        return  $xml;
    }

    /**
     * Company Txt
     * @var array $data
     * 
     * @return <object, null> $txt
     */
    public static function txtCompany(array $data){
        // Salva o arquivo txt.
        $file_name = $data['config']['name'] . '_' . auth()->user()->id . '_' . Str::random(20) . '.txt';
        $path = public_path('/storage/txt/' . $data['config']['name'] . '/');
        File::makeDirectory($path, $mode = 0777, true, true);
        $data['validatedData']['txt']->storeAs('public/txt/' . $data['config']['name'] . '/', $file_name);

        // Instancia dados do txt.
        $data = file($path . $file_name); 

        // Verifica se é um txt de empregador.
        if($data[0][0] == '2' && $data[0][3] == ']'):
            // Separa dados.
            $l = explode(']', $data[0]);

            // Monta o array.
            $txtArray = [
                'cnpj'     => $l[1],
                'name'     => $l[3],
                'nickname' => '',
                'path'     => $path . $file_name,
            ];

            // Atribui à variável.
            $txt = $txtArray;
        else:
            // Exclui o arquivo.
            unlink($path . $file_name);

            // Atribui à variável.
            $txt = null;
        endif;

        return  $txt;
    }

    /**
     * Provider Generate
     * @var array $data
     * 
     * @return bool true
     */
    public static function providerGenerate(array $data) : bool {
        // Gera o arquivo PDF.
        $pdf = PDF::loadView('components.' . $data['config']['name'] . '.pdf', [
            'user'  => auth()->user()->name,
            'title' => $data['config']['title'],
            'date'  => date('d/m/Y H:i:s'),
            'list'  => $list = Provider::where([
                            [$data['filter'], 'like', '%'. $data['search'] . '%'],
                        ])->orderBy('name', 'ASC')->get(), 
        ])->set_option('isPhpEnabled', true)->setPaper('A4', 'portrait');

        // Salva o arquivo PDF.
        File::makeDirectory($data['path'], $mode = 0777, true, true);
        $pdf->save($data['path'] . $data['file_name']);

        // Registra os dados do arquivo PDF.
        Report::create([
            'user_id' => auth()->user()->id,
            'folder'  => $data['config']['name'],
            'file'    => $data['file_name']
        ]);

        return true;
    }

    /**
     * Provider Xml
     * @var array $data
     * 
     * @return <object, null> $xml 
     */
    public static function xmlProvider(array $data){
        // Salva o arquivo xml.
        $file_name = $data['config']['name'] . '_' . auth()->user()->id . '_' . Str::random(20) . '.xml';
        $path      = public_path('/storage/xml/' . $data['config']['name'] . '/');
        File::makeDirectory($path, $mode = 0777, true, true);
        $data['validatedData']['xml']->storeAs('public/xml/' . $data['config']['name'] . '/', $file_name);

        // Instancia o objeto Xml.
        $xmlFile    = file_get_contents($path . $file_name);
        @$xmlObject = simplexml_load_string($xmlFile);

        // verifica se é um xml.
        if($xmlObject):
            // "Renomeia" o arquivo com a chave do xml.
            $data['validatedData']['xml']->storeAs('public/xml/' . $data['config']['name'] . '/', $xmlObject->protNFe->infProt->chNFe . '.xml');

            // Exclui o arquivo.
            unlink($path . $file_name);

            // Atribui à variável.
            $xml = $xmlObject;
        else:
            // Exclui o arquivo.
            unlink($path . $file_name);

            // Atribui à variável.
            $xml = null;
        endif;

        return  $xml;
    }

    /**
     * Productgroup Generate
     * @var array $data
     * 
     * @return bool true
     */
    public static function productgroupGenerate(array $data) : bool {
        // Gera o arquivo PDF.
        $pdf = PDF::loadView('components.' . $data['config']['name'] . '.pdf', [
            'user'  => auth()->user()->name,
            'title' => $data['config']['title'],
            'date'  => date('d/m/Y H:i:s'),
            'list'  => $list = Productgroup::where([
                            [$data['filter'], 'like', '%'. $data['search'] . '%'],
                        ])->orderBy('code', 'ASC')->get(), 
        ])->set_option('isPhpEnabled', true)->setPaper('A4', 'portrait');

        // Salva o arquivo PDF.
        File::makeDirectory($data['path'], $mode = 0777, true, true);
        $pdf->save($data['path'] . $data['file_name']);

        // Registra os dados do arquivo PDF.
        Report::create([
            'user_id' => auth()->user()->id,
            'folder'  => $data['config']['name'],
            'file'    => $data['file_name']
        ]);

        return true;
    }

}
