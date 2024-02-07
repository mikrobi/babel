<?php
/**
 * Parse
 *
 * @package babel
 * @subpackage classfile
 */

namespace mikrobi\Babel\Helper;

use modX;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use mikrobi\Babel\Babel;

/**
 * Class Parse
 */
class Parse
{
    /**
     * A reference to the modX instance
     * @var modX $modx
     */
    public $modx;

    /**
     * A reference to the Babel instance
     * @var Babel $babel
     */
    public $babel;

    /**
     * Template cache
     * @var array $_tplCache
     */
    private $_tplCache;

    /**
     * Valid binding types
     * @var array $_validTypes
     */
    private $_validTypes = [
        '@CHUNK',
        '@FILE',
        '@CODE',
        '@INLINE'
    ];

    /**
     * Parse constructor
     *
     * @param modX $modx A reference to the modX instance.
     */
    public function __construct(modX $modx)
    {
        $this->modx =& $modx;
        $this->babel =& $modx->babel;
    }

    /**
     * @param array $array
     * @param string $separator
     * @return array
     */
    public static function flattenArray($array, $separator = '.'): array
    {
        $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($array));
        $result = [];
        foreach ($iterator as $value) {
            $keys = [];
            foreach (range(0, $iterator->getDepth()) as $depth) {
                $keys[] = $iterator->getSubIterator($depth)->key();
            }
            $result[join($separator, $keys)] = $value;
        }
        return $result;
    }

    /**
     * Get and parse a chunk (with template bindings)
     * Modified parseTpl method from getResources package (https://github.com/opengeek/getResources)
     *
     * @param $tpl
     * @param null $properties
     * @return bool|string
     */
    public function getChunk($tpl, $properties = null)
    {
        if ($pdo = $this->getPdoTools()) {
            $output = $pdo->getChunk($tpl, $properties ?? []);
        } else {
            $output = false;
            if (!empty($tpl)) {
                $bound = [
                    'type' => '@CHUNK',
                    'value' => $tpl
                ];
                if (strpos($tpl, '@') === 0) {
                    $endPos = strpos($tpl, ' ');
                    if ($endPos > 2 && $endPos < 10) {
                        $tt = substr($tpl, 0, $endPos);
                        if (in_array($tt, $this->_validTypes)) {
                            $bound['type'] = $tt;
                            $bound['value'] = substr($tpl, $endPos + 1);
                        }
                    }
                }
                if (is_array($bound) && isset($bound['type']) && isset($bound['value'])) {
                    $output = $this->parseChunk($bound['type'], $bound['value'], $properties);
                }
            }
        }
        if (isset($properties['stripModxTags']) && $properties['stripModxTags']) {
            $output = $this->stripModxTags($output);
        }
        return $output;
    }

    /**
     * Parse a chunk (with template bindings)
     * Modified parseTplElement method from getResources package (https://github.com/opengeek/getResources)
     *
     * @param $type
     * @param $source
     * @param null $properties
     * @return bool|string
     */
    private function parseChunk($type, $source, $properties = null)
    {
        $output = false;

        if (!is_string($type) || !in_array($type, $this->_validTypes)) {
            $type = $this->modx->getOption('tplType', $properties, '@CHUNK');
        }

        $content = false;
        switch ($type) {
            case '@FILE':
                $path = $this->modx->getOption('tplPath', $properties, $this->modx->getOption('assets_path', $properties, MODX_ASSETS_PATH) . 'elements/chunks/');
                $key = $path . $source;
                if (!isset($this->_tplCache['@FILE'])) {
                    $this->_tplCache['@FILE'] = [];
                }
                if (!array_key_exists($key, $this->_tplCache['@FILE'])) {
                    if (file_exists($key)) {
                        $content = file_get_contents($key);
                    }
                    $this->_tplCache['@FILE'][$key] = $content;
                } else {
                    $content = $this->_tplCache['@FILE'][$key];
                }
                if (!empty($content) && $content !== '0') {
                    $chunk = $this->modx->newObject('modChunk', ['name' => $key]);
                    $chunk->setCacheable(false);
                    $output = $chunk->process($properties, $content);
                }
                break;
            case '@CODE':
            case '@INLINE':
                $uniqid = uniqid();
                $chunk = $this->modx->newObject('modChunk', ['name' => "$type-$uniqid"]);
                $chunk->setCacheable(false);
                $output = $chunk->process($properties, $source);
                break;
            case '@CHUNK':
            default:
                $chunk = null;
                if (!isset($this->_tplCache['@CHUNK'])) {
                    $this->_tplCache['@CHUNK'] = [];
                }
                if (!array_key_exists($source, $this->_tplCache['@CHUNK'])) {
                    $chunk = $this->modx->getObject('modChunk', ['name' => $source]);
                    if ($chunk) {
                        $this->_tplCache['@CHUNK'][$source] = $chunk->toArray('', true);
                    } else {
                        $this->_tplCache['@CHUNK'][$source] = false;
                    }
                } elseif (is_array($this->_tplCache['@CHUNK'][$source])) {
                    $chunk = $this->modx->newObject('modChunk');
                    $chunk->fromArray($this->_tplCache['@CHUNK'][$source], '', true, true, true);
                }
                if (is_object($chunk)) {
                    $chunk->setCacheable(false);
                    $output = $chunk->process($properties);
                }
                break;
        }
        return $output;
    }

    public function getPdoTools()
    {
        $pdoTools = null;
        if (class_exists('pdoTools')) {
            $pdoTools = $this->modx->getService('pdoTools');
        } elseif (class_exists('ModxPro\PdoTools\CoreTools')) {
            $pdoTools = $this->modx->getService('ModxPro\PdoTools\CoreTools');
        }

        return $pdoTools;
    }

    /**
     * @param $string
     * @return string
     */
    public function stripModxTags($string)
    {
        return preg_replace('/\[\[([^\[\]]++|(?R))*?]]/sm', '', $string);
    }
}
