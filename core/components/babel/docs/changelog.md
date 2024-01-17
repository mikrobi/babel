# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [3.2.0] - TBA

### Changed

- Code refactoring
- Modernized the user experience

### Added

- Show the Babel button only on resources in contexts referenced in `babel.contextKeys` system setting
- Show the context column in the custom manager page only for contexts referenced in `babel.contextKeys` system setting
- System settings tab in custom manager page
- Change the Babel button text by the `babel.displayText` system setting (language, context or combination)
- Show all contexts in the Babel button by disabling the `babel.restrictToGroup` system setting

### Fixed

- Set context grid height on base of the content

## [3.1.1] - 2021-05-26

### Fixed

- [#189] Fix various bugs - thanks to Jako <https://github.com/Jako>

## [3.1.0] - 2021-11-21

### Added

- Add MODX 3.x compatability
- Add various new translations
- [#176] Accept a comma separated list of IDs in resourceId property of the BabelTranslation snippet
- [#168] Invoke OnDocFormSave event when duplicating a resource

### Fixed

- Fix minor bugs

## [3.0.0] - 2016-12-12

### Added

- [#143] Link to a translation by entering the ID
- [#134] Add Babel System Events - thanks to Jako <https://github.com/Jako>
- [#147] Initiated language code standard using IANA's Language Subtag Registry
- Add options to link to specific resource or all resources
- [#144] Add options to unlink to specific resource or all resources
- Add config to set table's height
- Add styling to align table in CMP
- Initialized PSR-2 coding standard https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md

### Changed

- [#123] Minor cosmetic update
- Resource title's width

### Fixed

- [#135] Fix not found icons
- [#145] Fix unlinking unsync TVs
- [#139][#140] Fix Unable to link translations by searching by pagetitle

## [3.0.0-rc1] - 2016-04-15

### Added

- Add Custom Manager Page for resources matrix, supported by Steven Morgan of Waterlogic

## [3.0.0-beta5] - 2016-03-22

### Added

- [#103] Add mouseover action on babel's button to show menu
- [#115] Add language property in BabelLinks snippet

### Changed

- Ignore the menu if the current context key is not included in `babel.contextKeys` System Settings
- Change empty item on combobox
- Change link & unlink logic, no reset whatsoever
- [#107] Replace contextKey with cultureKey in the button drop down
- [#110] Update dutch lexicon

### Fixed

- [#66] Fix moving resource to another context
- [#114] Fix context on linking

## [3.0.0-beta4] - 2015-03-25

### Added

- [#105] Add `babel.ignoreSiteStatus` system setting to ignore site_status (when the site is offline)

### Fixed

- [#108] Fix context's link then there's no translation available when the includeUnlinked property is enabled
- [#106] Fix resource selection when selecting empty text (&nbsp;)

## [3.0.0-beta3] - 2015-02-14

### Added

- [#100] Add optional typeahead combo of pagetitle on searching new link
- [#99] Add polish lexicon

### Fixed

- [#101] Fix BabelTranslation

## [3.0.0-beta2] - 2014-12-02

### Added

- Add optional toArray property to BabelLinks snippet
- Add optional toPlaceholder property to BabelLinks snippet
- Add optional wrapperTpl property to BabelLinks snippet
- Add icons
- [#97] Add russian lexicon
- [#73] Add AjaxManager support

### Changed

- Remove "q" and "cultureKey" url parameters in urls generated in the BabelLinks snippet

## [3.0.0-beta1] - 2014-11-28

### Added

- [#51] Append URL parameters of current page if any
- Add includeUnlinked property to BabelLinks snippet, option to skip/include unlinked context
- [#88] Skip unpublished contexts
- [#78][#62] Add outputSeparator property to BabelLinks snippet
- [#67] Add italian lexicon
- [#60] Add dutch lexicon

### Changed

- [#77] Update readme.txt
- [#82] Check for resourceId property before defaulting
- [#75] Replaced deprecated method clearCache() with refresh()
- [#84] Optimize OnResourceDuplicate plugin event
- [#44][#29] Use cultureKey instead of contextKey in the output of the BabelTranslation snippet
- [#27][#90][#92] Refactor language selection, runs using AJAX
- [#83][#59] Extend OnResourceDuplicate plugin event for nested resources

### Fixed

- [#56] Emptying the MODX trash can corrupt values in modx_site_tmplvar_contentvalues
- [#70] fix is_folder to isfolder
- [#58][#64] Fix the error with updating a resource, at least in the quick update window

## [2.2.5] - 2011-12-11

### Added

- Extended list of languages in translation files
- Add Romanian lexicon

### Fixed

- [#31] Make babel buttons fixed in manager and change the look accordingly

## [2.2.4] - 2011-03-28

### Added

- [#22] Add showCurrent property to BabelLinks snippet

### Fixed

- [#25] Correctly synchronize unchecked "checkbox" TVs

## [2.2.3-rc1] - 2011-01-26

### Added

- [#18] Add id placeholder to the BabelLink chunk used by the BabelLinks snippet

### Fixed

- [#21] Fix Manager redirect causes error in Google Chrome browser

## [2.2.2-rc2] - 2011-01-17

### Fixed

- Fix initial synchronization of TV values when setting a link manually didn't work

## [2.2.2-rc1] - 2011-01-14

### Added

- [#16] Values of synchronized TVs of the source resource(s) can be copied to the target resource when setting a translation link manually
- Add showUnpublished property to BabelLinks and BabelTranslation snippets

## [2.2.1-rc1] - 2011-01-09

### Added

- [#11] User is redirect to new resource after creating a translation

### Fixed

- [#15] Fix Babel TV of duplicated resources is being initiated
- Alias is duplicated when creating a translation, too

## [2.2.0-pl] - 2010-12-22

### Added

- [#10] Add checks to handle invalid Babel settings (defined by the user)

### Added

- Improved performance of TV synchronization

### Fixed

- Fix bug occuring when creating new resources
- Fix bug in validation when setting a link manually

## [2.1.1-pl1] - 2010-12-21

### Fixed

- [#9] Calling deprecated and non-existing method in Babel class

## [2.1.1-rc1] - 2010-12-19

### Added

- Add resourceId property to BabelLinks snippet
- Add french translation for new keys from version 2.1.0-beta
- Add BabelTranslation snippet to get the id of a translated resource in a given context

### Changed

- Protect the Babel TV from being manipulated manually by setting the type to 'hidden' by default

### Fixed

- Fix bug with empty babel settings (contextKeys and syncTvs)

## [2.1.0-beta] - 2010-12-17

### Added

- Add french lexicon. Thanks to Romain <romain@meltingmedia.net>
- New Babel-Box:
	- A chunk is used to generate the language links (mgr/babelBoxItem)
	- Changed CSS
	- Add JavaScript (for the langauge layers, see below)
	- All languages (contexts) of current group are displayed even if there are no links defined
	- If there is no link to a language it's background is light gray
	- When mouseovering a language link a layer is displayed where you can
		- create a translation (new resource) if no link is defined
		- manually link the translation to an existing resource if no link is defined
		- remove a link if a link is defined
- Add validation to keep the babel TV clear
- Add several keys to the lexicon

## [2.0.0-beta] - 2010-12-16

### Added

- Add build script
- Add multilanguage support for the extra itself
- Easier but more powerfull configuration:
  No need to specify names/caption of context anymore (caption is derived from the contexts' cultureKey)
  3 system settings which can be set during package installation
	* babel.contextKeys:
	  Define which context should be used to link translation
	  Defining several groups of context is supported, too (see readme.txt)
	* babel.babelTvName:
	  Name of TV in which links between translated resources are keept
	* babel.syncTvs:
	  IDs of TVs which should be synchronized by Babel
- Plugin listens to OnEmptyTrash event to remove links to non-existing resources stored in the Babel TV
- Plugin listens to OnContextRemove event to remove links to non-existing context stored in the Babel TV and to clean the `babel.contextKeys` setting

### Changed

- Total reengineering of the whole extra (code structure and logic, too) => NOT BACKWARD COMPATIBLE
- Moved CSS into asset folder
- Moved codebase to https://github.com/mikrobi/babel/
- This version is NOT BACKWARD COMPATIBLE to older versions

### Fixed

- Fix issue with primary key validation errors when linking the Babel TV to templates

## [1.0.0] - 2010-12-13

### Added

- Initial release from Sylvain Aerni <enzyms@gmail.com> see https://github.com/enzyms/babel
