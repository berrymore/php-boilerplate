<?php

declare(strict_types=1);

namespace Carina\Http\Dispatcher;

use ReflectionParameter;
use Symfony\Component\HttpFoundation\Request;

final class AttributeHandler
{
    /** @var string */
    private string $attribute;

    /** @var callable */
    private $handler;

    /**
     * @param  string    $attribute
     * @param  callable  $handler
     */
    public function __construct(string $attribute, callable $handler)
    {
        $this->attribute = $attribute;
        $this->handler   = $handler;
    }

    /**
     * @return string
     */
    public function getAttribute(): string
    {
        return $this->attribute;
    }

    /**
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @param  \ReflectionParameter                       $parameter
     * @param  object                                     $attribute
     *
     * @return mixed
     */
    public function handle(Request $request, ReflectionParameter $parameter, object $attribute): mixed
    {
        return call_user_func($this->handler, $request, $parameter, $attribute);
    }
}
