<?php

namespace App\Models;

use Illuminate\Support\Str;

use Mail;
use App\Mail\ReportMail;
use App\Mail\SuggestionMail;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    /**
     * E-mail Usergroup Mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function usergroupMail(array $data) : bool {
        // Envia e-mail.
        Mail::to($data['validatedData']['mail'])->send(new ReportMail([
            'pathToReport' => storage_path('app/public/pdf/' . $data['config']['name'] . '/' . Report::find($data['validatedData']['report_id'])->file),
            'subject'      => 'Relatório de ' . $data['config']['title'],
            'title'        => $data['config']['title'],
            'comment'      => $data['validatedData']['comment'],
        ]));

        return true;
    }

    /**
     * E-mail User Mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function userMail(array $data) : bool {
        // Envia e-mail.
        Mail::to($data['validatedData']['mail'])->send(new ReportMail([
            'pathToReport' => storage_path('app/public/pdf/' . $data['config']['name'] . '/' . Report::find($data['validatedData']['report_id'])->file),
            'subject'      => 'Relatório de ' . $data['config']['title'],
            'title'        => $data['config']['title'],
            'comment'      => $data['validatedData']['comment'],
        ]));

        return true;
    }
    
    /**
     * E-mail Audit Mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function auditMail(array $data) : bool {
        // Envia e-mail.
        Mail::to($data['validatedData']['mail'])->send(new ReportMail([
            'pathToReport' => storage_path('app/public/pdf/' . $data['config']['name'] . '/' . Report::find($data['validatedData']['report_id'])->file),
            'subject'      => 'Relatório de ' . $data['config']['title'],
            'title'        => $data['config']['title'],
            'comment'      => $data['validatedData']['comment'],
        ]));

        return true;
    }

    /**
     * E-mail Company Mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function companyMail(array $data) : bool {
        // Envia e-mail.
        Mail::to($data['validatedData']['mail'])->send(new ReportMail([
            'pathToReport' => storage_path('app/public/pdf/' . $data['config']['name'] . '/' . Report::find($data['validatedData']['report_id'])->file),
            'subject'      => 'Relatório de ' . $data['config']['title'],
            'title'        => $data['config']['title'],
            'comment'      => $data['validatedData']['comment'],
        ]));

        return true;
    }

    /**
     * E-mail Provider Mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function providerMail(array $data) : bool {
        // Envia e-mail.
        Mail::to($data['validatedData']['mail'])->send(new ReportMail([
            'pathToReport' => storage_path('app/public/pdf/' . $data['config']['name'] . '/' . Report::find($data['validatedData']['report_id'])->file),
            'subject'      => 'Relatório de ' . $data['config']['title'],
            'title'        => $data['config']['title'],
            'comment'      => $data['validatedData']['comment'],
        ]));

        return true;
    }

    /**
     * E-mail Productgroup Mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function productgroupMail(array $data) : bool {
        // Envia e-mail.
        Mail::to($data['validatedData']['mail'])->send(new ReportMail([
            'pathToReport' => storage_path('app/public/pdf/' . $data['config']['name'] . '/' . Report::find($data['validatedData']['report_id'])->file),
            'subject'      => 'Relatório de ' . $data['config']['title'],
            'title'        => $data['config']['title'],
            'comment'      => $data['validatedData']['comment'],
        ]));

        return true;
    }

    /**
     * E-mail Invoice Mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function invoiceMail(array $data) : bool {
        // Envia e-mail.
        Mail::to($data['validatedData']['mail'])->send(new ReportMail([
            'pathToReport' => storage_path('app/public/pdf/' . $data['config']['name'] . '/' . Report::find($data['validatedData']['report_id'])->file),
            'subject'      => 'Relatório de ' . $data['config']['title'],
            'title'        => $data['config']['title'],
            'comment'      => $data['validatedData']['comment'],
        ]));

        return true;
    }

    /**
     * E-mail Invoice Mail Price.
     * @var array $data
     * 
     * @return bool true
     */
    public static function invoiceMailPrice(array $data) : bool {
        // Envia e-mail.
        Mail::to($data['validatedData']['mail'])->send(new ReportMail([
            'pathToReport' => storage_path('app/public/pdf/price/' . Report::find($data['validatedData']['report_id'])->file),
            'subject'      => 'Relatório de Preço',
            'title'        => $data['config']['title'],
            'comment'      => $data['validatedData']['comment'],
        ]));

        return true;
    }

    /**
     * E-mail Holiday Mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function holidayMail(array $data) : bool {
        // Envia e-mail.
        Mail::to($data['validatedData']['mail'])->send(new ReportMail([
            'pathToReport' => storage_path('app/public/pdf/' . $data['config']['name'] . '/' . Report::find($data['validatedData']['report_id'])->file),
            'subject'      => 'Relatório de ' . $data['config']['title'],
            'title'        => $data['config']['title'],
            'comment'      => $data['validatedData']['comment'],
        ]));

        return true;
    }

    /**
     * E-mail Employee Mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function employeeMail(array $data) : bool {
        // Envia e-mail.
        Mail::to($data['validatedData']['mail'])->send(new ReportMail([
            'pathToReport' => storage_path('app/public/pdf/' . $data['config']['name'] . '/' . Report::find($data['validatedData']['report_id'])->file),
            'subject'      => 'Relatório de ' . $data['config']['title'],
            'title'        => $data['config']['title'],
            'comment'      => $data['validatedData']['comment'],
        ]));

        return true;
    }
    
    /**
     * E-mail Employee Vacation Mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function employeevacationMail(array $data) : bool {
        // Envia e-mail.
        Mail::to($data['validatedData']['mail'])->send(new ReportMail([
            'pathToReport' => storage_path('app/public/pdf/' . $data['config']['name'] . '/' . Report::find($data['validatedData']['report_id'])->file),
            'subject'      => 'Relatório de ' . $data['config']['title'],
            'title'        => $data['config']['title'],
            'comment'      => $data['validatedData']['comment'],
        ]));

        return true;
    }

    /**
     * E-mail Employee Attest Mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function employeeattestMail(array $data) : bool {
        // Envia e-mail.
        Mail::to($data['validatedData']['mail'])->send(new ReportMail([
            'pathToReport' => storage_path('app/public/pdf/' . $data['config']['name'] . '/' . Report::find($data['validatedData']['report_id'])->file),
            'subject'      => 'Relatório de ' . $data['config']['title'],
            'title'        => $data['config']['title'],
            'comment'      => $data['validatedData']['comment'],
        ]));

        return true;
    }

    /**
     * E-mail Employee License Mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function employeelicenseMail(array $data) : bool {
        // Envia e-mail.
        Mail::to($data['validatedData']['mail'])->send(new ReportMail([
            'pathToReport' => storage_path('app/public/pdf/' . $data['config']['name'] . '/' . Report::find($data['validatedData']['report_id'])->file),
            'subject'      => 'Relatório de ' . $data['config']['title'],
            'title'        => $data['config']['title'],
            'comment'      => $data['validatedData']['comment'],
        ]));

        return true;
    }

    /**
     * E-mail Employee Absence Mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function employeeabsenceMail(array $data) : bool {
        // Envia e-mail.
        Mail::to($data['validatedData']['mail'])->send(new ReportMail([
            'pathToReport' => storage_path('app/public/pdf/' . $data['config']['name'] . '/' . Report::find($data['validatedData']['report_id'])->file),
            'subject'      => 'Relatório de ' . $data['config']['title'],
            'title'        => $data['config']['title'],
            'comment'      => $data['validatedData']['comment'],
        ]));

        return true;
    }

    /**
     * E-mail Employee Allowance Mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function employeeallowanceMail(array $data) : bool {
        // Envia e-mail.
        Mail::to($data['validatedData']['mail'])->send(new ReportMail([
            'pathToReport' => storage_path('app/public/pdf/' . $data['config']['name'] . '/' . Report::find($data['validatedData']['report_id'])->file),
            'subject'      => 'Relatório de ' . $data['config']['title'],
            'title'        => $data['config']['title'],
            'comment'      => $data['validatedData']['comment'],
        ]));

        return true;
    }

    /**
     * E-mail Employee Easy Mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function employeeeasyMail(array $data) : bool {
        // Envia e-mail.
        Mail::to($data['validatedData']['mail'])->send(new ReportMail([
            'pathToReport' => storage_path('app/public/pdf/' . $data['config']['name'] . '/' . Report::find($data['validatedData']['report_id'])->file),
            'subject'      => 'Relatório de ' . $data['config']['title'],
            'title'        => $data['config']['title'],
            'comment'      => $data['validatedData']['comment'],
        ]));

        return true;
    }
    
    /**
     * E-mail Clock.
     * @var array $data
     * 
     * @return bool true
     */
    public static function clockMail(array $data) : bool {
        // Envia e-mail.
        Mail::to($data['validatedData']['mail'])->send(new ReportMail([
            'pathToReport' => storage_path('app/public/pdf/' . $data['config']['name'] . '/' . Report::find($data['validatedData']['report_id'])->file),
            'subject'      => 'Relatório de ' . $data['config']['title'],
            'title'        => $data['config']['title'],
            'comment'      => $data['validatedData']['comment'],
        ]));

        return true;
    }

    /**
     * E-mail Clockemployee.
     * @var array $data
     * 
     * @return bool true
     */
    public static function clockemployeeMail(array $data) : bool {
        // Envia e-mail.
        Mail::to($data['validatedData']['mail'])->send(new ReportMail([
            'pathToReport' => storage_path('app/public/pdf/clockemployee/' . Report::find($data['validatedData']['report_id'])->file),
            'subject'      => 'Relatório de Ponto de Funcionário',
            'title'        => $data['config']['title'],
            'comment'      => $data['validatedData']['comment'],
        ]));

        return true;
    }
    
    /**
     * E-mail Clockfunded.
     * @var array $data
     * 
     * @return bool true
     */
    public static function clockfundedMail(array $data) : bool {
        // Envia e-mail.
        Mail::to($data['validatedData']['mail'])->send(new ReportMail([
            'pathToReport' => storage_path('app/public/pdf/clockfunded/' . Report::find($data['validatedData']['report_id'])->file),
            'subject'      => 'Relatório de Ponto Consolidado',
            'title'        => $data['config']['title'],
            'comment'      => $data['validatedData']['comment'],
        ]));

        return true;
    }

    /**
     * E-mail Clockbase Mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function clockbaseMail(array $data) : bool {
        // Envia e-mail.
        Mail::to($data['validatedData']['mail'])->send(new ReportMail([
            'pathToReport' => storage_path('app/public/pdf/' . $data['config']['name'] . '/' . Report::find($data['validatedData']['report_id'])->file),
            'subject'      => 'Relatório de ' . $data['config']['title'],
            'title'        => $data['config']['title'],
            'comment'      => $data['validatedData']['comment'],
            'company'      => $data['company'],
        ]));

        return true;
    }

    /**
     * E-mail Employee Base Mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function employeebaseMail(array $data) : bool {
        // Envia e-mail.
        Mail::to($data['validatedData']['mail'])->send(new SuggestionMail([
            'subject' => 'Sugestão anônima',
            'title'   => 'Sugestão anônima',
            'comment' => $data['validatedData']['comment'],
            'company' => $data['validatedData']['company'],
        ]));

        return true;
    }

}
