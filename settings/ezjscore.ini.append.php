<?php /* #?ini charset="utf-8"?

[ezjscServer]
FunctionList[]=dappsocial_rate
FunctionList[]=dappsocial_user_has_rated
FunctionList[]=stats
FunctionList[]=masterserver

[ezjscServer_dappsocial]
# Url to test this server function(rate):
# <root>/ezjscore/call/dappsocial::rate::<contentobjectattribute_id>::<version>::<rating>
Class=qaVoteServerCallFunctions
Functions[]=dappsocial
PermissionPrFunction=enabled
File=extension/dappsocial/classes/qavoteservercallfunctions.php


[ezjscServer_stats]
Class=StatsServerCallFunctions
Functions[]=stats
File=extension/dappsocial/classes/statsservercallfunctions.php

[ezjscServer_masterserver]
Class=MasterServerCallFunctions
Functions[]=masterserver
File=extension/dappsocial/classes/masterservercallfunctions.php

*/ ?>