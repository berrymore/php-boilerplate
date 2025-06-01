<?php

declare(strict_types=1);

namespace App\Welcome\Http\Controller\Welcome;

use App\Welcome\Application\Repositories\QuoteRepository;
use Component\Http\Dispatcher\Attributes\Controller;
use Symfony\Component\Routing\Attribute\Route;

#[Controller]
final readonly class IndexAction
{
    public function __construct(private QuoteRepository $quotes)
    {
    }

    #[Route('/welcome')]
    public function __invoke(): array
    {
        $quote = $this->quotes->findRandom();

        return [
            'quote'  => $quote->getQuote(),
            'author' => $quote->getAuthor(),
        ];
    }
}
