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
        return [];
    }

    private function geraPayloadCancelamentoNFSe (array $dadosServico): array
    {
        return [];
    }

    public function emitirNFSe (): array
    {
        return [];
    }

    public function consultarNFSe (string $id): array
    {
        return [];
    }

    public function cancelarNFSe (string $id): array {
        return [];
    }
}