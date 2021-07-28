<?php

namespace App\Factory;

use App\Entity\ApiToken;
use App\Repository\ApiTokenRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static ApiToken|Proxy createOne(array $attributes = [])
 * @method static ApiToken[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static ApiToken|Proxy find($criteria)
 * @method static ApiToken|Proxy findOrCreate(array $attributes)
 * @method static ApiToken|Proxy first(string $sortedField = 'id')
 * @method static ApiToken|Proxy last(string $sortedField = 'id')
 * @method static ApiToken|Proxy random(array $attributes = [])
 * @method static ApiToken|Proxy randomOrCreate(array $attributes = [])
 * @method static ApiToken[]|Proxy[] all()
 * @method static ApiToken[]|Proxy[] findBy(array $attributes)
 * @method static ApiToken[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static ApiToken[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static ApiTokenRepository|RepositoryProxy repository()
 * @method ApiToken|Proxy create($attributes = [])
 */
final class ApiTokenFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://github.com/zenstruck/foundry#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(ApiToken $apiToken) {})
        ;
    }

    protected static function getClass(): string
    {
        return ApiToken::class;
    }
}
