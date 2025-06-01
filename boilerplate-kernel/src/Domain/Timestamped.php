<?php

declare(strict_types=1);

namespace App\Kernel\Domain;

use Carbon\CarbonInterface;

trait Timestamped
{
    protected CarbonInterface $createdAt;
    protected CarbonInterface $updatedAt;

    /**
     * @return \Carbon\CarbonInterface
     */
    public function getCreatedAt(): CarbonInterface
    {
        return $this->createdAt;
    }

    /**
     * @return \Carbon\CarbonInterface
     */
    public function getUpdatedAt(): CarbonInterface
    {
        return $this->updatedAt;
    }
}
