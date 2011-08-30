<?php /* #?ini charset="utf-8"?

[ezjscServer]
FunctionList[]=tuteisocial_rate
FunctionList[]=tuteisocial_user_has_rated
FunctionList[]=stats
FunctionList[]=masterserver

[ezjscServer_tuteisocial]
# Url to test this server function(rate):
# <root>/ezjscore/call/tuteisocial::rate::<contentobjectattribute_id>::<version>::<rating>
Class=qaVoteServerCallFunctions
Functions[]=tuteisocial
PermissionPrFunction=enabled
File=extension/tuteisocial/classes/qavoteservercallfunctions.php


[ezjscServer_stats]
Class=StatsServerCallFunctions
Functions[]=stats
File=extension/tuteisocial/classes/statsservercallfunctions.php

[ezjscServer_masterserver]
Class=MasterServerCallFunctions
Functions[]=masterserver
File=extension/tuteisocial/classes/masterservercallfunctions.php

*/ ?>
