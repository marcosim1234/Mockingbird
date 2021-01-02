<?php

namespace ethaniccc\Mockingbird\detections\packet\badpackets;

use ethaniccc\Mockingbird\detections\Detection;
use ethaniccc\Mockingbird\user\User;
use pocketmine\item\ItemIds;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\PlayerAuthInputPacket;

class BadPacketD extends Detection{

    public function __construct(string $name, ?array $settings){
        parent::__construct($name, $settings);
        $this->vlSecondCount = 5;
        $this->lowMax = 0; $this->mediumMax = 2;
    }

    public function handle(DataPacket $packet, User $user): void{
        if($packet instanceof PlayerAuthInputPacket){
            // the player is gliding without anything to glide with - invalid.
            if($user->isGliding && $user->player->getArmorInventory()->getChestplate()->getId() !== ItemIds::ELYTRA){
                if(++$this->preVL >= 1.01){
                    $this->fail($user, "glide=true chestplate={$user->player->getArmorInventory()->getChestplate()->getId()}");
                }
            } else {
                $this->preVL = max($this->preVL - 0.05, 0);
            }
        }
    }

}