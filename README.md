# RC Flight Manager #

* Author: mrtoothrot
* Contributors: mrtoothrot
* Tags: flightmanager, modell-airfields, booking, schedule, roster
* Requires at least: 5.7.0
* Tested up to: 5.7.0
* Requires PHP: 7.4
* Stable tag: 0.7.0
* Text Domain: rc-flight-manager
* Domain Path: /languages
* Plugin URI: <https://wordpress.org/plugins/rc-flight-manager>
* License: GPLv2 or later
* License URI: <http://www.gnu.org/licenses/gpl-2.0.html>

Wordpress plugin implementing a Flight Manager Scheduling System for Modell Airfield Clubs

## Description ##

Usually modell airfields need to have a flight manager onsite while the members are flying their model planes. Normally there is a roster were each flying day is assigned to one of the members. This member is the flight manager for the day.

This plugin implements a scheduling system for these flight manager services. Members can check-in for a free slot and become the flight manager on the given date. They are also able to change their duty with other members of the club.

There is also a small scheduling system for flight slots. Each member of the club can book a time frame during which he/she wants to use the airfield. The number of parallel pilots can be configured. If the maximum is reached, a given timeslot can't be booked any more. This feature can be used for example to limit the number of persons on the airfield due to local COVID-19 regulations.

## Installation ##

This section describes how to install the plugin and get it working.

e.g.

1. On your wordpress installation, create a directory `/wp-content/plugins/rc-fligh-manager`
1. Upload all files from the GitHub repository to the `/wp-content/plugins/rc-fligh-manager` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Create a page and use the shortcodes [rc-flight-manager-schedule] (for the flight manager scheduling part) or [rc-flight-slot-reservation] (for the flightslot reservation part)

## Usage ##

### Flight Manager Scheduling System ###

Place the shortcode `[rc-flight-manager-schedule]` on any wordpress page on which you want to show the flight manager roster.

Use the shortcode parameter "months=" to specify how many months starting from current month are displayed in the roster.

Example:
    `[rc-flight-manager-schedule months=3]`

### Flight Slot Booking System ###

Place the shortcode `[rc-flight-slot-reservation]` on any wordpress page on which you want to show the booking system.

Example:
    `[rc-flight-slot-reservation]`

## Frequently Asked Questions ##

None yet

## Screenshots ##

1. Flight Manager roster - `/assets/screenshot-1.png`
2. Members can swap duties between each other - `/assets/screenshot-2.png`
3. Other members can be assigned to a duty - `/assets/screenshot-3.png`
4. Flightslot reservation table - `/assets/screenshot-4.png`

## Changelog ##

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
