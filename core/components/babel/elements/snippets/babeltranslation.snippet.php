<?php
/**
 * BabelTranslation
 *
 * @package babel
 * @subpackage snippet
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

use mikrobi\Babel\Snippets\BabelTranslation;

$corePath = $modx->getOption('babel.core_path', null, $modx->getOption('core_path') . 'components/babel/');
/** @var Babel $babel */
$babel = $modx->getService('babel', Babel::class, $corePath . 'model/babel/', [
    'core_path' => $corePath
]);

$snippet = new BabelTranslation($modx, $scriptProperties);
if ($snippet instanceof mikrobi\Babel\Snippets\BabelTranslation) {
    return $snippet->execute();
}
return 'mikrobi\Babel\Snippets\BabelTranslation class not found';
