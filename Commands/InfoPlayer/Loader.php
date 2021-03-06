<?php

namespace InfoPlayer\Loader;

use InfoPlayer\InfoPlayerCommand;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as TE;

class Loader extends PluginBase implements Listener {

public function onEnable(){
    $this->getLogger()->info(TE::GREEN ."InfoPlayer Enable");
    $this->getServer()->getCommandMap()->register("InfoPlayerCommand", new InfoPlayerCommand());
}

public function onDisable(){
    $this->getLogger()->info(TE::RED ."InfoPlayer Disable");
}
}
