<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Page;
use App\Models\Invoice;
use App\Models\Csv;

class InvoiceController extends Controller
{
    public $pageName;

    public function index(){
        /**
         * Nome da página - Configurável e único.
         * @var string $this->pageName.
         */
        $this->pageName = 'invoice';

        /**
         * Expulsa Usuário sem autorização de acesso à Página.
         */
        if (!Page::userAuthorized($this->pageName)) return redirect()->to('/home');

        /**
         * View.
         */
        return view('index', [
            'config' => [
                'name'  => $this->pageName,
                'title' => Page::getTitleByName($this->pageName), 
                'icon'  => Page::getIconByName($this->pageName), 
            ],
        ]);
    }

    public function priceZip(int $invoice_id)
    {
        $invoice = Invoice::find($invoice_id);

        if($invoice):
            $path_zip = public_path('/storage/zip/price/');
            $name_zip = Csv::where(['folder' => 'zip/price', 'reference_1' => $invoice_id])->orderBy('id', 'DESC')->first()->file;

            return response()->download($path_zip . $name_zip);
        else:
            session()->flash('message', 'Nenhum Arquivo ZIP de Preço para esta Nota Fiscal.');
            session()->flash('color', 'danger');

            return redirect()->to('/invoice');
        endif;
    }
}
