<?php

namespace App\Services;

use NFePHP\NFe\Common\Standardize;
use NFePHP\NFe\Tools;
use NFePHP\Common\Certificate;
use SimpleXMLElement;

class NFeService
{
    protected $tools;

    public function __construct()
    {
        $config = file_get_contents(storage_path('app/nfe/config.json'));
        $certPfx = file_get_contents(storage_path('app/nfe/certificado.pfx'));
        $senha = env('CERT_PFX_SENHA');

        $certificate = Certificate::readPfx($certPfx, $senha);
        $this->tools = new Tools($config, $certificate);
        $this->tools->model('55');
    }

    public function consultarNotas($ultimoNSU = 0)
    {
        $resp = $this->tools->sefazDistDFe($ultimoNSU);
        $stz = new Standardize($resp);
        return $stz->toArray();
    }

    public function salvarXML($xml, $chave)
    {
        $pasta = storage_path('app/nfe/xml');
        if (!is_dir($pasta)) {
            mkdir($pasta, 0777, true);
        }
        $path = "$pasta/{$chave}.xml";
        file_put_contents($path, $xml);
        return $path;
    }

    public function extrairDados($xml)
    {
        $dom = new SimpleXMLElement($xml);

        // A NF-e pode vir como resNFe (resumo) ou procNFe (completo)
        if (isset($dom->NFe)) {
            $ide = $dom->NFe->infNFe->ide;
            $emit = $dom->NFe->infNFe->emit;
            $total = $dom->NFe->infNFe->total->ICMSTot;
        } elseif (isset($dom->infNFe)) {
            $ide = $dom->infNFe->ide;
            $emit = $dom->infNFe->emit;
            $total = $dom->infNFe->total->ICMSTot;
        } else {
            return null; // pode ser apenas resumo (resNFe)
        }

        return [
            'cnpj_emitente' => (string)($emit->CNPJ ?? ''),
            'razao_emitente' => (string)($emit->xNome ?? ''),
            'data_emissao' => (string)($ide->dhEmi ?? $ide->dEmi ?? ''),
            'valor' => (string)($total->vNF ?? '0'),
        ];
    }
}
