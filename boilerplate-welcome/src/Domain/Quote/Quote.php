<?php

declare(strict_types=1);

namespace App\Welcome\Domain\Quote;

use App\Kernel\Domain\Timestamped;
use Ramsey\Uuid\UuidInterface;

class Quote
{
    use Timestamped;

    public function __construct(protected UuidInterface $id, protected string $quote, protected string $author)
    {
    }

    /**
     * @return string
     */
    public function getQuote(): string
    {
        return $this->quote;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }
}
