<?php


namespace Espo\Modules\SnapclaritySync\Core\Report\Contracts;


interface LogFileReportable
{

    /**
     * @return mixed
     */
    public function reportToLogFile();

}