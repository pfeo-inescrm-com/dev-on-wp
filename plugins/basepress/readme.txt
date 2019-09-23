=== BasePress Knowledge Base===
Contributors: codesavory, freemius
Donate link: https://codesavory.com
Tags: knowledge base, help desk,documentation,faq
Requires at least: 4.5
Tested up to: 5.2
Stable tag: 2.4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Build a single or multiple knowledge bases with ease.
Let users find the information they need and reduce the cost of customer support.

== Description ==
BasePress allows you to build as many knowledge bases as you need to document your products or services.
Reduces your customer support cost and helps your customers find the answer they need 24/7.
It is designed to be easy to use thanks to its clean administration tools that integrates seamlessly in WordPress admin area.

What makes BasePress the right tool for your business?

1. Ready to use in less then 5 minutes.
1. Create as many knowledge bases as you need.
1. Keep your content organized in a logical way dividing it by product, service, department etc.
1. Serve targeted answer to your customers saving them time too.
1. Adapts to all devices. Your customers can consult it form any device including tablets and phones.
1. Keeps the look of your website professional.


BasePress creates an entry page for your customers where they can choose the knowledge base they want to consult.
They will be taken to the right knowledge base for their needs and all the articles, searches and suggestions will be fully relevant to what they are looking for.

This is a Lite version of our Premium plugin that we wanted to share with the WordPress community.
It has all the features you need to create your fully functional knowledge base and nothing less.

MAIN FEATURES

1. Build a single or multiple knowledge base
1. A dedicated page for users to choose the knowledge base
1. Unlimited sections hierarchy
1. List and boxed sections styles
1. Image and description for each knowledge base
1. Image, icon and description for each section
1. Icon selector for each articles
1. Drag and drop reorder for knowledge bases and sections
1. Search bar with live results
1. Shortcode to add the search bar anywhere in your website
1. Related articles widget
1. Sections widget
1. Knowledge bases widget
1. Easy to use admin screens
1. Translatable via .pot files
1. Easy customization
1. 3 default themes included

If you need some extra features for you and your customers consider upgrading to the Premium version and get access to these extra benefits:

PREMIUM FEATURES

1. Improved search bar results based on user votes and visits as well
1. Articles voting
1. Popular articles widget based on votes and/or visits
1. Dashboard widget
1. Automatic Table of Contents (in article and/or widget)
1. Drag and drop articles reorder
1. Next and Previous articles navigation
1. Advanced Content Restriction by user role
1. Knowledge base Insights
1. Multisite support
1. WPML support
1. Shortcode editor to add dynamic lists of articles outside of the knowledge base


== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress.
1. Follow the Setup Wizard or the steps below
1. Create a page and add the shortcode [basepress] to that page.
1. Go to BasePress->Settings->General and select the page you just created on 'Knowledge Base page'.
1. Select 'Single Knowledge Base Mode' if you just need a single knowledge base skipping the knoledge bases selection page.
1. Start creating your first Knowledge Base and sections.
1. Add your articles.

== Frequently Asked Questions ==

= I don't need multiple Knowledge Bases can I still use it as standard knowledge base? =

Yes. You just need to enable the 'Single Knowledge Base mode' in the settings and it would work as a standard Knowledge Base.
You still need to create a Knowledge Base under Knowledge Base->Manage KBs before adding sections and articles.

= Can I customize the look of the knowledge base? =

Yes of course. There a few ways to do it depending on your needs:

1. Simple modifications are possible via css on your theme stylesheet.
1. You can customize the look by creating a child theme of one of the default themes that comes with the plugin.
1. Build your own theme using HTML, CSS and functions made available by BasePress to easily access the knowledge base content.

= I get 404 pages in the knowledge base =

A 404 page will appear if any of the following cases is true:

1. You haven't selected the main page containing the shortcode in the General settings.
1. You haven't created any Knowledge Base yet.
1. You haven't created any section yet.
1. You haven't selected any Knowledge Base or section for the articles.
1. You have changed the slug of the page with the shortcode. In this case just go to WP Settings->Permalinks and just click 'Save Changes'.

= My knowledge base page is empty =

For the knowledge base to appear you needs at least a Knowledge Base a Section and an article ready.
Knowledge Bases or sections without any article are not displayed by default.

= Can I upgrade to the Premium version and keep my content? =

Yes. You can use the free version and build your knowledge base as long as you like. If you then decide to upgrade to get all Premium features you can do it directly from within the plugin.
All your content will remain intact. New features might not be anabled after upgrade. Just go to BasePress->Settings and activate the ones you need.

= Is this a limited version? =

No. You get a fully functional plugin with no restrictions. You can create unlimited knowledge bases, sections and articles.

= Can I use BasePress in my language? =
BasePress is fully translatable via .pot files which you can find inside the plugin folder under languages.
If you need a multilingual knowledge base than consider upgrading to our Premium version for WPML compatibility.

= Can I get support for this plugin? =
We only give support for any bug fix you may find in the free version.
Customers of our Premium Version get full support for a year from the date of purchase.

== Screenshots ==

1. BasePress Comes with 3 default themes
2. Easy to use admin tools
3. Search bar with Live Results

== Changelog ==

= 2.4.3 =
* Improved Moder Theme JS to prevent issues with browser BFCache
* Fixed PHP error when WPML and BasePress content restrictions are both enabled (Premium only)
* Added votes submission confirmation message (Premium only)
* Fixed global search when "s" parameter is used

= 2.4.2.1 =
* Fixed wrong articles order when WPML is used (Premium only)

= 2.4.2 =
* Prevented restricted content to appear on default WordPress searches (Premium only)
* Added option to exclude articles from searches outside of the knowledge base
* Added notice when Build Mode is enabled
* Fixed notice when using WPML (Premium only)
* Updated Translations

= 2.4.1 =
* Added debugging info
* Added option to skip the loading of the header and footer
* Added option to load the themes from WordPress upload directory
* Updated translations
* Fixed undefined index warning when using the Table of Content (Premium only)
* Small admin UIs improvements

= 2.4.0 =
* Added default taxonomy screen to guaranty compatibility with other plugins
* Add option to limit section levels in widget
* Added option in Related articles widget to display the current article
* General code improvements

= 2.3.7 =
* Updated WPML configuration to include latest settings for translations (Premium only)

= 2.3.6 =
* Fixed warning in PHP 7.3 on switch statement

= 2.3.5 =
* Updated themes' translations

= 2.3.4 =
* Improved restrictions to work for users with multiple roles (Premium only)

= 2.3.3 =
* Improved smart search to prevent duplicated results on some edge cases
* Updated Freemius SDK

= 2.3.2 =
* fixed restrictions not working in some installations (Premium only)
* Added option to show restriction roles in admin post list (Premium only)
* Added option to filter post in admin by restriction roles (Premium only)
* Added "Select all", "Deselect all" buttons in content restriction metabox (Premium only)
* Added option to sort user role alphabetically in content restriction metabox (Premium only)
* Updated themes

= 2.3.1 =
* Fixed restrictions for users without roles (Premium only)
* Improved restrictions metabox (Premium only)

= 2.3.0 =
* Added option to choose sidebar position
* Added option to change the button text for selecting the knowledge base
* Added possibility to include total number of found results in live ajax search
* Renamed "Product" to "knowledge base" in the whole plugin to make it more generic
* Improved Restricted content teaser to work with content that contains HTML tags (Premium only)
* Updated themes
* Updated translations
* Improved Multisite handling (Premium only)
* Fixed article preview when no section was selected
* Added compatibility with MySQL 8.x

= 2.2.4 =
* Improved internal handling of articles' order (Premium only)

= 2.2.3 =
* Fixed Table of content links (Premium only)

= 2.2.2 =
* Fixed content still missing after updating to 2.2.0. This update is suggested to all users.

= 2.2.1 =
* Hot fixed content not showing on section after updating to 2.2.0 (Visiting plugin's settings and saving would already fix this)

= 2.2.0 =
* Added option to use search parameter on URL
* Updated all mentions of "post" with "article" in admin
* Improved Setup Wizard
* Fixed missing default options if wizard was not used
* Fixed new editor blocks to work with latest WordPress updates
* General code optimization

= 2.1.1 =
* Hot fixed bug that caused all menus to be deleted after using the Setup Wizard
* Hot fixed error appearing during upgrade to Premium version
* Added message for no results found in smart search results
* Improved theme's css

= 2.1.0 =
* Added new editor blocks
* Added Setup Wizard for new installations which includes some demo content
* Added 'build mode' to hide the KB to anyone apart from the administrator.
* Added filter by "Section" in articles list in admin side.
* Improved Insights charts labels (Premium Only)
* Fixed entry page canonical link with Yoast SEO
* Updated translations
* General code improvements

= 2.0.7 =
* Added hook 'basepress_article_shortcode_args' to customize articles shortcodes arguments

= 2.0.6 =
* Removed automatic 301 redirection becaused it can conflict with many plugins

= 2.0.5 =
* Updated Freemius SDK. This update is recommended to all users for improved security
* Fixed sections pagination not working on some edge cases

= 2.0.4 =
* Improved search function to prevent conflict with other plugins

= 2.0.3 =
* Fixed "Edit Product" and "Edit Section" link in admin bar in the front end not working

= 2.0.2 =
* Fixed search bar shortcode which made bar always appear at the top of the page

= 2.0.1 =
* Added option to order articles alphabetically
* Added Gutenberg editor support

= 2.0.0 =
* Added possibility to run the Knowledge base from front page
* Added Dashboard Widget (Premium Only)
* Moved shortcode editor to its own page (Premium Only)
* General code improvements

= 1.9.1 =
* Hotfix for sections permalink not working

= 1.9.0 =
* Added Users feedback feature (Premium Only)
* fixed conflict with WooCommerce cart if KB page is not selected in settings
* Fixed search that stopped working properly after version 1.8.9

= 1.8.11 =
* Fixed pagination that stopped working after version 1.8.9

= 1.8.10 =
* Improved articles list in sections. Now it shows the articles from that section and a list of sub-sections if present

= 1.8.9 =
* Removed feed links from header inside all knowledge base pages as there are no feeds for the articles
* Added automatic 301 redirects for all articles that have been moved to a different section (article's slug must remain unchanged)
* Updated Freemius SDK

= 1.8.8 =
* Fixed post order page not showing all articles (Premium Only)
* Small improvement on admin css

= 1.8.7 =
* Fixed bug for searches when the KB is in sub-page

= 1.8.6 =
* Improved search results to move exact matches on top

= 1.8.5 =
* Removed page builders' shortcodes from search snippets

= 1.8.4 =
* Renamed some element's css classes on admin side to avoid naming conflict with other plugins

= 1.8.3 =
* Improved menu handling so KB menu item has class "current_menu_item" when visiting any KB page

= 1.8.2 =
* Updated Freemius SDK

= 1.8.1 =
* Fixed case insensitive search for non English languages

= 1.8.0 =
* Added knowledge base Insights (Premium Only)
* Fixed products template translation

= 1.7.12 =
* Minor code optimization for post views counter

= 1.7.11 =
* Fixed PHP error when section image is missing.
* Fixed search feature not finding articles in sub-sections

= 1.7.10.1 =
* Fixed PHP error when product image is missing.

= 1.7.10 =
* Improved update and upgrade process
* Improved articles voting system for better articles suggestions (Premium Only)

= 1.7.9 =
* Improved permalink structure if KB is in a sub-page

= 1.7.8 =
* Added an Icons Manager to have full control on the icons used in the knowledge base
* Updated included themes to work with the new Icon Manager
* Updated Freemius SDK

= 1.7.7 =
* Fixed page title tag for single product mode always showing the same title name

= 1.7.6 =
* Automated product selection on all admin screen if 'Single product mode' is enabled
* Improved page title tag for single product mode

= 1.7.5 =
* Fixed Search and Articles navigation not working on some installations
* Updated Freemius SDK

= 1.7.4 =
* Added possibility to disable the Table of Contents on individual articles (Premium Only)

= 1.7.3 =
* Fixed bug that stopped highliting of found terms on search bar results
* Fixed post counter not working on some WordPress installation

= 1.7.2 =
* Improved compatibility with Yoast SEO which would make Table of Contents links not work correctly (Premium Only)

= 1.7.1 =
* Fixed bug that made sections with not articles but only sub-sections disappear

= 1.7.0 =
* First release
