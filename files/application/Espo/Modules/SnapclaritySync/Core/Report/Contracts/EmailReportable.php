<?php


namespace Espo\Modules\SnapclaritySync\Core\Report\Contracts;

interface EmailReportable
{
    /**
     * @param array $emails
     * @return mixed|EmailReportable
     */
    public function reportToEmail(array $emails = []);

}