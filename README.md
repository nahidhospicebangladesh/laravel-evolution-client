# Evolution Laravel Client

[![Latest Version on Packagist](https://img.shields.io/packagist/v/samuelterra22/evolution-laravel-client.svg?style=flat-square)](https://packagist.org/packages/samuelterra22/evolution-laravel-client)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/samuelterra22/evolution-laravel-client/run-tests?label=tests)](https://github.com/samuelterra22/evolution-laravel-client/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/samuelterra22/evolution-laravel-client/Check%20&%20fix%20styling?label=code%20style)](https://github.com/samuelterra22/evolution-laravel-client/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/samuelterra22/evolution-laravel-client.svg?style=flat-square)](https://packagist.org/packages/samuelterra22/evolution-laravel-client)

Cliente Laravel para a Evolution API, permitindo integração fácil com WhatsApp.

## Instalação

Você pode instalar o pacote via composer:

```bash
composer require samuelterra22/evolution-laravel-client
```

Você pode publicar o arquivo de configuração com:

```bash
php artisan vendor:publish --tag="evolution-config"
```

Este é o conteúdo do arquivo de configuração publicado:

```php
return [
    'base_url' => env('EVOLUTION_API_URL', 'http://localhost:8080'),
    'api_key' => env('EVOLUTION_API_KEY', ''),
    'default_instance' => env('EVOLUTION_DEFAULT_INSTANCE', 'default'),
    'timeout' => env('EVOLUTION_API_TIMEOUT', 30),
    'webhook_url' => env('EVOLUTION_WEBHOOK_URL', null),
    'webhook_events' => [
        'message',
        'message.ack',
        'status.instance',
    ],
];
```

## Uso

### Configurando o .env

```
EVOLUTION_API_URL=http://sua-evolution-api.com
EVOLUTION_API_KEY=sua-api-key
EVOLUTION_DEFAULT_INSTANCE=default
```

### Usando a Facade

```php
use SamuelTerra22\EvolutionLaravelClient\Facades\Evolution;

// Verificar QR Code
$qrCode = Evolution::getQrCode();

// Verificar se está conectado
$connected = Evolution::isConnected();

// Enviar mensagem de texto
$result = Evolution::sendText('5511999999999', 'Olá, esta é uma mensagem de teste!');
```

### Usando Instâncias Diferentes

```php
use SamuelTerra22\EvolutionLaravelClient\Facades\Evolution;

// Usar uma instância específica
$result = Evolution::instance('minha-instancia')->sendText('5511999999999', 'Olá!');
```

### Trabalhando com Chats

```php
use SamuelTerra22\EvolutionLaravelClient\Facades\Evolution;

// Listar todos os chats
$chats = Evolution::chat->all();

// Buscar um chat específico
$chat = Evolution::chat->find('5511999999999');

// Obter mensagens de um chat
$messages = Evolution::chat->messages('5511999999999', 20);

// Marcar um chat como lido
Evolution::chat->markAsRead('5511999999999');
```

### Trabalhando com Grupos

```php
use SamuelTerra22\EvolutionLaravelClient\Facades\Evolution;

// Listar todos os grupos
$groups = Evolution::group->all();

// Criar um novo grupo
$newGroup = Evolution::group->create('Nome do Grupo', [
    '5511999999999',
    '5511888888888',
]);

// Adicionar participantes a um grupo
Evolution::group->addParticipants($groupId, [
    '5511777777777',
]);

// Promover a administrador
Evolution::group->promoteToAdmin($groupId, '5511999999999');
```

### Enviando Diferentes Tipos de Mensagens

```php
use SamuelTerra22\EvolutionLaravelClient\Facades\Evolution;

// Enviar texto
Evolution::message->sendText('5511999999999', 'Olá, tudo bem?');

// Enviar imagem
Evolution::message->sendImage('5511999999999', 'https://exemplo.com/imagem.jpg', 'Legenda da imagem');

// Enviar documento
Evolution::message->sendDocument('5511999999999', 'https://exemplo.com/documento.pdf', 'nome-arquivo.pdf', 'Confira este documento');

// Enviar localização
Evolution::message->sendLocation('5511999999999', -23.5505, -46.6333, 'São Paulo', 'Avenida Paulista, 1000');

// Enviar contato
Evolution::message->sendContact('5511999999999', 'Nome do Contato', '5511888888888');
```

## Testando

```bash
composer test
```

## Changelog

Consulte o [CHANGELOG](CHANGELOG.md) para mais informações sobre o que mudou recentemente.

## Contribuindo

Por favor, veja [CONTRIBUTING](CONTRIBUTING.md) para detalhes.

## Segurança

Se você descobrir algum problema relacionado à segurança, envie um e-mail para seu-email@exemplo.com em vez de usar o issue tracker.

## Créditos

- [Samuel Terra](https://github.com/samuelterra22)
- [Todos os Contribuidores](../../contributors)

## Licença

The MIT License (MIT). Consulte o [Arquivo de Licença](LICENSE.md) para mais informações.
