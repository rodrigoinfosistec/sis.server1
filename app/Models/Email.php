<?php

namespace App\Models;

use Illuminate\Support\Str;

use Mail;
use App\Mail\ReportMail;

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
}
