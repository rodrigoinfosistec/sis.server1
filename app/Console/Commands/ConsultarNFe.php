<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\NfeConfig;
use App\Models\Nfe;
use App\Services\NfeService;

class ConsultarNFe extends Command
{
    protected $signature = 'nfe:consultar {--ano=}';
    protected $description = 'Baixar todas as NFe por ano especificado (ou todas se não informado)';

    public function handle()
    {
        $anoFiltro = $this->option('ano');

        $config = NfeConfig::firstOrCreate(
            ['chave' => 'ultimo_nsu'],
            ['valor' => '0']
        );

        $ultimoNSU = $config->valor;
        $nfe = new NfeService();

        do {
            $this->info("Consultando NSU: {$ultimoNSU}");
            $resp = $nfe->consultarNotas($ultimoNSU);

            // Atualiza o NSU salvo
            if (!empty($resp['ultNSU'])) {
                $ultimoNSU = $resp['ultNSU'];
                $config->valor = $ultimoNSU;
                $config->save();
            }

            if (!empty($resp['loteDistDFeInt']['docZip'])) {
                foreach ($resp['loteDistDFeInt']['docZip'] as $doc) {
                    $xml = gzdecode(base64_decode($doc['__content__']));
                    $chave = $doc['schema'];

                    // extrai data de emissão do XML
                    $dom = new \DOMDocument();
                    $dom->loadXML($xml);
                    $dhEmiNode = $dom->getElementsByTagName('dhEmi')->item(0);
                    $dhEmi = $dhEmiNode ? substr($dhEmiNode->nodeValue, 0, 10) : null;
                    $anoEmissao = $dhEmi ? substr($dhEmi, 0, 4) : null;

                    // se ano foi informado, filtra aqui
                    if ($anoFiltro && $anoEmissao != $anoFiltro) {
                        continue;
                    }

                    Nfe::updateOrCreate(
                        ['chave' => $chave],
                        ['xml' => $xml, 'data_emissao' => $dhEmi]
                    );

                    $this->info("XML salvo: {$chave}");
                }
            }

            $temMais = ($resp['ultNSU'] != $resp['maxNSU']);

        } while ($temMais);

        $this->info('Consulta finalizada.');
    }
}
