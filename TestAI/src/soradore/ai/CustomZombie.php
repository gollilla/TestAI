<?php 

namespace soradore\ai;

use pocketmine\entity\Entity;
use pocketmine\entity\Zombie;

class CustomZombie {

    const SPEED = 0.2;

    public $target = NULL;

    public function __construct(Entity $zombie, $target = null){
        $this->zombie = $zombie;
        $this->target = $target;
    }


    public function getX(){
        return $this->zombie->x;
    }

    public function getY(){
        return $this->zombie->y;
    }

    public function getZ(){
        return $this->zombie->z;
    }

    public function setPitch($deg){
        $this->zombie->pitch = $deg;
    }


    public function setYaw($deg){
        $this->zombie->yaw = $deg;
    }


    public function getLevel(){
        return $this->zombie->level;
    }


    public function getDirection(){
        return $this->zombie->getDirection();
    }


    public function setTarget(){
        $level = $this->zombie->getLevel();
        $target = $level->getNearestEntity($this->zombie, 10);
        if($target instanceof Zombie){
            $target = NULL;
        }
        $this->target = $target;
        var_dump($target);
        return $this->target;
    }


    public function getTarget(){
        return $this->target;
    }


    public function move($x, $y, $z){
        $this->zombie->move($x, $y, $z);
    }

}