<?php
/**
 * User: Gabriel Lau
 * Date: 10/07/2023
 * Time: 19:00
 */

namespace Cloudflare\API\Endpoints;

use Cloudflare\API\Adapter\Adapter;
use Cloudflare\API\Traits\BodyAccessorTrait;

class PageDomains implements API
{
    use BodyAccessorTrait;

    private $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function listDomains(
        string $accountID,
        string $projectName,
        int $page = 1,
        int $perPage = 20,
        string $direction = ''
    ): \stdClass {
        $query = [
            'page' => $page,
            'per_page' => $perPage
        ];

        if (!empty($direction)) {
            $query['direction'] = $direction;
        }

        $response = $this->adapter->get("accounts/{$accountID}/pages/projects/{$projectName}/domains", $query);
        $this->body = json_decode($response->getBody());

        return (object)['result' => $this->body->result, 'result_info' => $this->body->result_info];
    }

    public function getDomainDetails(string $accountID, string $projectName, string $domainName): \stdClass
    {
        $response = $this->adapter->get("accounts/{$accountID}/pages/projects/{$projectName}/domains/{$domainName}");

        $this->body = json_decode($response->getBody());

        return $this->body->result;
    }

    public function createDomain(string $accountID, string $projectName, array $payload): \stdClass
    {
        $response = $this->adapter->post("accounts/{$accountID}/pages/projects/{$projectName}/domains", $payload);
        $this->body = json_decode($response->getBody());
        return $this->body->result;
    }

    public function updateDomain(string $accountID, string $projectName, string $domainName, array $payload): \stdClass
    {
        $response = $this->adapter->patch("accounts/{$accountID}/pages/projects/{$projectName}/domains/{$domainName}", $payload);
        $this->body = json_decode($response->getBody());
        return $this->body->result;
    }

    public function deleteDomain(string $accountID, string $projectName, string $domainName): bool
    {
        $response = $this->adapter->delete("accounts/{$accountID}/pages/projects/{$projectName}/domains/{$domainName}");
        $this->body = json_decode($response->getBody());
        return $this->body->success;
    }
}
