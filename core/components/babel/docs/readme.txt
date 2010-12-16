--------------------
Babel
--------------------
Version: 2.0.0
Author: Jakob Class <jakob.class@class-zec.de>
		
License: GNU GPLv2 (or later at your option)
--------------------

Babel is an Extra for MODx Revolution that creates linked resources across different contexts.

The easy way for your multilingual site!

It's based on ideas of Sylvain Aerni <enzyms@gmail.com>.

Babel can be configured to synchronize specified TVs between linked resources:
When you change on of these TV in a certain context the change will be applied to all linked resources in the other contexts.

Example:
You have 3 contexts: web, fr and de (for English, French and German).
You create a resource in the web context and after saving you resource, Babel shows you a "create translation" button.
Clicking on it will duplicate your resource and all its contents in the other contexts, fr and de.
Links between these three resources are created for an easy navigation.

Feel free to suggest ideas/improvements/bugs on GitHub:
https://github.com/mikrobi/babel/issues


IMPORTANT: Upgrading from version < 2.0.0:
====================

Babel has been completely reengineered in version 2.0.0 and is NOT BACKWARD COMPATIBLE to older versions. 
If you're using an older version of Babel you have to uninstall and remove that version first.


Installation
====================

0.	First create a context for each language
	(please refer to this tutorial: http://churn.butter.com.hk/posts/2010/08/internationalization-in-modx-revolution.html).
	Be sure that your context switches work well.

1.	Install Babel via package manager and set system settings for Babel via the form displayed during setup:
	- Context Keys (babel.contextKeys):
		Comma separated list of context keys which should be used to link multilingual resources.
		
		For advanced configuration you may define several groups of context keys by using a semicolon as delimiter. This is usefull if your're administrating
		multiple multilingual site within one MODx instance.
		Example scenario:
			site1: en, de, fr. Using contexts: web, site1de, site1fr
			site2: en, de. Usinf contexts: site2en, site2de
			You would set babel.contextKeys to "web,site1de,site1fr;site2en,site2de"
	- Name of Babel TV (babel.babelTvName):
		Name of template variable (TV) in which Babel will store the links between multilingual resources.
	- IDs of TVs to be synchronized (babel.syncTvs):
		Comma separated list of ids of template variables (TVs) which should be synchronized by Babel.

You can now create resources, a button will appear at the top of the resource for the translations.
When you 'translate' a resource, all content and TV's are copied the first time.
After that, only the TV's you configured will change in each context when saving a linked resource.


Snippet usage 
====================

BabelLinks is a snippet which links to other languages (contexts) in the frontend.
You can call the snippet in your templates like this:

<ul class="language-links">
  [[BabelLinks]]
</ul>


You can use your own chunk to display the links

[[BabelLinks? &tpl=`babelLink`]]

In this Chunk you have access to the following placeholders:
- [[+url]]:
	Url to linked translation (or site_url of specific language if there
	is no translated resource available).
- [[+cultureKey]]:
	Culture key of translation (e.g en, de, fr oder es).
	You may use the babel lexicon to display the language's name:
	[[%babel.language_[[+cultureKey]]? &topic=`default` &namespace=`babel`]]
- [[+active]]:
	If link points to a resource of the current active language (context) this placeholder
	is set to the active CSS class name specified by the &activeCls parameter (default=active).
	Otherwise this placeholder is empty.
	

Support
====================

Feel free to suggest ideas/improvements/bugs on GitHub:
https://github.com/mikrobi/babel/issues