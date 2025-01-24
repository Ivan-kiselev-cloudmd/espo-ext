<?php

namespace Espo\Modules\SnapclaritySync\Core\Report\Contracts;

use Espo\Modules\SnapclaritySync\Core\Report\ErrorReport;

interface APIErrorReportable
{

    /**
     * @param array $errorData
     * @return mixed|APIErrorReportable|ErrorReport
     */
    public function setErrorData(array $errorData = []);

}