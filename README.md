symbb
==

German
===
Ein Symfony 2 basiertes BB Forum
Dieses Bundle ist derzeit noch in Entwicklung!!

Das Bundle Konfiguriert sich fast komplett selber!

Wenn ihr bereits andere Einstellungen habt kann es passieren das diese Überschrieben werden!
In diesem Fall müsst ihr entweder das CoreConfigBundle überschreiben oder deaktivieren und alle YML Dateien bei euch manuell anpassen!

Folgende Einstellungen müssen derzeit manuell vorgenommen werden!
====
- security.yml ( siehe YML Datei in CoreConfigBundle )
- AppKernel ( siehe Sandbox )

English
===
A symfony 2 BB forum
This Bundle is still in development



Aktueller Plan
==

- normale Foren Funktion
-- Foren
-- Kategorien
-- Links
-- Lesen
-- Eröffnen von Themen
-- Antworten auf Themen
-- Editieren von Beiträgen/Themen
-- Löschen von Beiträgen/Themen
-- BBCode
- Beiträge
-- Normale Ansicht
-- Bewertung von Beiträgen ( Daumen hoch/runter )
-- Zitieren von Beiträgen
- BBCodes
-- Normale BBCodes ( Fett, Unterstrichen etc..)
-- Zitat
-- Google Docs
-- Bild mit lightbox
-- Spoiler
-- Video (Youtube etc..)
- Userprofil
-- Avatar
--- Normal
--- Gravatar
-- Signatur incl. BBCode
-- eigene Bildergalerie
-- Ränge ( jeder kann mehrere haben und kann 1 als Haupt makieren )
--- je Beiträge
--- je nach Beitrittsdatum ( alter des Acc. )
--- je nach Bewertungen
-- eigener Blog (Schreiben von Beiträgen incl Kommentar funktion) ( inkl. eigene Url )
- Portal
-- Neue Beiträge
-- Meist besuchte Beiträge
-- best bewertete Beiträge
-- "eigene Blöcke" definierbar je User mit selbst definierbaren filtern
--- z.b neuer Block nur Beiträge aus Forum X,Y und Z
--- im Template sind "positionen" festzulegen wo man die Blocke später dann erstellen kann (z.b mit einer Twig erweiterung getPortalUserBlocks(ABC) )
--- Blocke sind je User verschiebbar
- Feeds
--- RSS
--- ???
- Sitemaps
-- XML
-- HTML
- Kalender
-- Beim erstellen von Beiträgen soll man optional sagen können das es als Kalender Eintrag angelegt wird
-- Dabei kann man einstellen Welche Gruppen es sehen können
-- Der Intervall des Eintragen
-- Anmeldeliste wo die User sich einträgen können
-- Zudem muss der Kalender im Forum/Portal in kleiner Form angezeigt werden ( aktuelle Woche )
-- und es muss eine Seite geben wo man ihn komplett sieht
- Umfragen
-- Es soll ein leichtes Umfrage modul geben ähnlich wie das von PHPBB
- Menu
-- soll flexible gestalltbar sein
-- eigene Einträge sollen erweitert werden können mit pos angabe und es sol möglich sein vorhanden zu deaktivieren
-- Angabe eines Routing Key von Symfony oder ein Direkter Link oder auswahl einer CMS Seite
- CMS
-- Es soll möglich sein eigene "Seiten" anzulegen die über einen normalen WYSIWUG Editor pflegbar ist.
-- Diese Seiten sollen in die Nav. einbaubar sein
-- Meta Daten sollen ebenfalls pflegbar sein
-- Ansonsten ist kein größerer Umfang geplant
- FAQ
-- einfaches FAQ
- Kontaktform
-- einfaches Kontaktformular zum anschreiben des Site Admins/Teams
- AGBs
-- Pflegen von 1-x "AGBs" die bei der Anmeldung akzeptiert werden müssen
-- Standardmässig muss eine Vorlage existieren
- LiveUpdates
-- Über ein socket.io server sollen Infos ausgetauscht werden. Z.b User A schreibt einen neuen Beitrag, User B hat gerade den Topic bzw. das Unterforum auf -> User B bekommt eine Meldung das es neue Beiträge gibt
- Chat
-- Über ein socket.io chat https://github.com/seyon/NodeJsChat
-- Moderatorfunktionen müssen erweitert werden
-- Benachrichtigung wenn Meldungen reinkommen