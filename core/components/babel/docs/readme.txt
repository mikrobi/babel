--------------------
Babel
--------------------
Version: 2.2.5-pl
Author: Jakob Class <jakob.class@class-zec.de>
		
License: GNU GPLv2 (or later at your option)
--------------------

Babel is an Extra for MODx Revolution that helps you managing your multilingual
websites using different contexts. Babel even supports managing several different
multilingual websites within one MODx instance by using so called context groups.

Babel maintains links between translated resources. In the manager you can use 
the Babel Box to easily switch between the different language versions
of your resources. Translations can be created automatically by Babel or defined 
manually.

Additionally Babel can be used to synchronize certain template variables (TVs)
of translated resources which should be the same in every context (language).

Feel free to suggest ideas/improvements/bugs on GitHub:
https://github.com/mikrobi/babel/issues


IMPORTANT: Upgrading from version < 2.0.0:
====================

Babel is based on ideas of Sylvain Aerni <enzyms@gmail.com> and has been completely
reengineered in version 2.0.0 and is NOT BACKWARD COMPATIBLE to older versions. 
If you're using an older version of Babel you have to uninstall and remove that 
version first.


Installation
====================

0.	Create a context for each language and set the cultureKey and site_url settings
	according to your needs. You may refer to our tutorial to setup your
	multilingual site(s):
	http://www.class-zec.com/en/blog/2011/multilingual-websites-with-modx-and-babel.html
	
	Be sure that your context switches work well.

1.	Install Babel via the package manager and set the system settings for Babel via 
	the form displayed during setup:
	- Context Keys (babel.contextKeys):
		Comma separated list of context keys which should be used to link 
		multilingual resources.
		For advanced configuration you may define several groups of context keys 
		by using a semicolon (;) as delimiter. This is usefull if your're 
		administrating multiple multilingual sites within one MODx instance.
		Example scenario:
			site1: en, de, fr. Using contexts: web, site1de, site1fr
			site2: en, de. Using contexts: site2en, site2de
			You would set babel.contextKeys to "web,site1de,site1fr;site2en,site2de".
			
	- Name of Babel TV (babel.babelTvName):
		Name of template variable (TV) in which Babel will store the links between
		multilingual resources this TV will be maintained by Babel. Please don't
		change this TV manually otherwhise your translation links may be broken.
		
	- IDs of TVs to be synchronized (babel.syncTvs):
		Comma separated list of ids of template variables (TVs) which should be
		synchronized by Babel.


Usage
====================

When you open a resource for editing, the Babel Box
will be displayed on top of the resource form. There will be button-like links for
each language (context) you have defined in the babel.contextKeys system setting.

The buttons may have three different colors according to their state:
- Black:
	Language of the currently displayed resource.
- Green:
	Language for which a translated resource is defined.
- Light Gray:
	Language for which no translation has been created or defined yet.
	
By clicking on the (green) language buttons you can easily switch between the
different language versions of your resources.

If there are no translations defined for certain language (gray button),
mousover the language's button: a layer appears where you can tell Babel to 
create a translation of the current resource or you can set the translation link
to an existing resource manually by entering the ID of the translated resource.

When clicking on "Create Translation" Babel will create a new resource in the
language's context and copy all the content of the current resource to the newly
created resource. You now can translated all the content and TVs and publish
the translated resource.

If you'd like to remove a translation link, just mouseover the (green) language
button: a layer appears where you can click on "Unlink translation" button to
remove the translation link to this language.


Snippet usage 
====================

Currently there are two snippets available for Babel:
BabelLinks and BabelTranslation.


BabelLinks
--------------------
BabelLinks is a snippet which displays links to other languages (contexts)
in the frontend. You can call the snippet in your templates like this:

<ul>
  [[BabelLinks]]
</ul>

The following parameters are supported by BabelLinks:
- resourceId (optional):
	ID of resource of which links to translations should be displayed.
	Default: current resource's ID.
- tpl (optional):
	Chunk to display a language link.
	Default: babelLink.
- activeCls (optional):
	CSS class name for the current active language.
	Default: active.
- showUnpublished (optional):
	Flag whether to show unpublished translations.
	Default: 0
- showCurrent (optional):
	Flag whether to show a link to a translation of the current language.
	Default: 1

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
	If link points to a resource of the current active language (context)
	this placeholder is set to the active CSS class name specified by the
	&activeCls parameter (default=active). Otherwise this placeholder is empty.
- [[+id]]:
	ID of tranlated resource. If no translation is available this placeholder
	is empty ('').


BabelTranslation
--------------------
The BabelTranslation snippets returns the ID of a translated resource in a 
given context.

The following parameters are supported by BabelLinks:
- resourceId (optional):
	ID of resource of which a translated resource should be determined.
	Default: current resource's ID.
- contextKey (required):
	Key of context in which translated resource should be determined.
- showUnpublished (optional):
	Flag whether to show unpublished translations.
	Default: 0
	
Example usage:

[[BabelTranslation? &contextKey=`de`]]

This will return the ID of the translated resource located in the "de" context
of the current resource.


Support
====================

Feel free to suggest ideas/improvements/bugs on GitHub:
https://github.com/mikrobi/babel/issues