<?php

namespace ;

use \
use \;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as TE;

class Loader extends PluginBase implements Listener {

public function onEnable(){
    $this->getLogger()->info(TE::GREEN ."Announce Enable");
}

public function onDisable(){
    $this->getLogger()->info(TE::RED ."Announce Disable");
}
}

