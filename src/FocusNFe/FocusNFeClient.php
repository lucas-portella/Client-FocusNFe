<?php

    Namespace FocusNFeClient;

    Use Exception;

    /**
     * Client para integração com API da FocusNFe
     * 
     * Reponsável por autenticação e requisições HTTP
     * para emissão e gerenciamento de NFSe.
     */

    class FocusNFeClient 
    {
        private string $baseUrl;
        private string $login;
        private string $senha;
        private string $idEmpresa;

        /**
         * Contrutor da classe
         * 
         * @param string $login             Login fornecido pela FocusNFe
         * @param string $senha             Senha fornecida pela FocusNFe
         * @param string|null $idEmpresa    Id da empresa, se previamente cadastrada na API
         * @param bool $producao            Habilite false para testes em homologacao
         */
        public function __construct (string $login, string $senha, ?string $idEmpresa = null, bool $producao = true)
        {
            $this->login = $login;
            $this->senha = $senha;
            $this->baseUrl = $producao ? 'https://api.focusnfe.com.br' : 'https://homologacao.focusnfe.com.br';
            $this->idEmpresa = $idEmpresa;
        }

        /**
        * Seta o IdEmpresa fornecido pela FocusNFe (mediante cadastro na API)
        * 
        * @param int $idEmpresa    Id da empresa retornado pela FocusNFe.
        */
        public function setIdEmpresa (int $idEmpresa): void
        {
            $this->idEmpresa = $idEmpresa;
        }

        /**
         * Retorna o IdEmpresa.
         */
        public function getIdEmpresa (): ?int
        {
            return $this->idEmpresa;
        }

        /**
         * Realiza requisição HTTP
         * 
         * @param string $method    Método HTTP (POST, GET, PUT, DELETE, PATCH)
         * @param string $uri       Endpoint da API
         * @param array|null $body  Payload da requisição
         * @param bool $raw         Se true, retorna o dado bruto (para download de xml, pdf, etc)
         * 
         * @return array {
         *  status: int,
         *  data: mixed
         * }
         * 
         * @throws Exception    Em caso de erro de comunicação
         */
        public function request (string $method = 'GET', string $uri, ?array $body = null, bool $raw = false): array
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