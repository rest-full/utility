<?php

namespace Restfull\Utility;

use ErrorException;
use Restfull\Container\Instances;

/**
 *
 */
class Translator extends translation
{

    /**
     * @var Inflector
     */
    public $inflector;

    /**
     * @var array
     */
    private $cache = [];

    /**
     * @param string $lenguage
     */
    public function __construct(string $lenguage = 'pt_BR')
    {
        $this->lenguage = ['en', $lenguage];
        $instance = new Instances();
        $this->inflector = $instance->resolveClass(
                $instance->assemblyClassOrPath(
                        '%s' . DS_REVERSE . 'Utility' . DS_REVERSE . 'Inflector',
                        [ROOT_NAMESPACE]
                )
        );
        parent::__construct();
        return $this;
    }

    /**
     * @param string $text
     * @param string $convect
     * @return string
     */
    public function plural(string $text, string $convect = ''): string
    {
        if (isset($this->inflector->pluralCache[$text])) {
            if (!empty($convect)) {
                $convect = 'to' . ucfirst($convect);
                return $this->inflector->{$convect}($this->inflector->pluralCache[$text]);
            }
            return $this->inflector->pluralCache[$text];
        }
        if (!empty($convect)) {
            $convect = 'to' . ucfirst($convect);
            return $this->translation($this->inflector->pluralize($text), $convect);
        }
        return $this->translation($this->inflector->pluralize($text));
    }

    /**
     * @param string $text
     * @param string $convert
     * @return string
     * @throws ErrorException
     */
    public function translation(string $text, string $convert = ''): string
    {
        if (isset($this->cache[$text])) {
            $text = $this->cache[$text];
        } else {
            $oldText = $text;
            $text = $this->translate($text);
            $this->cache[$oldText] = $text;
        }
        if (!empty($convert)) {
            return $this->inflector->{$convert}($text);
        }
        return $text;
    }

    /**
     * @param string $text
     * @param string $convect
     * @return string
     * @throws ErrorException
     */
    public function singular(string $text, string $convect = ''): string
    {
        if (isset($this->inflector->singularCache[$text])) {
            if (!empty($convect)) {
                $convect = 'to' . ucfirst($convect);
                return $this->inflector->{$convect}($this->inflector->singularCache[$text]);
            }
            return $this->inflector->singularCache[$text];
        }
        if (!empty($convect)) {
            $convect = 'to' . ucfirst($convect);
            return $this->translation($this->inflector->singularize($text), $convect);
        }
        return $this->translation($this->inflector->singularize($text));
    }

    /**
     * @param string $key
     * @param string $plural
     * @param string $singular
     * @return $this
     */
    public function caches(string $key, string $plural, string $singular): Translator
    {
        $this->inflector->singularCache[$key] = $singular;
        $this->inflector->pluralCache[$key] = $plural;
        return $this;
    }

    /**
     * @return $this
     */
    public function changeLenguage(): Translator
    {
        $this->lenguage = array_reverse($this->lenguage);
        return $this;
    }

    public function translations(array $texts, string $convert = ''): array
    {
        if (isset($this->cache[$texts])) {
            $texts = $this->cache[$texts];
        } else {
            foreach ($texts as $key => $value) {
                $text = !empty($convert) ? $this->translation($value, $convert) : $this->translation($text);
                $texts[$key] = $text;
            }
            $this->cache = $texts;
        }
        return $texts;
    }

}
