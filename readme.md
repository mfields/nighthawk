Ghostbird
==========================

A one-column theme for the [WordPress Publishing Platform](http://wordpress.org/) designed by [Michael Fields](http://wordpress.mfields.org). More doumentation and news can be found on the [official website](http://ghostbird.mfields.org).

Minimum Version
---------------
Ghostbird requires WordPress version 3.1 or later.

Supported Post Formats
----------------------
* Aside
* Gallery
* Link
* Standard
* Status
* Video

2 Custom Menu Areas
-------------------
* One on the top
* Another on the bottom

Widgetized Areas
----------------
3 widgetized areas are located at the bottom of every template file. These areas are contained in a div with the id attribute set to "widgets". This is how they work:

* If there are no widgets in any of the areas, no html should be printed for the the #widgets div.
* If only one area has widgets, each widget should fill the horizontal area available. The only exception here is the calendar widget which should always be ~14em wide.
* If two areas have widgets assigned then the left-most area will have a width of ~66% of the available horizontal space. The remaining 33% will be used for the second widgetized area.
* In cases where all three areas have widgets, each area should be ~33% of the available horizontal space.
* Although the widgets are numbered, the numbers to not define their position rather their order. If only areas 2 and 3 have widgets, they be be displayed in positions 1 and 2 in the html. Likewise if only area 3 has widgets, it will be seen as being in position #1 in the markup.

Custom Post Types and Taxonomies
--------------------------------
Ghostbird has native support for custom post post_types and taxonomies including single and archive views. This is very basic support, but care has been taken to ensure that all templates display an appropriate title and description (if available).

Supported Plugins
-----------------

* [SyntaxHighlighter Evolved](http://www.viper007bond.com/wordpress-plugins/syntaxhighlighter/) by [Viper007Bond](http://www.viper007bond.com/)
* [I Make Plugins](http://txfx.net/wordpress-plugins/i-make-plugins/) by [Mark Jaquith](http://coveredwebservices.com/)
* [Subscribe to Comments](http://txfx.net/wordpress-plugins/subscribe-to-comments/) by [Mark Jaquith](http://coveredwebservices.com/)

Changelog
---------

__1.0.1__
* Removed _ghostbird_page_menu_wrap() - no longer needed.
* WordPress nav menus no longer fallback to wp_list_pages().
* Deleted unneeded variables + css rules where appropriate.
* Removed unnecessary scrollbars in syntaxhighlighter stylesheet.

__1.0__

* First!