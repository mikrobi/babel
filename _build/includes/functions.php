<?php
/**
 * Functions supporting the build process.
 *
 * @author Jakob Class <jakob.class@class-zec.de>
 * 
 * @package babel
 * @subpackage build
 */
function getSnippetContent($filename) {
    $o = file_get_contents($filename);
    $o = str_replace('<?php','',$o);
    $o = str_replace('?>','',$o);
    $o = trim($o);
    return $o;
}