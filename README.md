# advanced-pods-bulk-action
This WordPress plugin modifies Pods UI to allow marking bulk items on several pages and uses POST instead of GET to avoid the "too long URI" issue.

## Motivation for this plugin
Pods is a powerful framework that transforms WordPress to a full blown CMS. Pods also provides an administration UI that allows you to manage these objects or "pods". However the bulk actions have a drawback, all the item ids are sent via HTTP GET. This works for many cases, specially when you are not displaying too many items in a single page, and when you want the bulk action to only be limited to items on that page. But what if you want to delete all items in all pages ? Or you need a bulk action that exports all the items to a CSV or PDF ? This plugin solves these cases.

## Installation
Just download the Zip file from our github repo and upload the plugin to your WordPress installation either using the WordPress Dashboard or by unzipping the file in your plugins directory. Activate the plugin and you are good to go

## Usage
After activation the plugin changes the form submission to HTTP POST and also starts tracking the checked items in session. So you can move from page to page, mark items and then select a bulk action and click "Apply". The action will happen on all selected items. Doing a new search or clicking on the WordPress menu to display all items clears the items from session.

## Advanced Usage
The plugin works seamlessly with any custom bulk actions that you have developed for the pods. No code changes are needed.
