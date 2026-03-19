=== Paid Memberships Pro: Downloads Add On ===
Contributors: strangerstudios, dparker1005
Tags: pmpro, paid memberships pro, members, memberships, downloads, files, restricted
Requires at least: 5.0
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 0.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create restricted file downloads for members.

== Description ==

**This plugin is in active development and is not yet recommended for production use.**

Create and manage restricted file downloads for your membership site. Upload files to a secure, non-public directory and control access based on membership level. Members see a download link; non-members see which level is required and a prompt to join.

Each download is a custom post type with a title, description, and uploaded file. Restrict access using the standard PMPro "Require Membership" meta box. Files are stored in the PMPro restricted files directory, protected from direct access.

Display downloads using three templates (link, card, or button) via shortcodes or Gutenberg blocks with live editor previews. The Download Library block and `[pmpro_download_library]` shortcode display multiple downloads in list or grid layouts.

== Installation ==

1. Upload the `pmpro-downloads` directory to the `/wp-content/plugins/` directory of your site.
1. Activate the plugin through the `Plugins` menu in WordPress.
1. Create new downloads under the Downloads menu in the WordPress admin.
1. Upload a file and set membership restrictions on each download.
1. Use the `[pmpro_download]` shortcode or the PMPro Download block to display a single download on any page.
1. Use the `[pmpro_download_library]` shortcode or the PMPro Download Library block to display multiple downloads.

== Frequently Asked Questions ==

= I found a bug in the plugin. =

Please post it in the issues section of GitHub and we'll fix it as soon as we can. Thanks for helping. [https://github.com/strangerstudios/pmpro-downloads/issues/new/choose](https://github.com/strangerstudios/pmpro-downloads/issues/new/choose)

= I need help installing, configuring, or customizing the plugin. =

Please visit our premium support site at [https://www.paidmembershipspro.com](https://www.paidmembershipspro.com) for more documentation and our support forums.

== Changelog ==
= 1.0 - TBD =
* Initial release.
