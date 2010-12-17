/**
 * Babel JavaScript file for the babel-box in the manager.
 * 
 * @author Jakob Class <jakob.class@class-zec.de>
 *
 * @package babel
 */
Ext.onReady(function() {
	Ext.select('div.babel-language:has(.babel-language-layer)').on('mouseenter', function(){
		Ext.get(this).child('.babel-language-layer').show();
	})
	.on('mouseleave', function(){
		Ext.get(this).child('.babel-language-layer').hide();
	});
});