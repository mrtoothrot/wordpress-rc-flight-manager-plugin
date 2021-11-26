# RC Flight Manager #

* Author: mrtoothrot
* Contributors: mrtoothrot
* Tags: flightmanager, flugleiter, rc, booking, buchung, schedule, stundenplan, roster, dienstplan
* Requires at least: 5.7.0
* Tested up to: 5.8.2
* Requires PHP: 7.4
* Stable tag: 1.1.0
* Text Domain: rc-flight-manager
* Domain Path: /languages
* Plugin URI: <https://wordpress.org/plugins/rc-flight-manager>
* License: GPLv2 or later
* License URI: <http://www.gnu.org/licenses/gpl-2.0.html>

Wordpress Plugin: Buchungssystem für Flugleiterdienste auf Modellflugplätzen

## Beschreibung ##

Link to english version: [README](https://github.com/mrtoothrot/wordpress-rc-flight-manager-plugin/blob/main/README.md)

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

* Neue Dienste im Dienstplan eintragen
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

Mit dem Shortcode-Parameter "year=" legst Du fest welches Jahr angezeigt werden soll.

Beispiel:

`[rc-flight-manager-schedule year=2022]` => Zeigt den Dienstplan für 2022.

Beide Shortcode-Parameter können kombiniert werden.

Beispiel:

`[rc-flight-manager-schedule year=2022 months=3]` => Zeigt die nächsten 3 Monate des Dienstplans für 2022.

### Buchungssystem für Flugzeiten anzeigen ###

Verwende den Shortcode `[rc-flight-slot-reservation]` auf einer beliebigen Wordpress Seite oder Blog-Post, auf der das Buchungssystem angezeigt werden soll.

Beispiel:

`[rc-flight-slot-reservation]`

## Benutzung ##

Einfach mit dem Browser die Seite aufrufen, auf der Du den shortcode platziert hast, und mit dem Eintragen von Diensten anfangen.

## Screenshots ##

1. Flugleiter Dienstplan - `/assets/screenshot-1.png`
1. Mitglieder können Dienste miteinander tauschen - `/assets/screenshot-2.png`
1. Mitglieder können für Dienste eingeteilt werden - `/assets/screenshot-3.png`
1. Flugzeiten Buchungssystem - `/assets/screenshot-4.png`
1. Neue Termine hinzufügen - `/assets/screenshot-5.png`
1. Eine Terminserie hinzufügen  - `/assets/screenshot-6.png`

## Changelog ##

### 1.1 ###

* Per Shortcode-Parameter kann jetzt festgelegt werden, welches Jahr gezeigt werden soll
* In den Monatsüberschriften des Dienstplans wird jetzt das Jahr angezeigt
* Beim zuseisen von Diensten wird nun angezeigt wie viele Dienste einem Mitglied bereits zugewiesen sind.

### 1.0 ###

* Administratoren können neue Termine hinzufügen
* Administratoren können neue Terminserien hinzufügen
* Hard-coded Style-Definitionen nach CSS verschoben
* Übersetzungs-Template und deutsche Übersetzung aktualisiert
* Aktualisierte readme.txt
* liesmich.txt hinzugefügt
* Bessere Fehlerbehandlung
* Sicherheitsverbesserungen

### 0.9 ###

* Sicherheitsverbesserungen

### 0.8 ###

* Der heutige Dienst wird mit einer Umrahmung hervorgehoben
* Wenn keine Monate im `[rc-flight-manager-schedule]` shortcode angegeben sind, wird das ganze Jahr gezeigt
* Dienste können nur noch mit Einträgen in der Zukunft getauscht werden
* Fehler im logging korrigiert

### 0.7 ###

* Plugin Tabellen werden bei der Plugin Entfernung aus der Wordpress DB gelöscht
* Flugleiter Dienste können nun hinzugefügt werden
* Dropdown button implementiert, in dem alle Plugin-Funktionen wie Übernehmen, Tauschen und Übergeben, etc. vereint werden
* Funktion zum löschen von Diensten hinzugefügt
* Plugin zeigt vergangene Dienste des aktuellen Monats
* Kommentare/Labels können nun geändert werden
* Kommentare/Labels können jetzt via CSS gestyled werden (p.rcfm-event-label)
* Tabellenüberschriften entfernt, da selbsterklärend

### 0.6 ###

* Vorbereitung für Internationalisierung
* Deutsche Lokalisierung (rc-flight-manager-de_DE.[po|mo] mit deutscher Übersetzung hinzugefügt)
* POT Datei für Übersetzung in andere Sprachen hinzugefügt

### 0.5 ###

* Security improvements
  * Das ändern von Optionen erfordert `manage_options` capability => Administrator Role
  * Dienstplan wird nur mit `read` capability angezeigt => Subscriber Role
  * Benutzer mit `edit_posts`capability Benutzer mit => Contributer Role
* Flugzeiten Reservierungs-Limits können nun angepasst werden

### 0.3 ###

* Widget implementiert, dass den aktuellen Flugleiter in der Sidebar anzeigt

### 0.2 ###

* E-Mail Benachrichtigung kann auf Einstellungs-Seite angepasst werden
* Platzhalter [flightmanger-name] und [flightmanager-duty-date] implementiert
* Shortcode kann nun so konfiguriert werden, dass nur Dienste der nächsten x Monate angezeigt werden
* Dienste werden beim erstellen des Dienstplans nach datum sortiert
* Einfache E-Mail Benachrichtigung zwei und 14 Tage vor dem Dienst implementiert

### 0.1 ###

* Minimum viable product, nur Basis-Funktionalität

## Upgrade Notice ##

None
