<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Core\Domain\Service;

use stdClass;

interface JwtDecoder
{
    public function decode(string $jwt): stdClass;
}
