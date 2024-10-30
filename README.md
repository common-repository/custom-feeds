# Custom RSS Feeds by Envintus, LLC #

* Add custom RSS feeds to your WordPress installation and customize the feeds using theme templates.
* Version 1.0
* Inspired by: https://wordpress.org/plugins/feed-wrangler/

### How to use this plugin ###
Install and activate the plugin. Once activated go to "Settings -> Custom Feeds" to begin adding new feeds.

Each feed consists of a slug, description, and template file. Each slug must be unique and if there is no theme template provided for the custom feed, then it will default to WordPress' RSS2 template. To use a custom theme template simply create one, name it appropriately (feed-slug.php), and place it in your theme directory. The theme will automatically be detected and used by the feed.

### Requirements ###
* PHP 5.3
* WordPress 3.1 or later

### Future releases ###

* Update existing feed
* Bulk delete feeds
* Better management interface
