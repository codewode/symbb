SymBB Forum System
=============

Genauere Infos und Anleitungen findet ihr hier:

https://github.com/seyon/symbb/wiki/

Initalisieren der Beispieldaten solange es keinen Installer gibt
-------------

Mit folgendem Befehlt könnt ihr die Beispieldaten importieren 

php app/console doctrine:schema:drop --force --full-database --em=symbb --env=dev
php app/console doctrine:schema:update --force --em=symbb --env=dev
php app/console doctrine:fixtures:load --em=symbb --env=dev

Bitte beachtet das --env eure Wunsch umgebung sein muss. Aktuell haben prod, dev sowie test unterschiedliche Datenbank Prefixe damit nicht ungewollt Daten gelöscht werden
Zudem ist zu beachten das die User und Group Daten UNBEDINGT benötigt werden. Die Foren Fixturen sind optional.