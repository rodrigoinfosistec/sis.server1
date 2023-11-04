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

    /**
     * Invoice Xml
     * @var array $data
     * 
     * @return <object, null> $xml 
     */
    public static function xmlInvoice(array $data){
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
     * Invoice Csv
     * @var array $data
     * 
     * @return <object, null> $csv
     */
    public static function csvInvoice(array $data){
        // Salva o arquivo csv.
        $file_name = $data['validatedData']['chNFe'] . '.csv';
        $path      = public_path('/storage/csv/' . $data['config']['name'] . '/');
        File::makeDirectory($path, $mode = 0777, true, true);
        $data['validatedData']['csv']->storeAs('public/csv/' . $data['config']['name'] . '/', $file_name);

        // Instancia dados do csv.
        $data = file($path . $file_name); 

        // Verifica se é um csv de produtos.
        if($data[0][0] == 'C' && $data[0][12] == 'R' && $data[0][23] == 'C'):
            // Percorre as linhas do arquivo csv.
            foreach($data as $key => $line):
                // Desconsidera a linha de cabeçalho (promeira linha).
                if($key != 0):
                    // Separa dados em cada linha.
                    $l = explode(';', $line);

                    // Monta array csv.
                    $CsvArray[] = [
                        'identifier' => $key,
                        'code'       => str_replace('"', '', $l[0]),
                        'reference'  => str_replace('"', '', $l[1]),
                        'ean'        => str_replace('"', '', $l[2]),
                        'name'       => str_replace('"', '', $l[3]),
                        'cost'       => str_replace('"', '', $l[4]),
                        'margin'     => str_replace('"', '', $l[5]),
                        'value'      => str_replace('"', '', $l[6]),
                    ];
                endif;
            endforeach;

            // Atribui à variável.
            $csv = $CsvArray;
        else:
            // Exclui o arquivo.
            unlink($path . $file_name);

            // Atribui à variável.
            $csv = null;
        endif;

        return  $csv;
    }

    /**
     * Invoice Generate
     * @var array $data
     * 
     * @return bool true
     */
    public static function invoiceGenerate(array $data) : bool {
        // Gera o arquivo PDF.
        $pdf = PDF::loadView('components.' . $data['config']['name'] . '.pdf', [
            'user'  => auth()->user()->name,
            'title' => $data['config']['title'],
            'date'  => date('d/m/Y H:i:s'),
            'list'  => $list = Invoice::where([
                            [$data['filter'], 'like', '%'. $data['search'] . '%'],
                            ['company_id', Auth()->user()->company_id],
                        ])->orderBy('id', 'DESC')->get(), 
        ])->set_option('isPhpEnabled', true)->setPaper('A4', 'landscape');

        // Salva o arquivo PDF.
        File::makeDirectory($data['path'], $mode = 0777, true, true);
        $pdf->save($data['path'] . $data['file_name']);

        // Registra os dados do arquivo PDF.
        Report::create([
            'user_id'     => auth()->user()->id,
            'folder'      => $data['config']['name'],
            'file'        => $data['file_name'],
            'reference_1' => Auth()->user()->company_id,
        ]);

        return true;
    }

    /**
     * Invoice Price Generate
     * @var array $data
     * 
     * @return bool true
     */
    public static function invoicePriceGenerate(array $data) : bool {
        // Gera o arquivo PDF.
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

        // Registra os dados do arquivo PDF.
        Report::create([
            'user_id'     => auth()->user()->id,
            'folder'      => 'price',
            'file'        => $data['file_name'],
            'reference_1' => $data['invoice_id'],
        ]);

        return true;
    }

    /**
     * Holiday Generate
     * @var array $data
     * 
     * @return bool true
     */
    public static function holidayGenerate(array $data) : bool {
        // Gera o arquivo PDF.
        $pdf = PDF::loadView('components.' . $data['config']['name'] . '.pdf', [
            'user'  => auth()->user()->name,
            'title' => $data['config']['title'],
            'date'  => date('d/m/Y H:i:s'),
            'list'  => $list = Holiday::where([
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
     * Employee Generate
     * @var array $data
     * 
     * @return bool true
     */
    public static function employeeGenerate(array $data) : bool {
        // Gera o arquivo PDF.
        $pdf = PDF::loadView('components.' . $data['config']['name'] . '.pdf', [
            'user'  => auth()->user()->name,
            'title' => $data['config']['title'],
            'date'  => date('d/m/Y H:i:s'),
            'list'  => $list = Employee::where([
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
     * Employee Txt
     * @var array $data
     * 
     * @return <object, null> $txt
     */
    public static function txtEmployee(array $data){
        // Salva o arquivo txt.
        $file_name = $data['config']['name'] . '_' . auth()->user()->id . '_' . Str::random(20) . '.txt';
        $path = public_path('/storage/txt/' . $data['config']['name'] . '/');
        File::makeDirectory($path, $mode = 0777, true, true);
        $data['validatedData']['txt']->storeAs('public/txt/' . $data['config']['name'] . '/', $file_name);

        // Instancia dados do txt.
        $data = file($path . $file_name);

        // Verifica se é um txt de empregador.
        if($data[0][4] == 'I' && $data[0][5] == '['):
            // Percorre todas as linhas do arquivo txt.
            foreach($data as $key => $line):
                // Verifica se é uma linha com informação de funcionário.
                if($line[4] == 'I' && $line[5] == '['):
                    // Separa dados.
                    $l = explode('[', $line);

                    // Monta o array.
                    $txtArray[$key] = [
                        'pis'  => $l[1],
                        'name' => $l[2],
                        'path' => $path . $file_name,
                    ];
                endif;
            endforeach;

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
     * Employee Vacation Generate
     * @var array $data
     * 
     * @return bool true
     */
    public static function employeevacationGenerate(array $data) : bool {
        // Gera o arquivo PDF.
        $pdf = PDF::loadView('components.' . $data['config']['name'] . '.pdf', [
            'user'  => auth()->user()->name,
            'title' => $data['config']['title'],
            'date'  => date('d/m/Y H:i:s'),
            'list'  => $list = Employeevacation::where([
                            [$data['filter'], 'like', '%'. $data['search'] . '%'],
                        ])->orderBy('date_start', 'DESC')->get(),
        ])->set_option('isPhpEnabled', true)->setPaper('A4', 'portrait');

        // Salva o arquivo PDF.
        File::makeDirectory($data['path'], $mode = 0777, true, true);
        $pdf->save($data['path'] . $data['file_name']);

        // Registra os dados do arquivo PDF.
        Report::create([
            'user_id' => auth()->user()->id,
            'folder'  => $data['config']['name'],
            'file'    => $data['file_name'],
        ]);

        return true;
    }

    /**
     * Employee Attest Generate
     * @var array $data
     * 
     * @return bool true
     */
    public static function employeeattestGenerate(array $data) : bool {
        // Gera o arquivo PDF.
        $pdf = PDF::loadView('components.' . $data['config']['name'] . '.pdf', [
            'user'  => auth()->user()->name,
            'title' => $data['config']['title'],
            'date'  => date('d/m/Y H:i:s'),
            'list'  => $list = Employeeattest::where([
                            [$data['filter'], 'like', '%'. $data['search'] . '%'],
                        ])->orderBy('date_start', 'DESC')->get(),
        ])->set_option('isPhpEnabled', true)->setPaper('A4', 'portrait');

        // Salva o arquivo PDF.
        File::makeDirectory($data['path'], $mode = 0777, true, true);
        $pdf->save($data['path'] . $data['file_name']);

        // Registra os dados do arquivo PDF.
        Report::create([
            'user_id' => auth()->user()->id,
            'folder'  => $data['config']['name'],
            'file'    => $data['file_name'],
        ]);

        return true;
    }

    /**
     * Employee License Generate
     * @var array $data
     * 
     * @return bool true
     */
    public static function employeelicenseGenerate(array $data) : bool {
        // Gera o arquivo PDF.
        $pdf = PDF::loadView('components.' . $data['config']['name'] . '.pdf', [
            'user'  => auth()->user()->name,
            'title' => $data['config']['title'],
            'date'  => date('d/m/Y H:i:s'),
            'list'  => $list = EmployeeLicense::where([
                            [$data['filter'], 'like', '%'. $data['search'] . '%'],
                        ])->orderBy('date_start', 'DESC')->get(),
        ])->set_option('isPhpEnabled', true)->setPaper('A4', 'portrait');

        // Salva o arquivo PDF.
        File::makeDirectory($data['path'], $mode = 0777, true, true);
        $pdf->save($data['path'] . $data['file_name']);

        // Registra os dados do arquivo PDF.
        Report::create([
            'user_id' => auth()->user()->id,
            'folder'  => $data['config']['name'],
            'file'    => $data['file_name'],
        ]);

        return true;
    }

    /**
     * Employee Absence Generate
     * @var array $data
     * 
     * @return bool true
     */
    public static function employeeabsenceGenerate(array $data) : bool {
        // Gera o arquivo PDF.
        $pdf = PDF::loadView('components.' . $data['config']['name'] . '.pdf', [
            'user'  => auth()->user()->name,
            'title' => $data['config']['title'],
            'date'  => date('d/m/Y H:i:s'),
            'list'  => $list = Employeeabsence::where([
                            [$data['filter'], 'like', '%'. $data['search'] . '%'],
                        ])->orderBy('date_start', 'DESC')->get(),
        ])->set_option('isPhpEnabled', true)->setPaper('A4', 'portrait');

        // Salva o arquivo PDF.
        File::makeDirectory($data['path'], $mode = 0777, true, true);
        $pdf->save($data['path'] . $data['file_name']);

        // Registra os dados do arquivo PDF.
        Report::create([
            'user_id' => auth()->user()->id,
            'folder'  => $data['config']['name'],
            'file'    => $data['file_name'],
        ]);

        return true;
    }

    /**
     * Employee Allowance Generate
     * @var array $data
     * 
     * @return bool true
     */
    public static function employeeallowanceGenerate(array $data) : bool {
        // Gera o arquivo PDF.
        $pdf = PDF::loadView('components.' . $data['config']['name'] . '.pdf', [
            'user'  => auth()->user()->name,
            'title' => $data['config']['title'],
            'date'  => date('d/m/Y H:i:s'),
            'list'  => $list = Employeeallowance::where([
                            [$data['filter'], 'like', '%'. $data['search'] . '%'],
                        ])->orderBy('date', 'DESC')->get(),
        ])->set_option('isPhpEnabled', true)->setPaper('A4', 'portrait');

        // Salva o arquivo PDF.
        File::makeDirectory($data['path'], $mode = 0777, true, true);
        $pdf->save($data['path'] . $data['file_name']);

        // Registra os dados do arquivo PDF.
        Report::create([
            'user_id' => auth()->user()->id,
            'folder'  => $data['config']['name'],
            'file'    => $data['file_name'],
        ]);

        return true;
    }
    
    /**
     * Employee Easy Generate
     * @var array $data
     * 
     * @return bool true
     */
    public static function employeeeasyGenerate(array $data) : bool {
        // Gera o arquivo PDF.
        $pdf = PDF::loadView('components.' . $data['config']['name'] . '.pdf', [
            'user'  => auth()->user()->name,
            'title' => $data['config']['title'],
            'date'  => date('d/m/Y H:i:s'),
            'list'  => $list = Employeeeasy::where([
                            [$data['filter'], 'like', '%'. $data['search'] . '%'],
                        ])->orderBy('date', 'DESC')->get(),
        ])->set_option('isPhpEnabled', true)->setPaper('A4', 'portrait');

        // Salva o arquivo PDF.
        File::makeDirectory($data['path'], $mode = 0777, true, true);
        $pdf->save($data['path'] . $data['file_name']);

        // Registra os dados do arquivo PDF.
        Report::create([
            'user_id' => auth()->user()->id,
            'folder'  => $data['config']['name'],
            'file'    => $data['file_name'],
        ]);

        return true;
    }
    
    /**
     * Clock Generate
     * @var array $data
     * 
     * @return bool true
     */
    public static function clockGenerate(array $data) : bool {
        // Gera o arquivo PDF.
        $pdf = PDF::loadView('components.' . $data['config']['name'] . '.pdf', [
            'user'  => auth()->user()->name,
            'title' => $data['config']['title'],
            'date'  => date('d/m/Y H:i:s'),
            'list'  => $list = Clock::where([
                            [$data['filter'], 'like', '%'. $data['search'] . '%'],
                            ['company_id', Auth()->user()->company_id],
                        ])->orderBy('id', 'DESC')->get(),

        ])->set_option('isPhpEnabled', true)->setPaper('A4', 'portrait');

        // Salva o arquivo PDF.
        File::makeDirectory($data['path'], $mode = 0777, true, true);
        $pdf->save($data['path'] . $data['file_name']);

        // Registra os dados do arquivo PDF.
        Report::create([
            'user_id'     => auth()->user()->id,
            'folder'      => $data['config']['name'],
            'file'        => $data['file_name'],
            'reference_3' => Auth()->user()->company_id,

        ]);

        return true;
    }

    /**
     * Clock Txt
     * @var array $data
     * 
     * @return <object, null> $txt
     */
    public static function txtClock(array $data){
        // Salva o arquivo txt.
        $file_name = $data['config']['name'] . '_' . auth()->user()->id . '_' . Str::random(20) . '.txt';
        $path = public_path('/storage/txt/' . $data['config']['name'] . '/');
        File::makeDirectory($path, $mode = 0777, true, true);
        $data['validatedData']['txt']->storeAs('public/txt/' . $data['config']['name'] . '/', $file_name);

        // Instancia dados do txt.
        $file = file($path . $file_name);

        // Verifica se é um txt de ponto.
        if($file[0][0] == '0' && $file[0][1] == '0'):
            // Verifica se o ponto é da mesma empresa.
            if(Company::where('cnpj', Company::encodeCnpj($file[0][11].$file[0][12].$file[0][13].$file[0][14].$file[0][15].$file[0][16].$file[0][17].$file[0][18].$file[0][19].$file[0][20].$file[0][21].$file[0][22].$file[0][23].$file[0][24]))->exists()):
                 // Inicializa array compacto.
                 $txtArrayCompact = [];

                // Percorre todas as linhas do arquivo.
                foreach($file as $key => $line):
                    // Verifica se é uma linha de evento de ponto de funcionário.
                    if($line[9] == '3'):
                        // Resgata os eventos dentro do período.
                        $date = $line[14].$line[15].$line[16].$line[17].'-'.$line[12].$line[13].'-'.$line[10].$line[11];
                        if($date >= $data['validatedData']['start'] && $date <= $data['validatedData']['end']):
                            $txtArrayCompact[] = $line;
                        endif;
                    endif;
                endforeach;

                // Verifica se existem eventos no período de ponto selecionado.
                if(count($txtArrayCompact) > 0):
                    // Percorre todos os eventos do array compacto.
                    foreach($txtArrayCompact as $key => $line):
                        // Resgata todos os funconátios com eventos no período selecionado.
                        $pis_all[Employee::encodePis($line[22].$line[23].$line[24].$line[25].$line[26].$line[27].$line[28].$line[29].$line[30].$line[31].$line[32].$line[33])] = Employee::encodePis($line[22].$line[23].$line[24].$line[25].$line[26].$line[27].$line[28].$line[29].$line[30].$line[31].$line[32].$line[33]);

                        $array_event[] = [
                            'pis'   => Employee::encodePis($line[22].$line[23].$line[24].$line[25].$line[26].$line[27].$line[28].$line[29].$line[30].$line[31].$line[32].$line[33]),
                            'event' => $line[0].$line[1].$line[2].$line[3].$line[4].$line[5].$line[6].$line[7].$line[8],
                            'date'  => $line[14].$line[15].$line[16].$line[17].'-'.$line[12].$line[13].'-'.$line[10].$line[11],
                            'time'  => $line[18].$line[19].':'.$line[20].$line[21],
                            'code'  => $line[34].$line[35].$line[36].$line[37],
                        ];
                    endforeach;

                    // Organiza pis.
                    foreach($pis_all as $key => $pis):
                        $array_pis[] = $pis;
                    endforeach;

                    // Atribui pis.
                    $txtArray['pis']   = $array_pis;
                    $txtArray['event'] = $array_event;

                    // Atribui à variável.
                    $txt = $txtArray;
                else:
                    // Exclui o arquivo.
                    unlink($path . $file_name);

                    // Atribui à variável.
                    $txt = null;
                endif;
            else:
                // Exclui o arquivo.
                unlink($path . $file_name);

                // Atribui à variável.
                $txt = null;
            endif;
        else:
            // Exclui o arquivo.
            unlink($path . $file_name);

            // Atribui à variável.
            $txt = null;
        endif;

        return  $txt;
    }

    /**
     * Clock Employee Generate
     * @var array $data
     * 
     * @return bool true
     */
    public static function clockemployeeGenerate(array $data) : bool {
        // Gera o arquivo PDF.
        $pdf = PDF::loadView('components.clock.pdf-employee', [
            'user'          => auth()->user()->name,
            'title'         => 'Ponto',
            'date'          => date('d/m/Y H:i:s'),
            'clockemployee' => Clockemployee::find($data['clockemployee_id']),
            'list'          => $list = Clockday::where(['clock_id' => $data['clock_id'], 'employee_id' => $data['employee_id']])->orderBy('date')->get(), 
        ])->set_option('isPhpEnabled', true)->setPaper('A4', 'portrait');

        // Salva o arquivo PDF.
        File::makeDirectory($data['path'], $mode = 0777, true, true);
        $pdf->save($data['path'] . $data['file_name']);

        // Registra os dados do arquivo PDF.
        Report::create([
            'user_id'     => auth()->user()->id,
            'folder'      => 'clockemployee',
            'file'        => $data['file_name'],
            'reference_1' => $data['clock_id'],
            'reference_2' => $data['employee_id'],
        ]);

        return true;
    }

    /**
     * Clock Employee Generate
     * @var array $data
     * 
     * @return bool true
     */
    public static function clockfundedGenerate(array $data) : bool {
        // Gera o arquivo PDF.
        $pdf = PDF::loadView('components.clock.pdf-funded', [
            'user'    => auth()->user()->name,
            'title'   => 'Ponto Consolidado',
            'date'    => date('d/m/Y H:i:s'),
            'company' => Company::find($data['validatedData']['company_id']),
            'clock'   => Clock::find($data['validatedData']['clock_id']),
            'list'    => $list = Clockemployee::where('clock_id', $data['validatedData']['clock_id'])->orderBy('employee_name')->get(), 
        ])->set_option('isPhpEnabled', true)->setPaper('A4', 'landascape');

        // Salva o arquivo PDF.
        File::makeDirectory($data['path'], $mode = 0777, true, true);
        $pdf->save($data['path'] . $data['file_name']);

        // Registra os dados do arquivo PDF.
        Report::create([
            'user_id'     => auth()->user()->id,
            'folder'      => 'clockfunded',
            'file'        => $data['file_name'],
            'reference_1' => $data['validatedData']['clock_id'],
        ]);

        return true;
    }

    /**
     * Clockbase Generate
     * @var array $data
     * 
     * @return bool true
     */
    public static function clockbaseGenerate(array $data) : bool {
        // Gera o arquivo PDF.
        $pdf = PDF::loadView('components.' . $data['config']['name'] . '.pdf', [
            'user'    => auth()->user()->name,
            'title'   => $data['config']['title'],
            'date'    => date('d/m/Y H:i:s'),
            'company' => Company::find(Auth()->user()->company_id),
            'list'    => $list = Employee::where([
                            [$data['filter'], 'like', '%'. $data['search'] . '%'],
                            ['company_id', Auth()->user()->company_id],
                            ['status', true],
                        ])->orderBy('name', 'ASC')->get(), 
        ])->set_option('isPhpEnabled', true)->setPaper('A4', 'portrait');

        // Salva o arquivo PDF.
        File::makeDirectory($data['path'], $mode = 0777, true, true);
        $pdf->save($data['path'] . $data['file_name']);

        // Registra os dados do arquivo PDF.
        Report::create([
            'user_id'     => auth()->user()->id,
            'folder'      => $data['config']['name'],
            'reference_1' => Auth()->user()->company_id,
            'file'        => $data['file_name'],
        ]);

        return true;
    }

    /**
     * Ponto Txt
     * @var array $data
     * 
     * @return <object, null> $txt
     */
    public static function txtPointevent(array $data){
        // Salva o arquivo txt.
        $file_name = $data['config']['name'] . '_' . auth()->user()->id . '_' . Str::random(20) . '.txt';
        $path = public_path('/storage/txt/' . $data['config']['name'] . '/');
        File::makeDirectory($path, $mode = 0777, true, true);
        $data['validatedData']['txt']->storeAs('public/txt/' . $data['config']['name'] . '/', $file_name);

        // Instancia dados do txt.
        $file = file($path . $file_name);

        // Verifica se é um txt de ponto.
        if($file[0][0] == '0' && $file[0][1] == '0'):
            // Inicializa array compacto.
            $txtArrayCompact = [];
            // Percorre todas as linhas do arquivo.
            foreach($file as $key => $line):
                // Verifica se é uma linha de evento de ponto de funcionário.
                if($line[9] == '3'):
                    // Define parâmetros.
                    $event = $line[0].$line[1].$line[2].$line[3].$line[4].$line[5].$line[6].$line[7].$line[8];
                    $date  = $line[14].$line[15].$line[16].$line[17].'-'.$line[12].$line[13].'-'.$line[10].$line[11];
                    $code  = $line[34].$line[35].$line[36].$line[37];
                    // Verifica se eventos já não está cadastrado.
                    if(Pointevent::where(['event' => $event, 'date' => $date, 'code' => $code])->doesntExist()):
                        $txtArrayCompact[] = [
                            'pis'   => Employee::encodePis($line[22].$line[23].$line[24].$line[25].$line[26].$line[27].$line[28].$line[29].$line[30].$line[31].$line[32].$line[33]),
                            'event' => $event,
                            'date'  => $date,
                            'time'  => $line[18].$line[19].':'.$line[20].$line[21],
                            'code'  => $code,
                        ];
                    endif;
                endif;
            endforeach;

            // Verifica se existe existe dados no $txtArrayCompact.
            if(count($txtArrayCompact) > 0):
                // Inicializa array $array_pis.
                $array_pis = [];
                // Percorre todas as linhas do arquivo.
                foreach($txtArrayCompact as $key => $line):
                    // Verifica se pis já foi salvo.
                    if(!in_array($line['pis'], $array_pis)):
                        // Salva pis existentes no arquivo, de forma única.
                        $array_pis[] = $line['pis'];
                    endif;
                endforeach;

                // Percorre todos os funcionários.
                foreach($array_pis as $key => $pis):
                    // Inicializa array $array_date.
                    $array_date[$pis] = [];
                    // Percorre todas as linhas do arquivo.
                    foreach($txtArrayCompact as $key => $line):
                        // Verifica se é o funcionário.
                        if($line['pis'] == $pis):
                            // Salva todas datas do pis existentes no arquivo, de forma única.
                            if(!in_array($line['date'], $array_date[$pis])):
                                $array_date[$pis][] = $line['date'];
                            endif;
                        endif;
                    endforeach;
                endforeach;

                // Percorre todos os funcionários.
                foreach($array_pis as $key_pis => $pis):
                    // Percorre todas as dastas do funcionário.
                    foreach($array_date[$pis] as $key_date => $date):
                        // Inicializa array $array_date.
                        $array_evento[$pis][$date] = [];
                        // Percorre todas as linhas do arquivo.
                        foreach($txtArrayCompact as $key => $line):
                            // Verifica se é o funcionário e a data.
                            if($line['pis'] == $pis && $line['date'] == $date):
                                // Salva os eventos do funcionário na data.
                                $array_evento[$pis][$date][] = [
                                    'pis'   => $line['pis'],
                                    'event' => $line['event'],
                                    'date'  => $line['date'],
                                    'time'  => $line['time'],
                                    'code'  => $line['code'],
                                    'type'  => 'clock',
                                ];
                            endif;
                        endforeach;
                    endforeach;
                endforeach;

                // Atribui à variável.
                $txt = $array_evento;
            else:
                // Exclui o arquivo.
                unlink($path . $file_name);

                // Atribui à variável.
                $txt = null;
            endif;
        else:
            // Exclui o arquivo.
            unlink($path . $file_name);

            // Atribui à variável.
            $txt = null;
        endif;

        return  $txt;
    }

}
