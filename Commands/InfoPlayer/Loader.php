<?php

namespace InfoPlayer\Loader;

use InfoPlayer\InfoPlayerCommand;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as TE;

class Loader extends PluginBase implements Listener {

public function onEnable(){
    $this->getLogger()->info(TE::GREEN ."InfoPlayer Enable");
}

public function onDisable(){
    $this->getLogger()->info(TE::RED ."InfoPlayer Disable");
}
public function __construct(Loader $core) {
        $this->core = $core;
        $this->registerCommand(new AnnounceCommand());
}
}
