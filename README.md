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
use SamuelTerra22\EvolutionLaravelClient\Models\Button;
use SamuelTerra22\EvolutionLaravelClient\Models\ListRow;
use SamuelTerra22\EvolutionLaravelClient\Models\ListSection;

// Enviar texto
Evolution::message->sendText('5511999999999', 'Olá, tudo bem?');

// Enviar texto com delay e preview de link
Evolution::message->sendText('5511999999999', 'Confira este site: https://exemplo.com', false, 1000, true);

// Enviar imagem
Evolution::message->sendImage('5511999999999', 'https://exemplo.com/imagem.jpg', 'Legenda da imagem');

// Enviar documento
Evolution::message->sendDocument('5511999999999', 'https://exemplo.com/documento.pdf', 'nome-arquivo.pdf', 'Confira este documento');

// Enviar localização
Evolution::message->sendLocation('5511999999999', -23.5505, -46.6333, 'São Paulo', 'Avenida Paulista, 1000');

// Enviar contato
Evolution::message->sendContact('5511999999999', 'Nome do Contato', '5511888888888');

// Enviar enquete
Evolution::message->sendPoll('5511999999999', 'Qual sua cor favorita?', 1, ['Azul', 'Verde', 'Vermelho', 'Amarelo']);

// Enviar lista
$rows1 = [
    new ListRow('Opção 1', 'Descrição da opção 1', 'opt1'),
    new ListRow('Opção 2', 'Descrição da opção 2', 'opt2')
];
$rows2 = [
    new ListRow('Opção 3', 'Descrição da opção 3', 'opt3'),
    new ListRow('Opção 4', 'Descrição da opção 4', 'opt4')
];

$sections = [
    new ListSection('Seção 1', $rows1),
    new ListSection('Seção 2', $rows2)
];

Evolution::message->sendList(
    '5511999999999',
    'Título da Lista',
    'Escolha uma opção',
    'Ver Opções',
    'Rodapé da lista',
    $sections
);

// Enviar botões
$buttons = [
    new Button('reply', 'Sim', ['id' => 'btn-yes']),
    new Button('reply', 'Não', ['id' => 'btn-no']),
    new Button('url', 'Visitar Site', ['url' => 'https://exemplo.com'])
];

Evolution::message->sendButtons(
    '5511999999999',
    'Confirmação',
    'Deseja prosseguir com a operação?',
    'Escolha uma opção abaixo',
    $buttons
);

// Enviar reação a uma mensagem
Evolution::message->sendReaction(
    ['remoteJid' => '5511999999999@c.us', 'id' => 'ABCDEF123456', 'fromMe' => false],
    '👍'
);

// Enviar status
Evolution::message->sendStatus(
    'text',
    'Olá, este é meu status!',
    null,
    '#25D366',
    2,
    true
);
```

### Trabalhando com Labels

```php
use SamuelTerra22\EvolutionLaravelClient\Facades\Evolution;

// Listar todas as etiquetas
$labels = Evolution::label->findLabels();

// Adicionar uma etiqueta a um chat
Evolution::label->addLabel('5511999999999', 'label_id_123');

// Remover uma etiqueta de um chat
Evolution::label->removeLabel('5511999999999', 'label_id_123');
```

### Trabalhando com Chamadas

```php
use SamuelTerra22\EvolutionLaravelClient\Facades\Evolution;

// Fazer uma chamada fake
Evolution::call->fakeCall('5511999999999', false, 45); // Chamada de voz com 45 segundos
Evolution::call->fakeCall('5511999999999', true, 30);  // Chamada de vídeo com 30 segundos
```

### Trabalhando com Perfil

```php
use SamuelTerra22\EvolutionLaravelClient\Facades\Evolution;

// Buscar perfil de um contato
$profile = Evolution::profile->fetchProfile('5511999999999');

// Buscar perfil de empresa
$businessProfile = Evolution::profile->fetchBusinessProfile('5511999999999');

// Atualizar nome do perfil
Evolution::profile->updateProfileName('Meu Nome');

// Atualizar status
Evolution::profile->updateProfileStatus('Disponível para atendimento');

// Atualizar foto de perfil
Evolution::profile->updateProfilePicture('data:image/jpeg;base64,/9j/4AAQSkZJRgABAQE...');

// Remover foto de perfil
Evolution::profile->removeProfilePicture();

// Buscar configurações de privacidade
$privacySettings = Evolution::profile->fetchPrivacySettings();

// Atualizar configurações de privacidade
Evolution::profile->updatePrivacySettings(
    'all',               // readreceipts
    'contacts',          // profile
    'contacts',          // status
    'all',               // online
    'contacts',          // last
    'contacts'           // groupadd
);
```

### Trabalhando com WebSocket

```php
use SamuelTerra22\EvolutionLaravelClient\Facades\Evolution;

// Configurar WebSocket
Evolution::websocket->setWebSocket(true, [
    'message',
    'message.ack',
    'status.instance'
]);

// Buscar configurações do WebSocket
$webSocketConfig = Evolution::websocket->findWebSocket();

// Criar um cliente WebSocket
$webSocketClient = Evolution::websocket->createClient();

// Registrar handlers para eventos
$webSocketClient->on('message', function ($data) {
    // Processar mensagem recebida
    Log::info('Nova mensagem recebida', $data);
});

$webSocketClient->on('message.ack', function ($data) {
    // Processar confirmação de leitura
    Log::info('Mensagem lida', $data);
});

// Conectar ao servidor WebSocket
$webSocketClient->connect();

// ... Em algum momento posterior, desconectar
$webSocketClient->disconnect();
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
