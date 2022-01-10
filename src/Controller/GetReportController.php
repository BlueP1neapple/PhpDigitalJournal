<?php

namespace JoJoBizzareCoders\DigitalJournal\Controller;

class GetReportController extends GetReportCollectionController
{

    protected function buildHttpCode(array $foundReport): int
    {
        return 0 === count($foundReport) ? 404 : 200;
    }


    protected function buildResult(array $foundReport): array
    {
        return 1 === count($foundReport) ? current($foundReport) : [    'status' => 'fail',
            'message' => 'entity not found',];
    }

}