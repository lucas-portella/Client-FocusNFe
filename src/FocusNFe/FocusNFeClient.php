<?php

    Namespace FocusNFe;

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
         * @param string $login      Login fornecido pela FocusNFe
         * @param string $senha      Senha fornecida pela FocusNFe
         * @param ?string $idEmpresa Id da empresa, se previamente cadastrada na API
         * @param bool $producao     Habilite false para testes em homologacao
         */
        public function __construct (string $login, string $senha, ?string $idEmpresa = null, bool $producao = true)
        {
            $this->login = $login;
            $this->senha = $senha;
            $this->baseUrl = $producao ? 'https://api.focusnfe.com.br' : 'https://homologacao.focusnfe.com.br';
            $this->idEmpresa = $idEmpresa;
        }

        /**
         * Gera payload para cadastro de empresa.\
         * 
         * @param array $dadosEmpresa   Dados necessários para cadastro da empresa. Verifique exemplos.
         * 
         * @return array 
        */
        private function geraPayloadCadastroEmpresa (array $dadosEmpresa): array
        {
            $payload = [
                'nome' => (string) $dadosEmpresa['nome'],
                'nome_fantasia' => (string) $dadosEmpresa['nome_fantasia'],
                'inscricao_estadual' => (int) $dadosEmpresa['inscricao_estadual'],
                'inscricao_municipal' => (int) $dadosEmpresa['inscricao_municipal'],
                'cnpj' => (string) $dadosEmpresa['cnpj'],
                'cpf' => (string) $dadosEmpresa['cpf'],
                'regime_tributario' => (int) $dadosEmpresa['regime_tributario'],
                'logradouro' => (string) $dadosEmpresa['logradouro'],
                'numero' => (int) $dadosEmpresa['numero'],
                'complemento' => (string) $dadosEmpresa['complemento'],
                'municipio' => (string) $dadosEmpresa['municipio'],
                'bairro' => (string) $dadosEmpresa['bairro'],
                'cep' => (int) $dadosEmpresa['cep'],
                'UF' => (string) $dadosEmpresa['UF'],
                'telefone' => (string) $dadosEmpresa['telefone'],
                'email' => (string) $dadosEmpresa['email'],
                'enviar_email_destinatario' => (bool) $dadosEmpresa['enviar_email_destinatario'],
                'descrimina_impostos' => (bool) $dadosEmpresa['descrimina_impostos'],
                'habilita_nfe' => (bool) $dadosEmpresa['habilita_nfe'],
                'habilita_nfce' => (bool) $dadosEmpresa['habilita_nfce'],
                'habilita_nfse' => (bool) $dadosEmpresa['habilita_nfse'],
                'habilita_nfsen_producao' => (bool) $dadosEmpresa['habilita_nfsen_producao'],
                'habilita_nfsen_homologacao' => (bool) $dadosEmpresa['habilita_nfsen_homologacao'],
                'habilita_cte' => (bool) $dadosEmpresa['habilita_cte'],
                'habilita_mdfe' => (bool) $dadosEmpresa['habilita_mdfe'],
                'habilita_manifestacao' => (bool) $dadosEmpresa['habilita_manifestacao'],
                'habilita_manifestacao_cte' => (bool) $dadosEmpresa['habilita_manifestacao_cte'],
                'habilita_contigencia_offline_nfce' => (bool) $dadosEmpresa['habilita_contigencia_offline_nfce'],
                'reaproveita_numero_nfce_contingencia' => (bool) $dadosEmpresa['reaproveita_numero_nfce_contingencia'],
                'mostrar_danfse_badge' => (bool) $dadosEmpresa['mostrar_danfse_badge'],
                'orientacao_danfe' => (string) $dadosEmpresa['orientacao_danfe'],
                'recibo_danfe' => (bool) $dadosEmpresa['recibo_danfe'],
                'exibe_sempre_ipi_danfe' => (bool) $dadosEmpresa['exibe_sempre_ipi_danfe'],
                'exibe_issqn_danfe' => (bool) $dadosEmpresa['exibe_issqn_danfe'],
                'exibe_impostos_adicionais_danfe' => (bool) $dadosEmpresa['exibe_impostos_adicionais_danfe'],
                'exibe_unidade_tributaria_danfe' => (bool) $dadosEmpresa['exibe_unidade_tributaria_danfe'],
                'exibe_sempre_volumes_danfe' => (bool) $dadosEmpresa['exibe_sempre_volumes_danfe'],
                'exibe_composicao_carga_mdfe' => (bool) $dadosEmpresa['exibe_composicao_carga_mdfe'],
                'enviar_email_homologacao' => (bool) $dadosEmpresa['enviar_email_homologacao'],
                'cpf_cnpj_contabilidade' => (string) $dadosEmpresa['cpf_cnpj_contabilidade'],
                'arquivo_certificado_base64' => (string) $dadosEmpresa['arquivo_certificado_base64'],
                'senha_certificado' => (string) $dadosEmpresa['senha_certificado'],
                'arquivo_logo_base64' => (string) $dadosEmpresa['arquivo_logo_base64'],
                'delete_logo' => (bool) $dadosEmpresa['delete_logo'],
                'nome_responsavel' => (string) $dadosEmpresa['nome_responsavel'],
                'cpf_responsavel' => (string) $dadosEmpresa['cpf_responsavel'],
                'login_responsavel' => (string) $dadosEmpresa['login_responsavel'],
                'senha_responsavel' => (string) $dadosEmpresa['senha_responsavel'],
                'data_inicio_recebimento_nfe' => (string) $dadosEmpresa['data_inicio_recebimento_nfe'],
                'data_inicio_recebimento_cte' => (string) $dadosEmpresa['data_inicio_recebimento_cte'],
                'smtp_endereco' => (string) $dadosEmpresa['smtp_endereco'],
                'smtp_dominio' => (string) $dadosEmpresa['smtp_dominio'],
                'smtp_autenticacao' => (string) $dadosEmpresa['smtp_autenticacao'],
                'smtp_porta' => (string) $dadosEmpresa['smtp_porta'],
                'smtp_login' => (string) $dadosEmpresa['smtp_login'],
                'smtp_senha' => (string) $dadosEmpresa['smtp_senha'],
                'smtp_remetente' => (string) $dadosEmpresa['smtp_remetente'],
                'smtp_responder_para' => (string) $dadosEmpresa['smtp_responder_para'],
                'smtp_modo_verificacao_openssl' => (string) $dadosEmpresa['smtp_modo_verificacao_openssl'],
                'smtp_habilita_starttls' => (bool) $dadosEmpresa['smtp_habilita_starttls'],
                'smtp_ssl' => (bool) $dadosEmpresa['smtp_ssl'],
                'smtp_tls' => (bool) $dadosEmpresa['smtp_tls'],
                'csc_nfce_producao' => (string) $dadosEmpresa['csc_nfce_producao'],
                'id_token_nfce_producao' => (int) $dadosEmpresa['id_token_nfce_producao'],
                'csc_nfce_homologacao' => (string) $dadosEmpresa['csc_nfce_homologacao'],
                'id_token_nfce_homologacao' => (int) $dadosEmpresa['id_token_nfce_homologacao'],
                'proximo_numero_nfe_producao' => (string) $dadosEmpresa['proximo_numero_nfe_producao'],
                'proximo_numero_nfe_homologacao' => (string) $dadosEmpresa['proximo_numero_nfe_homologacao'],
                'serie_nfe_producao' => (string) $dadosEmpresa['serie_nfe_producao'],
                'serie_nfe_homologacao' => (string) $dadosEmpresa['serie_nfe_homologacao'],
                'proximo_numero_nfce_producao' => (string) $dadosEmpresa['proximo_numero_nfce_producao'],
                'proximo_numero_nfce_homologacao' => (string) $dadosEmpresa['proximo_numero_nfce_homologacao'],
                'serie_nfce_producao' => (string) $dadosEmpresa['serie_nfce_producao'],
                'serie_nfce_homologacao' => (string) $dadosEmpresa['serie_nfce_homologacao'],
                'proximo_numero_nfse_producao' => (string) $dadosEmpresa['proximo_numero_nfse_producao'],
                'proximo_numero_nfse_homologacao' => (string) $dadosEmpresa['proximo_numero_nfse_homologacao'],
                'serie_nfse_producao' => (string) $dadosEmpresa['serie_nfse_producao'],
                'serie_nfse_homologacao' => (string) $dadosEmpresa['serie_nfse_homologacao'],
                'proximo_numero_nfsen_producao' => (string) $dadosEmpresa['proximo_numero_nfsen_producao'],
                'proximo_numero_nfsen_homologacao' => (string) $dadosEmpresa['proximo_numero_nfsen_homologacao'],
                'serie_nfsen_producao' => (string) $dadosEmpresa['serie_nfsen_producao'],
                'serie_nfsen_homologacao' => (string) $dadosEmpresa['serie_nfsen_homologacao'],
                'proximo_numero_cte_producao' => (string) $dadosEmpresa['proximo_numero_cte_producao'],
                'proximo_numero_cte_homologacao' => (string) $dadosEmpresa['proximo_numero_cte_homologacao'],
                'serie_cte_producao' => (string) $dadosEmpresa['serie_cte_producao'],
                'serie_cte_homologacao' => (string) $dadosEmpresa['serie_cte_homologacao'],
                'proximo_numero_cte_os_producao' => (string) $dadosEmpresa['proximo_numero_cte_os_producao'],
                'proximo_numero_cte_os_homologacao' => (string) $dadosEmpresa['proximo_numero_cte_os_homologacao'],
                'serie_cte_os_producao' => (string) $dadosEmpresa['serie_cte_os_producao'],
                'serie_cte_os_homologacao' => (string) $dadosEmpresa['serie_cte_os_homologacao'],
                'proximo_numero_mdfe_producao' => (string) $dadosEmpresa['proximo_numero_mdfe_producao'],
                'proximo_numero_mdfe_homologacao' => (string) $dadosEmpresa['proximo_numero_mdfe_homologacao'],
                'serie_mdfe_producao' => (string) $dadosEmpresa['serie_mdfe_producao'],
                'serie_mdfe_homologacao' => (string) $dadosEmpresa['serie_mdfe_homologacao'],
                'habilita_nfcom' => (bool) $dadosEmpresa['habilita_nfcom'],
                'proximo_numero_nfcom_producao' => (string) $dadosEmpresa['proximo_numero_nfcom_producao'],
                'proximo_numero_nfcom_homologacao' => (string) $dadosEmpresa['proximo_numero_nfcom_homologacao'],
                'serie_nfcom_producao`' => (string) $dadosEmpresa['serie_nfcom_producao'],
                'serie_nfcom_homologacao' => (string) $dadosEmpresa['serie_nfcom_homologacao'],
                'nfe_sincrono' => (bool) $dadosEmpresa['nfe_sincrono'],
                'nfe_sincrono_homologacao' => (bool) $dadosEmpresa['nfe_sincrono_homologacao'],
                'mdfe_sincrono' => (bool) $dadosEmpresa['mdfe_sincrono'],
                'mdfe_sincrono_homologacao' => (bool) $dadosEmpresa['mdfe_sincrono_homologacao'],
                'senha_responsavel_preenchida' => (bool) $dadosEmpresa['senha_responsavel_preenchida']
            ];

            return $payload;
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
         * Cadastra uma empresa na FocusNFe
         * 
         * @param array $payload    Dados necessários da empresa. Veja exemplos.
         * @param bool $producao    Habilite false para testar em ambiente de homoloação (dry_run)
         * 
         * @return array
         */
        public function cadastraEmpresa (array $payload, bool $producao = true): array 
        {
            $uri = $producao ? '/v2/empresas' : '/v2/empresas?dry_run=1';

            try {
                $response = $this->request('POST', $uri, $this->geraPayloadCadastroEmpresa($payload));
                $idEmpresa = $response['data']['id'] ?? null;
                if ($idEmpresa) {
                    $this->setIdEmpresa($idEmpresa);
                }
                return $response;
            } catch (Exception $e) {
                return [
                    'status' => 500,
                    'data' => [
                        'ambiente' => $producao ? 'producao' : 'homologacao',
                        'mensagem' => 'Erro ao cadastrar empresa',
                        'detalhe'  => $e->getMessage()
                    ]
                ];
            }
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