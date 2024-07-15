<?php

namespace App\Http\Livewire;

use Illuminate\Support\Str;

use App\Models\General;
use App\Models\Report;

use App\Models\Deposit;
use App\Models\Depositinput;
use App\Models\Depositinputproduct;

use App\Models\Provider;
use App\Models\Company;
use App\Models\User;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class DepositinputShow extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshChildren' => 'refreshMe'];

    public function refreshMe(){}

    public $config;

    public $search = '';
    public $filter = 'deposit_name';

    public $report_id;
    public $mail;
    public $comment;

    public $depositinput_id;
    public $deposit_name;
    public $deposit_id;
    public $provider_id;
    public $provider_name;
    public $company_id;
    public $company_name;
    public $user_id;
    public $user_name;
    public $key;
    public $number;
    public $range;
    public $total;
    public $issue;
    public $created;

    public $xml;

    public function render()
    {
        return view('livewire.depositinput-show');
    }
}
