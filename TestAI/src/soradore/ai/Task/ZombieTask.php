<?php

namespace soradore\ai\Task;

use pocketmine\scheduler\PluginTask;
use pocketmine\plugin\PluginBase;

use pocketmine\block\Block;

use soradore\ai\CustomEntities\CustomZombie;

class ZombieTask extends PluginTask{

    public function __construct(PluginBase $plugin, CustomZombie $zombie){
        $this->zombie = $zombie;
        parent::__construct($plugin);
    }

    public function onRun(int $currentTick){
        $target = $this->zombie->setTarget();
        $level = $this->zombie->getWorld();
        if($target == NULL) return;

        $tx = $target->x;
        $tz = $target->z;

        $cx = $this->zombie->getX();
        $cz = $this->zombie->getZ();

        if((0 <= $cx && 0 <= $tx) || ($cx < 0 && $tx < 0)){
            if($tx < $cx){
                $x = -($cx - $tx);
            }else{
                $x = $tx - $cx;
            }
        }else if(0 <= $cx && $tx < 0){
            $x = -(abs($tx) + $cx);
        }else if($cx < 0 && 0 <= $tx){
            $x = abs($cx) + $tx;
        }


        if((0 <= $cz && 0 <= $tz) || ($cz < 0 && $tz < 0)){
            if($tz < $cz){
                $z = -($cz - $tz);
            }else{
                $z = $tz - $cz;
            }
        }else if(0 <= $cz && $tz < 0){
            $z = -(abs($tz) + $cz);
        }else if($cz < 0 && 0 <= $tz){
            $z = abs($cz) + $tz;
        }

        $rad = atan2($x, $z);

        $x = CustomZombie::SPEED * sin($rad);
        $y = 0;
        $z = CustomZombie::SPEED * cos($rad);

        if($this->zombie->getFrontBlock() !== Block::AIR && $this->zombie->getFrontBlock(1) == Block::AIR && $this->zombie->getFrontBlock(2) == Block::AIR){
            $y += 0.5;
        }

        $this->zombie->setYaw(-rad2deg($rad));
        
        if($this->zombie->getDistance() <= 1.5){
        	$this->zombie->attack2target();
        	return;
        }
        $this->zombie->move($x, $y, $z);
        $this->zombie->setNameTag();
    }

}