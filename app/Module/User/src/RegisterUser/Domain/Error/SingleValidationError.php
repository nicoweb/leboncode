<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\RegisterUser\Domain\Error;

use DomainException;
use NicolasLefevre\LeBonCode\Core\Domain\Violation\Violation;
use NicolasLefevre\LeBonCode\Core\Domain\Violation\ViolationList;

abstract class SingleValidationError extends DomainException
{
    public const PROPERTY_PATH = '';
    public const MESSAGE = '';

    public readonly ViolationList $violations;

    protected function __construct()
    {
        $this->violations = new ViolationList();
        parent::__construct();
    }

    public function withViolation(): self
    {
        $this->violations->add(
            Violation::create(static::PROPERTY_PATH, static::MESSAGE),
        );

        return $this;
    }
}
