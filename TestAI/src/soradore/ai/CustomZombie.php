<?php 

namespace soradore\ai;

use pocketmine\entity\Entity;
use pocketmine\entity\Zombie;
use pocketmine\entity\Human;

use pocketmine\level\Position;
use pocketmine\math\Vector3;

class CustomZombie {

    const SPEED = 0.2;

    public $target = NULL;
    public $randomWalk = true;

    public function __construct(Entity $zombie, $target = NULL){
        $this->zombie = $zombie;
        if($target == NULL){
            $target = $this->setTarget();
        }
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
        $target = $level->getNearestEntity($this->zombie, 10, Human::class);
        $this->target = $target;
        return $this->getTarget();
    }


    public function getTarget(){
        return $this->target;
    }


    public function move($x, $y, $z){
        $this->zombie->move($x, $y, $z);
    }

}