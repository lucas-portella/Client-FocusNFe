# Client para API da FocusNFe

Classe para consumo da API da FocusNFe em PHP. Abstrais as funcionalidades de operações com NFSe.

A classe abstrai a comunicação HTTP e expõe métodos simples para uso em aplicações PHP.

## Requisitos
-   PHP 7.4 ou superior
-   Docker Desktop (Instalação em Docker para teste)

## Instalação via Docker
```bash
    docker compose build --no-cache # Build do container
    docker compose up -d # Subindo o container
```

## Entrando no container
```bash
    docker exec -it php_app bash
```

## Executando o arquivo de exemplo
```bash
    php -f src/Exemplos/usoPlugNotasClient.php
```

## Autenticação
A autenticação é feita via API Key fornecida pela FocusNFe. Ela também oferece um ambiente de homologação. A classe está configurada para uso dos dois ambientes.

## Tratamento de erros
Todos os métodos retornam um array no formato:
```php
    [
        'status' => int,    //código HTTP
        'data'   => mixed   //conteúdo ou mensagem de erro
    ]
```
Em caso de falha de comunicação, validação ou erro da API, retorna `status = 500` com a mensagem descritiva.

## Documentação
A documentação da API da FocusNFe está disponível nos seguintes endereços:
-   [Documentação Oficial](https://focusnfe.com.br/doc/#introducao)
-   [Documentação da Reforma Tributária 2026](https://doc.focusnfe.com.br/reference/introducao)

# Autor
Lucas Portella.
