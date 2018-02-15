# iotLocalNetworkApi

api.php: Simple php json rest api.
create.php: Create tables for iot local network -project.

If api gets POST to table "command", it runs console command. New command received.

Using api:

    api.php/[tablename]/[id]

Creating tables: run create.php

## Installing

in LocalApi.php class construct -method, set your PDO db -connection.
