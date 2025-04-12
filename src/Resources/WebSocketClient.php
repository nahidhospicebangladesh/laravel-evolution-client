<?php

namespace SamuelTerra22\EvolutionLaravelClient\Services;

use Illuminate\Support\Facades\Log;
use Ratchet\Client\WebSocket as RatchetWebSocket;
use Ratchet\Client\Connector;
use React\EventLoop\Factory;
use React\Socket\Connector as SocketConnector;
use Ratchet\RFC6455\Messaging\MessageInterface;

class WebSocketClient
{
    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var string
     */
    protected $instanceId;

    /**
     * @var string
     */
    protected $apiToken;

    /**
     * @var int
     */
    protected $maxRetries;

    /**
     * @var float
     */
    protected $retryDelay;

    /**
     * @var int
     */
    protected $retryCount = 0;

    /**
     * @var bool
     */
    protected $shouldReconnect = true;

    /**
     * @var \React\EventLoop\LoopInterface
     */
    protected $loop;

    /**
     * @var array
     */
    protected $handlers = [];

    /**
     * @var RatchetWebSocket|null
     */
    protected $conn = null;

    /**
     * Create a new WebSocketClient instance.
     *
     * @param string $baseUrl
     * @param string $instanceId
     * @param string $apiToken
     * @param int $maxRetries
     * @param float $retryDelay
     */
    public function __construct(
        string $baseUrl,
        string $instanceId,
        string $apiToken,
        int $maxRetries = 5,
        float $retryDelay = 1.0
    ) {
        $this->baseUrl = rtrim(preg_replace('/^http/', 'ws', $baseUrl), '/');
        $this->instanceId = $instanceId;
        $this->apiToken = $apiToken;
        $this->maxRetries = $maxRetries;
        $this->retryDelay = $retryDelay;
        $this->loop = Factory::create();
    }

    /**
     * Connect to the WebSocket server.
     *
     * @return void
     */
    public function connect(): void
    {
        try {
            $connector = new Connector($this->loop, new SocketConnector([
                'dns' => '8.8.8.8',
                'timeout' => 10
            ]));

            $url = "{$this->baseUrl}?apikey={$this->apiToken}";

            $connector($url)->then(
                function (RatchetWebSocket $conn) {
                    $this->conn = $conn;
                    $this->retryCount = 0;

                    Log::info("WebSocket connected");

                    // Subscribe to the instance-specific namespace
                    $conn->send(json_encode([
                        'event' => 'subscribe',
                        'data' => ['instance' => $this->instanceId]
                    ]));

                    $conn->on('message', function (MessageInterface $msg) {
                        $this->handleMessage($msg);
                    });

                    $conn->on('close', function ($code = null, $reason = null) {
                        Log::warning("WebSocket disconnected: {$code} {$reason}");
                        $this->conn = null;

                        if ($this->shouldReconnect && $this->retryCount < $this->maxRetries) {
                            $this->attemptReconnect();
                        } else {
                            Log::error("Maximum number of reconnection attempts reached");
                            $this->loop->stop();
                        }
                    });
                },
                function (\Exception $e) {
                    Log::error("Could not connect: {$e->getMessage()}");

                    if ($this->shouldReconnect && $this->retryCount < $this->maxRetries) {
                        $this->attemptReconnect();
                    } else {
                        Log::error("Maximum number of reconnection attempts reached");
                        $this->loop->stop();
                    }
                }
            );

            $this->loop->run();
        } catch (\Exception $e) {
            Log::error("Error connecting to WebSocket: {$e->getMessage()}");
        }
    }

    /**
     * Attempt to reconnect with exponential backoff.
     *
     * @return void
     */
    protected function attemptReconnect(): void
    {
        try {
            $delay = $this->retryDelay * (2 ** $this->retryCount);
            Log::info("Attempting to reconnect in {$delay} seconds...");

            $this->loop->addTimer($delay, function () {
                $this->retryCount++;
                $this->connect();
            });
        } catch (\Exception $e) {
            Log::error("Error during reconnection attempt: {$e->getMessage()}");

            if ($this->retryCount < $this->maxRetries) {
                $this->retryCount++;
                $this->attemptReconnect();
            } else {
                Log::error("All reconnection attempts failed");
                $this->loop->stop();
            }
        }
    }

    /**
     * Handle an incoming message.
     *
     * @param MessageInterface $msg
     * @return void
     */
    protected function handleMessage(MessageInterface $msg): void
    {
        try {
            $data = json_decode($msg->getPayload(), true);

            if (isset($data['event']) && isset($this->handlers[$data['event']])) {
                $event = $data['event'];
                $eventData = $data['data'] ?? [];

                call_user_func($this->handlers[$event], $eventData);
            }
        } catch (\Exception $e) {
            Log::error("Error processing message: {$e->getMessage()}");
        }
    }

    /**
     * Register a callback for a specific event.
     *
     * @param string $event
     * @param callable $callback
     * @return self
     */
    public function on(string $event, callable $callback): self
    {
        $this->handlers[$event] = $callback;
        return $this;
    }

    /**
     * Disconnect from the WebSocket server.
     *
     * @return void
     */
    public function disconnect(): void
    {
        $this->shouldReconnect = false;

        if ($this->conn !== null) {
            $this->conn->close();
            $this->conn = null;
        }

        $this->loop->stop();
    }
}
