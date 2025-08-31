<?php

namespace App\Services\Faceit\DataQueries;


use App\Services\Faceit\FaceitService;

abstract class BaseDataQuery
{
    protected FaceitService $faceitService;

    public function __construct(FaceitService $faceitService)
    {
        $this->faceitService = $faceitService;
    }

    abstract public function runQuery(): array;

}
