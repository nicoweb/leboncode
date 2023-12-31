<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Core\Domain\Error;

use DomainException;
use NicolasLefevre\LeBonCode\Core\Domain\Violation\Violation;
use NicolasLefevre\LeBonCode\Core\Domain\Violation\ViolationList;

class ValidationError extends DomainException
{
    public ViolationList $violations;

    public function __construct()
    {
        $this->violations = new ViolationList();
        parent::__construct();
    }

    public function addViolation(Violation $violation): self
    {
        $this->violations->add($violation);

        return $this;
    }

    public function addViolations(ViolationList $violationList): void
    {
        foreach ($violationList->items as $violation) {
            $this->violations->add($violation);
        }
    }

    public function hasViolations(): bool
    {
        return $this->violations->hasViolations();
    }
}
