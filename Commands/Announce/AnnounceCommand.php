<?php

namespace Commands\AnnounceCommand;

use \Loader;

use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\TextFormat as TE;

use CortexPE\DiscordWebhookAPI\Message;
use CortexPE\DiscordWebhookAPI\Embed;
use CortexPE\DiscordWebhookAPI\Webhook;

class AnnounceCommand extends PluginCommand{
    public function __construct(){
        parent::__construct("alert", Loader::getInstance());
    }
    public function execute(CommandSender $sender, String $label, Array $args) : void {
        if(!$sender->isOp()){
            $sender->sendMessage(TE::RED."You have not permissions to use this command");
            return;
        }
        if(empty($args[0])) {
                $sender->sendMessage(TE::RED . "Use: /{$label} <message>");
                return;
        }
        $alert = implode(" ", $args);
        $this->sendWebhook($alert, $sender->getName());
        Loader::getInstance()->getServer()->broadcastMessage("§8[§4Alert] &e" . $alert);
    }
    public function sendWebhook(String $message, String $playerSender){
        $webhook = new Webhook("https://discord.com/api/webhooks/EDIT_ME/EDIT_ME");
        $msg = new Message();

        $msg->setUsername("Alerts");

        $embed = new Embed();

        $embed->setTitle("New Alert!");
        $embed->setDescription($message);
        $embed->setFooter($playerSender);

        $msg->addEmbed($embed);

        $webhook->send($msg);
    }
}
