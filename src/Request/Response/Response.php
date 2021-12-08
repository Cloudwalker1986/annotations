<?php
declare(strict_types=1);

namespace Request\Response;

interface Response
{
    public function getHtmlContent(): string;
}
