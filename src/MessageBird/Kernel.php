<?php

declare(strict_types=1);

class Kernel {

    private $env;

    /**
     * Kernel constructor.
     */
    public function __construct(string $env = null) {
        $this->env = $env ?? 'prod';
    }

    /**
     * @return string
     */
    public function getEnv(): string {
        return $this->env;
    }
}
