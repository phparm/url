<?php

declare(strict_types=1);

namespace Phparm\Url\Option;

use Phparm\Entity\Option;

/**
 * ========== property_hook_method ==========
 * @method string getNumericPrefix()
 * @method string|null getArgSeparator()
 * @method int getEncodingType()
 *
 * @method $this setNumericPrefix(string $numericPrefix)
 * @method $this setArgSeparator(string|null $argSeparator)
 * @method $this setEncodingType(int $encodingType)
 * ========== property_hook_method ==========
 */
class QueryOption extends Option
{
    public string $numericPrefix = '';
    public ?string $argSeparator = null;
    public int $encodingType = PHP_QUERY_RFC3986;
}