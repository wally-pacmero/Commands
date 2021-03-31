<?php

namespace Condense\Loader;

use Condense\CondenseCommand;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as TE;

class Loader extends PluginBase implements Listener {

public function onEnable(){
    $this->getLogger()->info(TE::GREEN ."Condense Enable");
    $this->getServer()->getCommandMap()->register("CondenseCommand", new CondenseCommand());
}

public function onDisable(){
    $this->getLogger()->info(TE::RED ."Condense Disable");
}
}
