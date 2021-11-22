# RC Flight Manager #

* Author: mrtoothrot
* Contributors: mrtoothrot
* Tags: flightmanager, flugleiter, rc, booking, buchung, schedule, stundenplan, roster, dienstplan
* Requires at least: 5.7.0
* Tested up to: 5.8.2
* Requires PHP: 7.4
* Stable tag: 1.0.0
* Text Domain: rc-flight-manager
* Domain Path: /languages
* Plugin URI: <https://wordpress.org/plugins/rc-flight-manager>
* License: GPLv2 or later
* License URI: <http://www.gnu.org/licenses/gpl-2.0.html>

Wordpress plugin implementing a Flight Manager Scheduling System for Modell Airfield Clubs

## Description ##

Link zur deutschen Version: [LIESMICH](https://github.com/mrtoothrot/wordpress-rc-flight-manager-plugin/blob/main/LIESMICH.md)

RC Flight Manager provides an online roster for flight manager duties on you model club website.

Modell airfields need to have a flight manager onsite while the pilots are flying their model aircrafts. Normally one club member is the assigned flight manager for the day and has to take care that the local air safety regulations are obeyed by the pilots.

### Online Flight Manager Roster ###

Displays a table with all dates for which flight managers need to be assigned. For each date, the assigned flight manager is shown.

Features

* Display the current flight manager roster on any wordpress page or post
* Duties are saved in the wordpress database
* Todays date and flight manager is highlighted in the roster
* Todays flight manager on duty can be shown in a sidebar widget
* E-Mail notification for flight managers (two weeks and again two days before their duty)

Club Members (Wordpress Subscribers) can

* take over an empty duty
* swap their duty with other members
* handover their duty to other members

Club officials (Wordpress Contributers) can

* add new dates to the roster
* delete dates from the roster
* assign members to dates
* add/change/remove labels on dates (e. g. to mark an event on that date)

Administrators (Wordpress Administrators) can

* activate/deactivate E-Mail notification
* configure notification E-Mail subject and text

### Booking System for flight slots ###

This feature can be used to limit the number of persons on the airfield (e. g. due to local COVID-19 regulations). Flightslots can be booked by up to a maximum number of persons. This maximum limit can be configured by the Administrator.

Features

* Display a table with pilots who have reserved timeslots on current day
* Each hour is one flightslot

Club Members (Wordpress Subscribers) can

* book multiple flightslots for a day

Administrators (Wordpress Administrators) can

* configure how many pilots are allowed on the field at the same time

## Installation ##

Installation of the plugin is easy. You can install and activate the plugin from your wordpress dashbord:

### Automated installation from wordpress directory ###

1. Make a backup!
1. Navigate to Plugins -> Add New
1. Search for "RC Flight Manager"
1. Click "Install Now"
1. Activate the plugin with "Activate"

### Display the Online Flight Manager Roster ###

Place the shortcode `[rc-flight-manager-schedule]` on any wordpress page on which you want to show the flight manager roster.

Use the shortcode parameter "months=" to specify how many months starting from current month are displayed in the roster.

Example:

`[rc-flight-manager-schedule months=3]` => Shows the next 3 months

### Display the Booking System for flight slots ###

Place the shortcode `[rc-flight-slot-reservation]` on any wordpress page on which you want to show the booking system.

Example:

`[rc-flight-slot-reservation]`

## Usage ##

Just point your browser to the URL of the wordpress page/post where you have placed the shortcode and start adding dates.

## Frequently Asked Questions ##

**Some of the buttons are not translated into my language. How can I fix this?**

Wordpress seems to cache the translation files and might not update them correctly during a plugin update. Open the `[wp-content\languages\plugins\]` folder of your Wordpress installation and delete the following files:

* rc-flight-manager-de_DE.po
* rc-flight-manager-de_DE.mo

The new tranlation files should be loaded on the next refresh of the page.

## Screenshots ##

1. Flight Manager roster - `/assets/screenshot-1.png`
1. Members can swap duties between each other - `/assets/screenshot-2.png`
1. Other members can be assigned to a duty - `/assets/screenshot-3.png`
1. Flightslot reservation table - `/assets/screenshot-4.png`
1. Adding new date entries - `/assets/screenshot-5.png`
1. Adding a series of date entries - `/assets/screenshot-6.png`

## Changelog ##

### 1.1 ###

* Add filter to specify flightmanager services for a specific year
* Show Year behind month names in flightmanager schedule
TODO: When assigning services, show how many services are already assigned to a member
TODO: Update german LIESMICH.md!!!

### 1.0 ###

* New entries can be added by administrators
* Series of date entries can be added by administrators
* Hard-coded style definitions moved to CSS file
* Updated translation template file and german translation
* Updated readme.txt
* Added liesmich.txt
* Better error handling
* Security improvements

### 0.9 ###

* Security improvements

### 0.8 ###

* Todays service is highlighted with a table row border
* If no monts are specified in the `[rc-flight-manager-schedule]` shortcode, the whole current year is shown
* Swapping services is only possible with services in future
* Fixed logging issue

### 0.7 ###

* Plugin tables are now removed from the wordpress DB during plugin uninstallation
* Flight Manager Service dates can now be added
* Implemented dropdown button which accumulates all plugin functions like takeover, swap, handover, etc.
* Added function to delete service dates
* Plugin will now show past services in the current month
* Comments/Labels can now be changed
* Comments/Labels can now be styled via CSS (p.rcfm-event-label)
* Removed table headers, as the tables should be self explaining

### 0.6 ###

* Preparations for internationalization
* German Localization (added rc-flight-manager-de_DE.[po|mo] with German translation)
* Added POT file for tranlation to other languages

### 0.5 ###

* Security improvements
  * Changing options now requires `manage_options` capability => Administrator Role
  * Viewing the flight manager roster needs `read` capability => Subscriber Role
  * Users with `edit_posts`capability can assign duties to other members => Contributer Role
* Flight slot reservation limits now customizable

### 0.3 ###

* Implemented widget to show current flight manager in sidebar

### 0.2 ###

* E-Mail notification is customizable in settings page
* Placeholders for [flightmanger-name] and [flightmanager-duty-date] are now implemented
* Shortcode can be configured to only show services in the next x months
* Duties are now sorted by date when creating the roster table.
* Implemented basic email notification two and 14 days before scheduled date

### 0.1 ###

* Minimum viable product, only basic functionality

## Upgrade Notice ##

None
