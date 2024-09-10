<?php

namespace XackiGiFF\MPEBanSystem\api;


use pocketmine\player\Player;
use XackiGiFF\MPEBanSystem\api\player\BPlayer;

class BanSystem
{
    public function getPlayer(Player $player): BPlayer
    {
        return new BPlayer($player);
    }
}