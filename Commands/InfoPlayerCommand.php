<?php

namespace Commands\InfoPlayerCommand;

use \;

use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\{Config, TextFormat as TE};
use pocketmine\Player;

class InfoPlayerCommand extends PluginCommand {

    /** @var Loader */
    protected $plugin;

    /**
     * InfoPlayerCommand Constructor.
     * @param 
     */
    public function __construct(){
        parent::__construct("co", Loader::getInstance());
        $this->setPermission("co.command.use");
    } 

    /**
     * @param CommandSender $sender
     * @param String $commandLabel
     * @param Array $args
     * @return bool|mixed
     */
    public function execute(CommandSender $sender, String $commandLabel, Array $args){
        if(!$sender->hasPermission("co.command.use")){
            $sender->sendMessage(TE::RED."co.command.use");
            return;
        }
        if(!isset($args[0])){
            $sender->sendMessage(TE::RED."Usage: /co help");
            return;
        }
        if($args[0] === "commands" or $args[0] === "cmd"){
            if(!$sender->hasPermission("co.command.use")){
                $sender->sendMessage(TE::RED."co.command.use");
                return;
            }
            if(!isset($args[1])||!isset($args[2])){
                $sender->sendMessage(TE::RED."Usage: /co commands <playerName> <list of page>");
                return;
            }
            if(!is_numeric($args[2])){
                $sender->sendMessage(TE::RED."You must enter a number!");
                return;
            }
            $this->sendPaginateText($sender, $args);
        }
        elseif($args[0] === "device"){
            if(!$sender->hasPermission("co.command.use")){
                $sender->sendMessage(TE::RED."co.command.use");
                return;
            }
            if(!isset($args[1])){
                $sender->sendMessage(TE::RED."Usage: /co device <playerName>");
                return;
            }
            $player = Loader::getInstance()->getServer()->getPlayer($args[1]);
            if($player === null){
                $sender->sendMessage(TE::RED."The player you are looking for is not connected!");
                return;
            }
            $sender->sendMessage(TE::BOLD.TE::GOLD.$player->getName().TE::RESET.TE::GRAY." is playing with the device: ".TE::YELLOW.$this->getDevice($player));
        }
        elseif($args[0] === "help" or $args[0] === "?"){
            if(!$sender->hasPermission("co.command.use")){
                $sender->sendMessage(TE::RED."co.command.use");
                return;
            }
            $sender->sendMessage(TE::YELLOW."Usage: /co commands".TE::RESET." ".TE::GRAY."(To view a player's commands)");
            $sender->sendMessage(TE::YELLOW."Usage: /co device".TE::RESET." ".TE::GRAY."(to see the player's device)");
            $sender->sendMessage(TE::YELLOW."Usage: /co blocks".TE::RESET." ".TE::GRAY."(To see the blocks put by a player)");
        }else{
            $sender->sendMessage(TE::RED."Invalid Arguments!");
        }
    }

    /**
     * @param CommandSender $sender
     * @return void
     */
    protected function sendPaginateText(CommandSender $sender, Array $args, ?Int $pageHeight = 5){
        $config =  new Config(Loader::getInstance()->getDataFolder()."CommandsData.yml", Config::YAML);
        if(Loader::getInstance()->getServer()->getPlayer($args[1]) instanceof Player){
            $logs = $config->get(strtolower(Loader::getInstance()->getServer()->getPlayer($args[1])->getName()), []);
            //TODO:
            $countPage = 0;
            $countData = 0;

            $maxPage = round((count($logs)) / 5);
            $pageNumber = (int)min($maxPage, max(1, $args[2]));
            if(count($logs) > 1){
                $sender->sendMessage(TE::GRAY."Page ".TE::BOLD.TE::GREEN.$pageNumber.TE::RESET.TE::GRAY." of ".TE::BOLD.TE::GREEN.$maxPage.TE::RESET.TE::GRAY." pages!");
                foreach($logs as $result){
                    $data = (int)round($countData / 5);
                    if($data === $pageNumber){
                        if($countPage !== 0){
                            $sender->sendMessage(TE::BLUE."[".$result["date"]."]".TE::RESET." ".TE::GOLD.Loader::getInstance()->getServer()->getPlayer($args[1])->getName().TE::GRAY." I use the command: ".TE::WHITE.$result["command_id"]);
                        }
                    }else{
                        if($args[2] > $pageNumber){
                            $sender->sendMessage(TE::RED."There is only a {$maxPage} limit on the records");
                            return;
                        }
                    }
                    $countPage++;
                    $countData++;
                }
            }else{
                $sender->sendMessage(TE::RED.Loader::getInstance()->getServer()->getPlayer($args[1])->getName()." never used commands");
            }
        }else{
            $logs = $config->get(strtolower(Loader::getInstance()->getServer()->getOfflinePlayer($args[1])->getName()), []);
            //TODO:
            $countPage = 0;
            $countData = 0;

            $maxPage = round((count($logs)) / 5);
            $pageNumber = (int)min($maxPage, max(1, $args[2]));
            if(count($logs) > 1){
                $sender->sendMessage(TE::GRAY."Page ".TE::BOLD.TE::GREEN.$pageNumber.TE::RESET.TE::GRAY." of ".TE::BOLD.TE::GREEN.$maxPage.TE::RESET.TE::GRAY." pages!");
                foreach($logs as $result){
                    $data = (int)round($countData / 5);
                    if($data === $pageNumber){
                        if($countPage !== 0){
                            $sender->sendMessage(TE::BLUE."[".$result["date"]."]".TE::RESET." ".TE::GOLD.Loader::getInstance()->getServer()->getOfflinePlayer($args[1])->getName().TE::GRAY." I use the command: ".TE::WHITE.$result["command_id"]);
                        }
                    }else{
                        if($args[2] > $pageNumber){
                            $sender->sendMessage(TE::RED."There is only a {$maxPage} limit on the records");
                            return;
                        }
                    }
                    $countPage++;
                    $countData++;
                }
            }else{
                $sender->sendMessage(TE::RED.Loader::getInstance()->getServer()->getOfflinePlayer($args[1])->getName()." never used commands");
            }
        }
    }

    /**
     * @param Player $player
     */
    protected function getDevice(Player $player){
        if(!isset(Loader::$device[$player->getName()])) return;
        $device = Loader::$device[$player->getName()];
        if(is_int($device)){
            return $this->getVersionDevice($device);
        }else{
            return $device;
        }
    }

    /**
     * @param String $device
     */
    protected function getVersionDevice($device){
        if($device === 1){
            $d = "Android";
        }
        elseif($device === 2){
            $d = "iOS";
        }
        elseif($device === 3){
            $d = "Mac";
        }
        elseif($device === 4){
            $d = "FireIOS";
        }
        elseif($device === 5){
            $d = "GearVR";
        }
        elseif($device === 6){
            $d = "Hololens";
        }
        elseif($device === 7){
            $d = "Windows_10";
        }
        elseif($device === 8){
            $d = "Windows_7";
        }
        elseif($device === 9){
            $d = "NoName";
        }
        elseif($device === 10){
            $d = "PlayStation_4";
        }else{
            $d = "Not_Registered";
        }
        return $d;
    }
}

?>
