<?php
/**
 * BabelLinks
 *
 * @package babel
 * @subpackage snippet
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

use mikrobi\Babel\Snippets\BabelLinks;

$corePath = $modx->getOption('babel.core_path', null, $modx->getOption('core_path') . 'components/babel/');
/** @var Babel $babel */
$babel = $modx->getService('babel', Babel::class, $corePath . 'model/babel/', [
    'core_path' => $corePath
]);

$snippet = new BabelLinks($modx, $scriptProperties);
if ($snippet instanceof mikrobi\Babel\Snippets\BabelLinks) {
    return $snippet->execute();
}
return 'mikrobi\Babel\Snippets\BabelLinks class not found';
