<?php

namespace XackiGiFF\MPEBanSystem\api\player;

use pocketmine\player\Player;

class BPlayer
{
    private static BPlayer $instance;
    private Player $player;

    public function __construct(Player $player)
    {
        self::$instance = $this;

        $this->player = $player;
    }

    public function setBanByNick(): void
    {
        $name = $this->player->getName();
        // TODO: do to ban by name
    }
    public function setBanByIP(): void
    {
        $name = $this->player->getName();

        // TODO: do to ban IP
    }

    public function setBanByXUID(): void
    {
        $name = $this->player->getName();

        // TODO: do to ban IP
    }

    public function setMute(int $time) : void
    {
        $name = $this->player->getName();

        // TODO: do to ban IP
    }

    public function unsetBanByNick() : void
    {

    }

    public function unsetBanByIP() : void
    {

    }

    public function unsetBanByXUID() : void
    {

    }

    public function unsetMute() : void
    {

    }

    public function isBannedByNick() : bool {
        $name = $this->player->getName();
        return false;
    }

    public function isBannedByIP() : bool
    {
        return false;
    }

    public function isBannedByXUID() : bool
    {
        return false;
    }

    public function isMute() : void
    {

    }

    public function getBPlayer(): self {
        return self::$instance;
    }

}