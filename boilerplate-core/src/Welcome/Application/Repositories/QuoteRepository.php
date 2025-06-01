<?php

declare(strict_types=1);

namespace App\Welcome\Application\Repositories;

use App\Welcome\Domain\Quote\Quote;

interface QuoteRepository
{
    /**
     * @return \App\Welcome\Domain\Quote\Quote
     */
    public function findRandom(): Quote;
}
