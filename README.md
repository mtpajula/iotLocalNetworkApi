# iotLocalNetworkApi

api.php: Simple php json rest api.
create.php: Create tables for iot local network -project.

If api gets POST to table "command", it runs console command. New command received.

Using api:

    api.php/[tablename]/[id]

HTTP methods available: GET, POST, PUT, DELETE

Creating tables: run create.php

## Installing

    cp settings.demo.php settings.php

in **settings.php** set your PDO-MySQL -login infromation.
