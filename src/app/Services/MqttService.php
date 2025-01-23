<?php

namespace App\Services;

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\Exceptions\MqttClientException;

class MqttService
{
    private $config;
    protected $mqtt;

    public function __construct()
    {
        $this->config = config('mqtt');

        $this->mqtt = new MqttClient(
            $this->config['host'],
            $this->config['port'],
            $this->config['client_id']
        );
    }

    public function sendMessage($topic, $message, $qualityOfService = 0, $retain = false)
    {
        $this->connect();
        $this->publish($topic, $message, $qualityOfService, $retain);
        $this->disconnect();
    }

    private function connect()
    {
        try {
            $connectionSettings = (new \PhpMqtt\Client\ConnectionSettings)->setUsername($this->config["username"])->setPassword($this->config["password"]);
            $this->mqtt->connect($connectionSettings);
        } catch (MqttClientException $e) {
            throw new \RuntimeException('Failed to connect to MQTT broker: ' . $e->getMessage());
        }
    }

    private function disconnect()
    {
        $this->mqtt->disconnect();
    }

    private function publish($topic, $message, $qualityOfService = 0, $retain = false)
    {
        try {
            $this->mqtt->publish($topic, $message, $qualityOfService, $retain);
        } catch (MqttClientException $e) {
            throw new \RuntimeException('Failed to publish MQTT message: ' . $e->getMessage());
        }
    }

    public function addUser(string $username, string $password): bool
    {
        try {
            // Étape 1 : Créer le client MQTT
            $createClientPayload = [
                "commands" => [
                    [
                        "command" => "createClient",
                        "username" => $username,
                        "password" => $password,
                    ]
                ]
            ];
            $this->sendMessage('$CONTROL/dynamic-security/v1', json_encode($createClientPayload), 1);

            // Étape 2 : Créer un rôle spécifique pour l'agent
            $roleName = "user_" . $username;
            $createRolePayload = [
                "commands" => [
                    [
                        "command" => "createRole",
                        "rolename" => $roleName,
                    ]
                ]
            ];
            $this->sendMessage('$CONTROL/dynamic-security/v1', json_encode($createRolePayload), 1);

            // Étape 3 : Ajouter les permissions au rôle
            $permissionsPayload = [
                "commands" => [
                    [
                        "command" => "modifyRole",
                        "rolename" => $roleName,
                        "acls" => [
                            [
                                "acltype" => "publishClientReceive",
                                "topic" => "agents/$username",
                                "priority" => 0,
                                "allow" => true
                            ],
                            [
                                "acltype" => "subscribePattern",
                                "topic" => "agents/$username",
                                "priority" => 0,
                                "allow" => true
                            ],
                            [
                                "acltype" => "publishClientSend",
                                "topic" => "agents/$username",
                                "priority" => 0,
                                "allow" => true
                            ]
                        ]
                    ]
                ]
            ];
            $this->sendMessage('$CONTROL/dynamic-security/v1', json_encode($permissionsPayload), 1);

            // Étape 4 : Associer le rôle à l'utilisateur
            $assignRolePayload = [
                "commands" => [
                    [
                        "command" => "addClientRole",
                        "username" => $username,
                        "rolename" => $roleName
                    ]
                ]
            ];
            $this->sendMessage('$CONTROL/dynamic-security/v1', json_encode($assignRolePayload), 1);

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function deleteUser(string $username): bool
    {
        try {
            $payload = [
                "commands" => [
                    [
                        "command" => "deleteClient",
                        "username" => $username
                    ]
                ]
            ];
            $this->sendMessage('$CONTROL/dynamic-security/v1', json_encode($payload), 1);

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
