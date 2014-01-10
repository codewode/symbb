# SymBB Forum System


More detailed information and instructions can be found here (currently only in German):

https://github.com/seyon/symbb/wiki/

For a "finished" Symfony version you can use the Sandbox :

https://github.com/seyon/symbb_sandbox

# Bundles are used

- FOSUserBundle ( optional, but recommended )
- FOSRestBundle ( for future api )
- FOSJsRoutingBundle 
- FOSMessageBundle (PM System)
- KnpMenuBundle
- KnpPaginatorBundle
- SonataIntlBundle ( Date formating )
- FMBbcodeBundle (BBCodes)
- LswMemcacheBundle (Memcache Manager)

# Demo

A demo of the latest features can be found here :

http://symbb.de/

As long as no beta is , the page serves as a demo. The data is cleared regularly.
In addition, the version can easily lag behind the current version since I only update when the Dev is reasonably stable .

# Init the sample data as long as there is no installer


With the following command should you can import the sample data

 php app/console doctrine:schema:drop --force --full-database --em=symbb --env=dev

 php app/console doctrine:schema:update --force --em=symbb --env=dev

 php app/console doctrine:fixtures:load --em=symbb --env=dev


Please note that " -env " is your wish environment. 
Also note that the User and Group Data are required. The forums fixtures are optional.