SymBB Forum System
=============

Genauere Infos und Anleitungen findet ihr hier:

https://github.com/seyon/symbb/wiki/


Bei Verwendung einer eigenen FOS User Klasse
-------------

In zukunft soll es natürlich möglich sein eine eigene FOS User Klasse anzugeben.

Bzw. idealerweise sollte es auch eine nicht FOS Userklasse geben.

Hierzu sind jedoch folgende Anpassungen noch nötig:

- Doctrine Mapping Extension welche die Annotation Angaben auf die andere Userklasse ummapped
- FOS UserManager durch einen eigenen ersetzen
- alle sonstigen aufrufe z.b Repository aufrufe welche auf das Core Bundle Zeigen über den eigenen UserManager lösen!

Danach muss dann nur auf folgendes geachtet werden:

- implementiert das SymBB\Core\UserBundle\Entity\UserInterface
- es muss ein Attribute mit dem Namen "symbbType" geben. Es genügt nicht! Nur die get Methode zu definieren da über dieses Feld gesucht wird!
- die Userklasse muss die normale FOS Gruppen implementation haben. Falls jemand was eigenes dafür baut kann es sein das die Fixturen beim Installer nicht klappen
- die getSymbbData Methode muss IMMER ein Object zurückliefern! Auch wenn noch keins in der DB gespeichert wurde.
- Die FOS User Entity muss über den Entitymanager "symbb" laufen, da wir datenbank verknüpfungen haben ich ich bezweifle das doctrine mit 2 EM bei Joins oder sonstigem Arbeiten kann..

Initalisieren der Beispieldaten solange es keinen Installer gibt
-------------

Mit folgendem Befehlt könnt ihr die Beispieldaten importieren 

php app/console doctrine:schema:drop --force --full-database --em=symbb --env=dev
php app/console doctrine:schema:update --force --em=symbb --env=dev
php app/console doctrine:fixtures:load --em=symbb --env=dev

Bitte beachtet das --env eure Wunsch umgebung sein muss. Aktuell haben prod, dev sowie test unterschiedliche Datenbank Prefixe damit nicht ungewollt Daten gelöscht werden
Zudem ist zu beachten das die User und Group Daten UNBEDINGT benötigt werden. Die Foren Fixturen sind optional.