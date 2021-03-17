# wordpress-rc-flight-manager-plugin

### Plugin Name ###
* Contributors: mrtoothrot
* Donate link: -
* Tags: flightmanager, modell-airfields, booking, schedule, roster
* Requires at least: 5.0.0
* Tested up to: 5.7.0
* Stable tag: -
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html

Wordpress plugin implementing a Flight Manager Scheduling System for Modell Airfield Clubs

## Description ##

**PLEASE NOTE: This plugin is in a very early state of development! A lot of things are hard-coded and might be very special to my local club!**
**USE AT OWN RISK!**

Usually modell airfields need to have a flight manager onsite while the members are flying their model planes. Normally there is a roster were each flying day is assigned to one of the members. This member is the flight manager for the day.

This plugin implements a scheduling system for these flight manager services. Members can check-in for a free slot and become the flight manager on the given date. They are also able to change their duty with other members of the club.

There is also a small scheduling system for flight slots. Each member of the club can book a time frame during which he/she wants to use the airfield. The number of parallel pilots can be configured. If the maximum is reached, a given timeslot can't be booked any more. This feature can be used for example to limit the number of persons on the airfield due to local COVID-19 regulations.

## Installation ##

This section describes how to install the plugin and get it working.

e.g.

1. On your wordpress installation, create a directory `/wp-content/plugins/rc-fligh-manager`
1. Upload all files from the GitHub repository to the `/wp-content/plugins/rc-fligh-manager` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Create a page and use the shortcodes [rc-flight-manager-schedule] (for the flight manager scheduling part) or [rc_flight_slot_reservation] (for the flightslot reservation part)

## Usage ##

Place the shortcode `[rc-flight-manager-schedule]` on any page on which you want to show the flight manager roster.

Use the shortcode parameter "months=" to specify how many months starting from current month are displayed in the roster.

Example:
    `[rc-flight-manager-schedule months=3]`

Enter the required dates for which a flight manager needs to be assigned to the database table `$wpdb->prefix_rcfm_schedule`.

Currently this needs to be done using SQL or you favourite DB administration tool. A wordpress settings page for the plugin is not yet available.

## Frequently Asked Questions ##

**A question that someone might have**

An answer to that question.

**What about foo bar?**

Answer to foo bar dilemma.

## Screenshots ##

1. Flight Manager roster - `/assets/screenshot-1.png`
2. Members can swap duties between each other - `/assets/screenshot-2.png`
3. Other members can be assigned to a duty - `/assets/screenshot-3.png`
4. Flightslot reservation table - `/assets/screenshot-4.png`

## Changelog ##

### 0.2 ###
* E-Mail notification is customizable in settings page (E-Mail recipients and subject)
* E-Mail text still hard-coded
* Shortcode can be configured to only show services in the next x months 
* Duties are now sorted by date when creating the roster table.
* Implemented basic email notification two and 14 days before scheduled date

### 0.1 ###
* Minimum viable product, only basic functionality

## Upgrade Notice ##

### 0.1 ###
Initial version
