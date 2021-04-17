# RC Flight Manager #

* Author: mrtoothrot
* Contributors: mrtoothrot
* Tags: flightmanager, flugleiter, rc, booking, buchung, schedule, stundenplan, roster, dienstplan
* Requires at least: 5.7.0
* Tested up to: 5.7.0
* Requires PHP: 7.4
* Stable tag: 0.9.0
* Text Domain: rc-flight-manager
* Domain Path: /languages
* Plugin URI: <https://wordpress.org/plugins/rc-flight-manager>
* License: GPLv2 or later
* License URI: <http://www.gnu.org/licenses/gpl-2.0.html>

Wordpress Plugin: Buchungssystem für Flugleiterdienste auf Modellflugplätzen

## Beschreibung ##

RC Flight Manager ist ein Online Dienstplan für Flugleiter-Dienste auf Modellflugplätzen.

Auf Modellflugplätzen sind Flugleiter anwesend, während die Piloten ihre Flugmodell fliegen. In der Regel wird ein Vereinsmitlglied zum Flugleiter eingeteilt und sorgt für die Einhaltung der geltenden Flugsicherheitsregeln.

### Online Flugleiter Dienstplan ###

Zeigt eine Tabelle mit allen Flugleiter-Diensten an, die besetzt werden müssen. Für jeden Tag ist der diensthabende Flugleiter eingetragen.

Funktionen

* Anzeige des Flugleiter-Dienstplans auf einer beliebigen Wordpress Seite oder Blog-Post
* Dienste werden in der Wordpress Datenbank gespeichert
* Der aktuell diensthabende Flugleiter ist im Dienstplan hervorgehoben
* Der heutige Flugleiter kann in einem Sidebar-Widget angezeigt werden
* E-Mail Benachrichtigungen der Flugleiter (zwei Wochen und dann erneut zwei Tage vor dem Dienst)

Vereinsmitglieder (Wordpress Abonnenten) können

* sich für einen Dienst eintragen
* ihren Dienst mit anderen tauschen
* ihren Dienst an andere übergeben

Vereinsvorstände (Wordpress Mitarbeiter) können

* neue Dienste im Dienstplan eintragen
* Dienste aus dem Dienstplan löschen
* Mitglieder zum Dienst einteilen
* Labels für Dienste eintragen, ändern oder löschen (z. B. um Veranstaltungen zu markieren)

Administratoren (Wordpress Administratoren) können

* E-Mail Benachrichtigungen aktivieren/deaktivieren
* E-Mail Betreff und Text für Benachrichtigungen ändern

### Buchungssystem für Flugzeiten ###

Kann benutzt werden um die Anzahl der Anwesenden Piloten auf dem Modellflugplatz zu begrenzen (z. B. aufgrund von lokalen COVID-19 Beschränkungen). Flugzeiten können bis zu einer maximalen Anzahl von Personen reserviert werden. Das Limit kann durch den Administrator konfiguriert werden.

Funktionen

* Anzeigen einer Tabelle mit Piloten, die Flugzeiten reserviert haben
* Jede Stunde ist eine Flugzeit

Vereinsmitglieder (Wordpress Abonnenten) können

* mehrere Flugzeiten für einen Tag buchen

Administratoren (Wordpress Administratoren) können

* festlegen wie viele Personen gleichzeitig buchen können.

## Installation ##

Die Installation des Plugins ist einfach. Du kannst es über das Wordpress Dashboard installieren und aktivieren:

### Automatische Installation über das Wordpress Verzeichnis ###

1. Erstelle ein Backup deiner Webseite!
1. Wähle Plugins -> Installieren
1. Suche nach "RC Flight Manager"
1. Klicke "Jetzt installieren"
1. Aktiviere das plugin mit "Aktivieren"

### Online Flugleiter Dienstplan anzeigen ###

Verwende den Shortcode `[rc-flight-manager-schedule]` auf einer beliebigen Wordpress Seite oder Blog-Post, auf der der Dienstplan angezeigt werden soll.

Mit dem Shortcode-Parameter "months=" kannst Du einstellen wie viele kommende Monate im Dienstplan angezeigt werden sollen.

Beispiel:

`[rc-flight-manager-schedule months=3]` => Zeigt die nächsten 3 Monate an.

### Buchungssystem für Flugzeiten anzeigen ###

Verwende den Shortcode `[rc-flight-slot-reservation]` auf einer beliebigen Wordpress Seite oder Blog-Post, auf der das Buchungssystem angezeigt werden soll.

Beispiel:

`[rc-flight-slot-reservation]`

## Usage ##

Einfach mit dem Browser die Seite aufrufen, auf der Du den shortcode platziert hast, und mit dem Eintragen von Diensten anfangen.

## Häufig gestellte Fragen ##

Noch keine

## Screenshots ##

1. Flugleiter Dienstplan - `/assets/screenshot-1.png`
2. Mitglieder können Dienste miteinander tauschen - `/assets/screenshot-2.png`
3. Mitglieder können für Dienste eingeteilt werden - `/assets/screenshot-3.png`
4. Flugzeiten Buchungssystem - `/assets/screenshot-4.png`

## Changelog ##

### 1.0 ###

* Updated readme.txt
* Provided liesmich.txt

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
