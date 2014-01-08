# SymBB Forum System


More detailed information and instructions can be found here (currently only in German):

https://github.com/seyon/symbb/wiki/

For a "finished" Symfony version you can use the Sandbox :

https://github.com/seyon/symbb_sandbox

# demo

A demo of the latest features can be found here :

http://symbb.de/

As long as no beta is , the page serves as a demo. The data to be reset regularly provide .ç
In addition, the version can easily lag behind the current version since I only update when the Dev is reasonably stable .

# Init the sample data as long as there is no installer


With the following command should you can import the sample data

 php app/console doctrine:schema:drop --force --full-database --em=symbb --env=dev

 php app/console doctrine:schema:update --force --em=symbb --env=dev

 php app/console doctrine:fixtures:load --em=symbb --env=dev


Please note that - your request must be env environment . Unwanted data will be deleted currently have prod, dev and not test different database prefixes so
Also note that the User and Group Data MUST be required. The forums fixtures are optional.