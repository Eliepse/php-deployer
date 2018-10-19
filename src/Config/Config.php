<?php


namespace Eliepse\Deployer\Config;


use Eliepse\Deployer\Exception\ConfigurationException;

class Config
{

    /**
     * Every value of this array are considered as a required attribute
     * @var array
     */
    private $required = [];

    /**
     * The config attributes
     * @var array
     */
    private $attributes = [];

    /**
     * If provided, config only contain keys present in the filter
     * @var array
     */
    private $filter = [];


    /**
     * Config constructor.
     * @param array $required
     * @param array $filter
     */
    public function __construct(array $required = [], array $filter = [])
    {
        $this->required = $required;
        $this->filter = $filter;
    }


    /**
     * Create a config object from the provided file
     * @param string $filepath The path to the config file
     * @param Config|null $config When provided, hydrate and return this object instead of creating a new one
     * @return Config
     * @throws ConfigurationException
     */
    public static function load(string $filepath, Config $config = null): self
    {
        if (!file_exists($filepath))
            throw new ConfigurationException("Configuration file not found at: $filepath");

        $config = $config ?? new Config();

        $config->hydrate(json_decode(file_get_contents($filepath), true));

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

        $this->checkRequired();

    }


    /**
     * @throws ConfigurationException
     */
    private function checkRequired(): void
    {
        // If no required keys provided, abort checking
        if (count($this->required) === 0)
            return;

        // Find missing elements
        $missing_keys = array_values(array_diff($this->required, array_flip($this->attributes)));

        if (count($missing_keys) > 0) {
            $keys = join(", ", $missing_keys);
            throw new ConfigurationException("The configuration is missing required elements: $keys");
        }

        // Find empty required elements
        $empty_keys = array_filter(array_intersect_key($this->attributes, array_flip($this->required)), function ($value) { return empty($value); });

        if (count($empty_keys) > 0) {
            $keys = join(", ", array_keys($empty_keys));
            throw new ConfigurationException("The configuration is missing required values for keys: $keys");
        }
    }


    public function get(string $key, $default = null)
    {
        return $this->attributes[ $key ] ?? $default;
    }


    public function getAll(): array
    {
        return $this->attributes;
    }


}