<?php

use FocusNFeClient\FocusNFeClient;

require "./FocusNFeClient.php";

class FocusNFSe
{
    private FocusNFeClient $client;

    public function __construct(string $login, string $senha, bool $producao = true)
    {
        $this->client = new FocusNFeClient($login, $senha, $producao);
    }

    private function geraPayloadEmissaoNFSe (array $dadosServico): array
    {
        $payload = [
            "cnpj_prestador" => (string) $dadosServico['cnpj_prestador'] ?? null,
            "cpf_prestador" => (string) $dadosServico['cpf_prestador'],
            "data_emissao" => (string) $dadosServico['data_emissao'] ?? null,
            "serie_dps" => (int) $dadosServico['serie_dps'] ?? null,
            "numero_dps" => (int) $dadosServico['numero_dps'] ?? null,
            "data_competencia" => (string) $dadosServico['data_competencia'] ?? null,
            "emitente_dps" => (int) $dadosServico['emitente_dps'] ?? null,
            "codigo_municipio_emissora" => (int) $dadosServico['codigo_municipio_emissora'] ?? null,
            "codigo_opcao_simples_nacional" => (int) $dadosServico['codigo_opcao_simples_nacional'] ?? null,
            "regime_especial_tributacao" => (int) $dadosServico['regime_especial_tributacao'] ?? null,
            "cpf_tomador" => (string) $dadosServico['cpf_tomador'] ?? null,
            "codigo_municipio_prestacao" => (int) $dadosServico['codigo_municipio_prestacao'] ?? null,
            "codigo_tributacao_nacional_iss" => (int) $dadosServico['codigo_tributacao_nacional_iss'] ?? null,
            "descricao_servico" => (string) $dadosServico['descricao_servico'] ?? null,
            "codigo_nbs" => (int) $dadosServico['codigo_nbs'] ?? null,
            "valor_servico" => (float) $dadosServico['valor_servico'] ?? null,
            "tributacao_iss" => (int) $dadosServico['tributacao_iss'] ?? null,
            "finalidade_emissao" => (int) $dadosServico['finalidade_emissao'] ?? null,
            "consumidor_final" => (int) $dadosServico['consumidor_final'] ?? null,
            "indicador_destinatario" => (int) $dadosServico['indicador_destinatario'] ?? null,
            "ibs_cbs_situacao_tributaria" => (string) $dadosServico['ibs_cbs_situacao_tributaria'] ?? null,
            "ibs_cbs_classificacao_tributaria" => (string) $dadosServico['ibs_cbs_classificacao_tributaria'] ?? null
        ];

        return $payload;
    }

    public function emitirNFSe (array $dadosServico, string $referenciaNFSe): array
    {
        $uri = "/v2/nfsen?ref={$referenciaNFSe}";
        $payload = $this->geraPayloadEmissaoNFSe($dadosServico);

        try {
            return $this->client->request('POST', $uri, $payload);
        } catch (Exception $e) {
            return [
                'status' => 500,
                'data' => [
                    'mensagem' => 'Erro ao emitir NFSe',
                    'detalhe' => $e->getMessage()
                ]
            ];
        }
    }

    public function consultarNFSe (string $referenciaNFSe): array
    {
        $uri = "/v2/nfsen/{$referenciaNFSe}";

        try {
            return $this->client->request('GET', $uri);
        } catch (Exception $e) {
            return [
                'status' => 500,
                'data' => [
                    'mensagem' => "Erro ao consultar NFSe {$referenciaNFSe}",
                    'detalhe' => $e->getMessage()
                ]
            ];
        }
    }

    public function cancelarNFSe (string $referenciaNFSe, string $justificativa): array 
    {
        $uri = "/v2/nfsen/{$referenciaNFSe}";
        $payload = ['justificativa' => $justificativa];
    
        try {
            return $this->client->request ('DELETE', $uri, $payload);
        } catch (Exception $e) {
            return [
                'status' => 500,
                'data' => [
                    'mensagem' => "Erro ao cancelar NFSe {$referenciaNFSe}",
                    'detalhe' => $e->getMessage()
                ]
            ];
        }
    }
}