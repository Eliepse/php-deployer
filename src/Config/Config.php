<?php


namespace Eliepse\Deployer\Config;


use Eliepse\Deployer\Exception\ConfigurationException;
use Eliepse\Deployer\Exception\JsonException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Yaml\Yaml;

class Config implements \JsonSerializable
{

    /**
     * Every value of this array are considered as a required attribute
     * @var array
     */
    protected $required = [];

    /**
     * The config attributes
     * @var array
     */
    private $attributes = [];

    /**
     * If provided, config only contain keys present in the filter
     * @var array
     */
    protected $filter = [];


    /**
     * Config constructor.
     * @param array $required
     * @param array $filter
     */
    public function __construct(array $required = null, array $filter = null)
    {
        $this->required = $required ?? $this->required;
        $this->filter = $filter ?? $this->filter;
    }


    /**
     * Create a config object from the provided file
     * @param string $filepath The path to the config file
     * @param Config|null $config When provided, hydrate and return this object instead of creating a new one
     * @return static|Config
     * @throws ConfigurationException
     */
    public static function load(string $filepath, Config $config = null)
    {
        if (!file_exists($filepath))
            throw new ConfigurationException("Configuration file not found at: $filepath");

        $config = $config ?? new static();

        $config->hydrate(Yaml::parseFile($filepath));

        return $config;
    }


    /**
     * @param array $attributes
     * @throws ConfigurationException
     */
    private function hydrate(array $attributes): void
    {
        $filter = array_flip($this->filter);

        if (empty($filter)) {

            $this->attributes = $attributes;

        } else {

            $this->attributes = array_filter($attributes,
                function ($key) use ($filter) {

                    return array_key_exists($key, $filter);

                },
                ARRAY_FILTER_USE_KEY);

        }

        $this->checkRequired(true);

    }


    /**
     * @param bool $throw
     * @return bool
     * @throws ConfigurationException
     */
    private function checkRequired(bool $throw = false): bool
    {
        // If no required keys provided, abort checking
        if (count($this->required) === 0)
            return true;

        // Find missing elements
        $missing_keys = array_values(array_diff($this->required, array_keys($this->attributes)));

        if (count($missing_keys) > 0) {
            $keys = join(", ", $missing_keys);

            if ($throw)
                throw new ConfigurationException("The configuration is missing required elements: $keys");
            else
                return false;

        }

        // Find empty required elements
        $empty_keys = array_filter(array_intersect_key($this->attributes, array_flip($this->required)), function ($value) {
            return empty($value);
        });

        if (count($empty_keys) > 0) {
            $keys = join(", ", array_keys($empty_keys));

            if ($throw)
                throw new ConfigurationException("The configuration is missing required values for keys: $keys");
            else
                return false;
        }

        return true;
    }


    public function get(string $key, $default = null)
    {
        return $this->attributes[$key] ?? $default;
    }


    public function set(string $key, $value): self
    {
        // If filter provided, check if the key is allowed
        if (count($this->filter) === 0 || array_search($key, $this->filter, true) !== false)
            $this->attributes[$key] = $value;

        return $this;
    }


    public function getAll(): array
    {
        return $this->attributes;
    }

    public function isValid(): bool
    {
        return $this->checkRequired(false);
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize(): array
    {
        return $this->attributes;
    }
}