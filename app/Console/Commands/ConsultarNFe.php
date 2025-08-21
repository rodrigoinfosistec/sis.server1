<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NFeService;
use App\Models\Nfe;
use App\Models\NfeConfig;

class ConsultarNFe extends Command
{
    protected $signature = 'nfe:consultar';
    protected $description = 'Consulta NF-e emitidas contra o CNPJ, salva no banco e guarda o último NSU';

    public function handle(NFeService $nfe)
    {
        // Recupera último NSU salvo
        $config = NfeConfig::firstOrCreate(['chave' => 'ultimo_nsu'], ['valor' => '0']);
        $ultimoNSU = (int)$config->valor;

        $resp = $nfe->consultarNotas($ultimoNSU);

        if (!empty($resp['loteDistDFeInt']['docZip'])) {
            $docs = $resp['loteDistDFeInt']['docZip'];

            // Se vier apenas um documento, transforma em array
            if (isset($docs['__content__'])) {
                $docs = [$docs];
            }

            foreach ($docs as $doc) {
                $xml = gzdecode(base64_decode($doc['__content__']));
                $chave = pathinfo($doc['schema'], PATHINFO_FILENAME);

                // Salvar XML no storage
                $path = $nfe->salvarXML($xml, $chave);

                // Extrair dados principais
                $dados = $nfe->extrairDados($xml);
                if ($dados) {
                    Nfe::updateOrCreate(
                        ['chave' => $chave],
                        [
                            'cnpj_emitente' => $dados['cnpj_emitente'],
                            'razao_emitente' => $dados['razao_emitente'],
                            'data_emissao' => $dados['data_emissao'],
                            'valor' => $dados['valor'],
                            'xml_path' => $path
                        ]
                    );
                    $this->info("NF-e salva: $chave");
                } else {
                    $this->warn("NF-e $chave é apenas resumo (resNFe).");
                }
            }

            // Atualizar último NSU retornado
            if (!empty($resp['loteDistDFeInt']['ultNSU'])) {
                $novoNSU = (int)$resp['loteDistDFeInt']['ultNSU'];
                $config->valor = $novoNSU;
                $config->save();
                $this->info("Último NSU atualizado para $novoNSU");
            }
        } else {
            $this->info("Nenhuma nova nota encontrada.");
        }
    }
}
