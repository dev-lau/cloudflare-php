<?php
/**
 * User: Gabriel Lau
 * Date: 10/07/2023
 * Time: 19:00
 */

namespace Cloudflare\API\Endpoints;

use Cloudflare\API\Adapter\Adapter;
use Cloudflare\API\Traits\BodyAccessorTrait;

class PageProjects implements API
{
    use BodyAccessorTrait;

    private $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function listProjects(
        string $accountID,
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

        $response = $this->adapter->get('accounts/' . $accountID . '/pages/projects', $query);
        $this->body = json_decode($response->getBody());

        return (object)['result' => $this->body->result, 'result_info' => $this->body->result_info];
    }

    public function getProjectDetails(string $accountID, string $projectName): \stdClass
    {
        $response = $this->adapter->get('accounts/' . $accountID . '/pages/projects/' . $projectName);

        $this->body = json_decode($response->getBody());

        return $this->body->result;
    }

    public function createProject(string $accountID, array $payload): \stdClass
    {
        $response = $this->adapter->post('accounts/' . $accountID . '/pages/projects', $payload);
        $this->body = json_decode($response->getBody());
        return $this->body->result;
    }

    public function updateProject(string $accountID, string $projectName, array $payload): \stdClass
    {
        $response = $this->adapter->put('accounts/' . $accountID . '/pages/projects/' . $projectName, $payload);
        $this->body = json_decode($response->getBody());
        return $this->body->result;
    }

    public function deleteProject(string $accountID, string $projectName): bool
    {
        $response = $this->adapter->delete('accounts/' . $accountID . '/pages/projects/' . $projectName);
        $this->body = json_decode($response->getBody());
        return $this->body->success;
    }
}
