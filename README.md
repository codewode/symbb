SymBB Forum System
=============

Genauere Infos und Anleitungen findet ihr hier:

https://github.com/seyon/symbb/wiki/SymBB-Foren-System


Bei Verwendung einer eigenen FOS User Klasse
-------------
Generell ist es möglich die User Entity aus dem SymBB Bundle durch eine eigene zu ersetzen. 
Jedoch muss auf folgendes geachtet werden:
- implementiert das SymBB\Core\UserBundle\Entity\UserInterface
- es muss ein Attribute mit dem Namen "symbbType" geben. Es genügt nicht! Nur die get Methode zu definieren da über dieses Feld gesucht wird!
- die Userklasse muss die normale FOS Gruppen implementation haben. Falls jemand was eigenes dafür baut kann es sein das die Fixturen beim Installer nicht klappen
- die getSymbbData Methode muss IMMER ein Object zurückliefern! Auch wenn noch keins in der DB gespeichert wurde.
- Die FOS User Entity muss über den Entitymanager "symbb" laufen und ggf. müssen wir einpaar Bundles anpassen... da evt. an ein paar stellen der SymbbUser Bundle name benutzt wird

Initalisieren der Beispieldaten solange es keinen Installer gibt
-------------

Mit folgendem Befehlt könnt ihr die Beispieldaten importieren 

php app/console doctrine:schema:drop --force --full-database --em=symbb --env=dev
php app/console doctrine:schema:update --force --em=symbb --env=dev
php app/console doctrine:fixtures:load --em=symbb --env=dev

Bitte beachtet das --env eure Wunsch umgebung sein muss. Aktuell haben prod, dev sowie test unterschiedliche Datenbank Prefixe damit nicht ungewollt Daten gelöscht werden
Zudem ist zu beachten das die User und Group Daten UNBEDINGT benötigt werden. Die Foren Fixturen sind optional.