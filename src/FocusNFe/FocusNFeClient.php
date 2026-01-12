<?php

    Namespace FocusNFe;

    Use Exception;

    class FocusNFeClient 
    {
        private string $baseUrl;
        private string $login;
        private string $senha;

        public function __construct (string $login, string $senha, bool $ambienteHomologacao = true)
        {
            $this->login = $login;
            $this->senha = $senha;
            $this->baseUrl = $ambienteHomologacao ? 'https://homologacao.focusnfe.com.br' : 'https://api.focusnfe.com.br';
        }

        private function geraPayloadCadastroEmpresa (array $dadosEmpresa): array
        {
            $payload = [
                'nome' => (string) $dadosEmpresa['nome'],
                'nome_fantasia' => (string) $dadosEmpresa['nome_fantasia'],
                'inscricao_estadual' => (int) $dadosEmpresa['inscricao_estadual'],
                'inscricao_municipal' => (int) $dadosEmpresa['inscricao_municipal'],
                'cnpj' => (string) $dadosEmpresa['cnpj'],
                'cpf' => (string) $dadosEmpresa['cpf'],
            ];

            return $payload;
        }

        private function request (string $method = 'GET', string $uri, ?array $body = null, bool $raw = false): array
        {
            $ch = curl_init();

            curl_setopt_array($ch, [
                CURLOPT_URL => $this->baseUrl . $uri,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                CURLOPT_USERPWD => "$this->login:$this->senha",
                CURLOPT_CUSTOMREQUEST => $method
            ]);

            if (in_array($method, ['POST', 'PUT', 'PATCH'])) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            } else {
                curl_setopt($ch, CURLOPT_HTTPHEADER, array());
            }

            if ($body && in_array($method, ['POST', 'PUT', 'PATCH'])) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
            }

            $response = curl_exec($ch);

            if ($response === false) {
                throw new Exception(curl_error($ch));
            }

            $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            return [
                'status' => $http,
                'data'   => $raw ? $response : json_decode($response, true)
            ];
        }
    }