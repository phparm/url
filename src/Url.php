<?php

declare(strict_types=1);

namespace Phparm\Url;

use Phparm\Entity\Attribute;
use Phparm\Entity\Option;
use InvalidArgumentException;

use function \parse_url;

/**
 * @template TKey of array-key
 * @template TValue
 *
 * ========== property_hook_method ==========
 * @method string getScheme()
 * @method string getHost()
 * @method int getPort()
 * @method string getUser()
 * @method string getPass()
 * @method string getPath()
 * @method Query|null getQuery()
 * @method string getFragment()
 *
 * @method $this setScheme(string $scheme)
 * @method $this setHost(string $host)
 * @method $this setPort(int $port)
 * @method $this setUser(string $user)
 * @method $this setPass(string $pass)
 * @method $this setPath(string $path)
 * @method $this setQuery(Query|null $query)
 * @method $this setFragment(string $fragment)
 * ========== property_hook_method ==========
 */
class Url extends Attribute
{
    public string $scheme;
    public string $host;
    public int $port;
    public string $user;
    public string $pass;
    public string $path;
    public ?Query $query = null;
    public string $fragment;

    /**
     * @param array|string $attributes
     * @param Option|null $option
     */
    public function __construct($attributes, ?Option $option = null)
    {
        parent::__construct($attributes, $option);
    }

    public function __toString(): string
    {
        return implode('', [
            $this->scheme ?: '',
            '://',
            $this->user ?: '',
            $this->pass ? ":{$this->pass}@" : '',
            $this->host ?: '',
            $this->port ? ":{$this->port}" : '',
            $this->path ?: '',
            $this->query?->getValue() ? sprintf('?%s', $this->query) : '',
            $this->fragment ? "#{$this->fragment}" : '',
        ]);
    }

    /**
     * @param array|string $attributes
     * @param Option|null $option
     * @return static
     */
    public static function make($attributes = null, ?Option $option = null): static
    {
        return parent::make($attributes, $option);
    }

    /**
     * @param array|string $attributes
     * @param Option|null $option
     * @return array
     */
    protected function transform($attributes, ?Option $option = null): array
    {
        $data = $attributes;
        if (is_string($data)) {
            $data = $this->parse($attributes);
            if (isset($data['query'])) {
                $data['query'] = Query::make($data['query']);
            }
        } elseif (is_array($data)) {
            if (isset($data['query'])) {
                $data['query'] = Query::make($data['query']);
            }
        }
        return parent::transform($data, $option);
    }

    public function base(): string
    {
        return implode('', [
            $this->scheme ?: '',
            '://',
            $this->user ?: '',
            $this->pass ? ":{$this->pass}@" : '',
            $this->host ?: '',
            $this->port ? ":{$this->port}" : '',
        ]);
    }

    public function baseSwap(string $swapUrl): static
    {
        $urlInfo = $this->parse($swapUrl);
        $this->scheme = $urlInfo['scheme'] ?? '';
        $this->user = $urlInfo['user'] ?? '';
        $this->pass = $urlInfo['pass'] ?? '';
        $this->host = $urlInfo['host'] ?? '';
        $this->port = $urlInfo['port'] ?? 0;
        return $this;
    }

    public function parse(string $url): array
    {
        if (!str_starts_with($url, 'http')) {
            throw new InvalidArgumentException(sprintf('Invalid url: "%s"', $url));
        }
        return parse_url($url) ?: [];
    }
}