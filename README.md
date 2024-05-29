# Projeto Ebanx 🏦 

Este é um projeto de exemplo para gerenciar transações "bancárias" utilizando o Laravel 11. 
Ele fornece uma API RESTful para criar, ler, atualizar e excluir transações. O projeto também inclui testes unitários para garantir o funcionamento correto das funcionalidades.

## Funcionalidades

- CRUD de transações.
- Filtragem de contas bancárias.
- Validação de contas caso já exista.
- Acréscimo de taxas.
- Validações para garantir que as transações não excedam o saldo disponível.

## Tecnologias Utilizadas

- Laravel 11
- PHP 8.2
- MySQL (ou outro banco de dados suportado pelo Laravel)
- PHPUnit para testes unitários

## Requisitos

- PHP >= 8.2
- Composer
- MySQL (ou outro banco de dados suportado pelo Laravel)

## Instalação

1. Clone o repositório:

2. Instale as dependências do Composer:
`composer install`

3. Crie um arquivo `.env` baseado no `.env.example` e configure suas variáveis de ambiente, incluindo as configurações do banco de dados.

4. Gere a chave de aplicativo:
`php artisan key:generate`

6. Inicie o servidor local:
`php artisan serve`

## URL Pública
Caso queira somente testar a API e não fazer o setup, você pode encontrar ela no endpoint: `http://ebanx.mauriciotoledo.com.br/api` e mais informações abaixo em nossa documentação.

## Documentação da API
Você pode acessar o nosso swagger no link: `http://ebanx.mauriciotoledo.com.br/docs`

## Testes Unitários

O projeto inclui testes unitários para garantir que as funcionalidades estejam funcionando corretamente. Você pode executar os testes usando o comando `vendor/bin/phpunit`.

## PHP 8.2 e Laravel 11

Este projeto utiliza PHP 8.2 e Laravel 11 para aproveitar os recursos mais recentes e as melhorias de desempenho oferecidas pelas versões mais recentes da linguagem e do framework.

