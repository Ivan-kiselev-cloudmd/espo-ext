<?php

namespace Espo\Custom\Jobs;

use Espo\Core\Container;
use Espo\Core\ServiceFactory;
use Espo\Custom\Services\Eligibility;
use Espo\Custom\Services\EligibilityMapping;

class MapCaseEligibilityJob extends \Espo\Core\Jobs\Base
{
    /** @var ServiceFactory */
    private $serviceFactory;

    public function __construct(Container $container)
    {
        parent::__construct($container);

        $this->serviceFactory = $this->getServiceFactory();
    }

    public function run()
    {
        /** @var EligibilityMapping $eligibilityService */
        $eligibilityService = $this->serviceFactory->create('EligibilityMapping');

        $eligibilityService->mapContacts();
    }

}