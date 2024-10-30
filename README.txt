=== cbVegas ===

Tags: image, background, fullscreen, slideshow, slides, Hintergrund, Bild, Hintergrundbild

Requires at least: 3.9  
Tested up to: 4.7.2
Stable tag: 0.3.6
Version: 0.3.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Author: demispatti
Contributors: demispatti

Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XLMMS7C62S76Q

The Vegas Background Slideshow for your WordPress powered website.

== Description ==

This plugin enables you to use the "Vegas Background Slideshow" for images on regular posts and pages. You can define a global slideshow, a fallback slideshow, and even slideshows on a per post basis. Read the help tab for more details on the options.

== Features ==

- Unlimited slideshows
- Global slideshow
- Fallback slideshow
- Slideshows on a per post basis
- Supports images
- Supports lots of custom post types: 'post', 'page', 'product', 'portfolio', 'gallery', 'art', 'books', 'movies', 'videos' - or you name it
- Various overlays to choose from

== Requirements ==

Your theme must support the core WordPress implementation of the [Custom Backgrounds](https://codex.wordpress.org/Custom_Backgrounds) theme feature.

Your theme's layout must be "boxed" somehow or an opacity should be added to the page content container for the background slideshow to be seen (of course...).

PHP version 5.4 or above.

== Installation ==

1. Upload the `cb-vegas` folder to your `/wp-content/plugins/` directory.
2. Activate the "cbVegas" plugin through the "Plugins" menu in WordPress.
3. Go to "Settings/cbVegas" to define your first slideshows.
4. Edit a post to select and enable a custom slideshow.

== Frequently Asked Questions ==

= Where do I interact with this plugin? =

There is a settings page,
and you will find a meta box on edit screens for posts, pages and products.

= How does it work? =

On the settings page, located under "Settings/cbVegas", you can create and edit slideshows and their settings.
Within the meta box, you can select a slideshow and enable or disable it for that particular post or page.

= Why doesn't it work with my theme? =

Most likely, this is because your theme doesn't support the WordPress `custom-background` theme feature.
This plugin requires that your theme utilize this theme feature to work properly.
Unfortunately, there's just no reliable way for the plugin to overwrite the background if the theme doesn't support this feature.
You'll need to check with your theme author to see if they'll add support or switch to a different theme.

= My theme supports 'custom-background' but it doesn't work! =

That's unlikely.
Just to make sure, check with your theme author and make sure that they support the WordPress `custom-background` theme feature.
It can't be something custom your theme author created.  It must be the WordPress feature.

Assuming your theme does support `custom-background` and this plugin still isn't working, your theme is most likely implementing the custom background feature incorrectly.  However, I'll be more than happy to take a look.

= How do I add support for this in a theme? =

Your theme must support the [Custom Backgrounds](https://codex.wordpress.org/Custom_Backgrounds) feature for this plugin to work.

If you're a theme author, consider adding support for this if you can make it fit in with your design.  The following is the basic code, but check out the above link.

	add_theme_support( 'custom-background' );

= Are there any known limitations? =

Not really. One thing you could consider a limitation is that supported post types have to be registered by me.
But since I made this software for you, you can provide me with your custom post type and it will be available with the next update :)

= Can you help me? =

Yes. I have a look at the plugin's support page two or three times a week and I provide some basic support there.

= Are there any known issues? =

None known.

== Screenshots ==

1. Plugin settings page image 1.
2. Plugin settings page image 2.
3. Meta box.

== Changelog ==

= Version 0.3.6 =
1. Introduced autoloading.
2. Removed redundant code.
3. Optimized scripts enqueueing.
4. Resolved problems with transitions and animations.
5. Help tab updated.

= Version 0.3.3 =
1. Renamed 'cb-vegas-style-css' to 'cb-vegas-admin-css'.
2. Removed the redundant "use global" option. However, you can still define one slideshow for all posts and pages with the fallback option.
3. Slideshows set on edit screens always overrule the fallback slideshow, if one is set.
4. Fixed some errors that occurred on non-default-locale installations.
5. Upgraded "Vegas Background SLideshow" to version 2.4.0.
6. First slide fades in by default.
7. Some stability improvements.
8. Fixed some css.
9. Corrected the alignments.
10. Improved sorting algorithm.
11. Prevent slides containing the placeholder image from being displayed on the frontend.
12. Tweaked the default values for new slides.
13. Modified the options for "cover". Image size can now be original or cover, also "repeat" is an option now.

= Version 0.3.2 =
1. Resolved a bug regarding slideshows not appearing in the dropdown on edit screens. Visit <https://wordpress.org/support/topic/re-new-sliders-not-appearing-in-the-list/#post-8845467> if this happened to you.
2. Updated Font Awesome
3. Updated Alertify
4. Removed "Loader" completely
5. Reorganized and cleaned up the UI
6. Refactored javascripts
7. General improvements

= Version 0.3.0 =

1. Enabled notifications for plugin interactions
2. Major bug fixes, like propper animations, transitions, and timings
-> If you update from a previous version, you will have to (just) save each slideshow you created and the missing options will then be available.

= Version 0.2.1 =

Resolved deactivation bug

= Version 0.2.0 =

- Added support for more post types: 'post', 'page', 'product', 'portfolio', 'gallery', 'art', 'books', 'movies', 'videos'
- Extended and corrected the plugin documentation and the documentation of the code

= Version 0.1.2 =

Minor bug fixes.

= Version 0.1.1 =

Minor bug fixes.

= Version 0.1.0 =

First release :-)
