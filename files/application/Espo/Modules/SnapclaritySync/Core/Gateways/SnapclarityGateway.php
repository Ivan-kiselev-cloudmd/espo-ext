<?php

namespace Espo\Modules\SnapclaritySync\Core\Gateways;

use Espo\Core\Container;

/**
 * @property Container container
 * @property string apiKey
 * @property string organizationId
 * @property string apiUrl
 * @property string errorMessage
 */
class SnapclarityGateway
{

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var
     */
    private $errorMessage;

    /**
     * @var
     */
    private $apiKey;

    /**
     * @var
     */
    private $organizationId;

    /**
     * @var
     */
    private $apiUrl;

    /**
     * SnapclarityGateway constructor.
     * @param Container $container
     * @throws \Espo\Core\Exceptions\Error
     */
    public function __construct
    (
        Container $container
    )
    {
        $this->container = $container;
        $this->setApiCredentials();
    }


    /**
     * @return mixed
     */
    public function isError()
    {
        return !empty($this->errorMessage);
    }

    /**
     * @param array $parameters
     * @return array
     */
    public function getContacts($parameters = [])
    {
        $parameters['includeAssessment'] = 1;
        $response = $this->doRequest('/integration/mc/users','GET',$parameters);
        $response['message'] = $this->errorMessage;

        return $response;
    }

    /**
     * @param string $endpoint
     * @param string $method
     * @param array $queryParams
     * @param array $formData
     * @return array
     */
    private function doRequest(string $endpoint, string $method, array $queryParams = [], array $formData = [])
    {
        $success = true;
        $curl = curl_init();
        $query = http_build_query($queryParams);

        curl_setopt_array($curl, array(
            CURLOPT_URL =>  $this->apiUrl . $endpoint .'?' . $query,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => array(
                'X-APIKey: ' . $this->apiKey
            ),
        ));

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $success = false;
            $this->errorMessage = curl_errno($curl) ?: $response;
        }
        curl_close($curl);

        if (empty($response)) {
            $success = false;
            $this->errorMessage = $response;
        }

        return [
            'success' => $success,
            'data' => !empty($response)  ? json_decode($response,true)['data'] : null
        ];
    }

    /**
     * @throws \Espo\Core\Exceptions\Error
     */
    private function setApiCredentials()
    {
        if (!empty($this->container)) {
            $integration = $this->getIntegration();
            if (!empty($integration)) {
                $this->apiKey = $integration->get('apiKey');
                $this->organizationId = $integration->get('organizationId');
                $this->apiUrl = $integration->get('apiUrl');
            }
        }
    }

    /**
     * @return mixed
     * @throws \Espo\Core\Exceptions\Error
     */
    private function getIntegration()
    {
        return $this->container->get('entityManager')->getEntity('Integration', 'Contact');
    }

}