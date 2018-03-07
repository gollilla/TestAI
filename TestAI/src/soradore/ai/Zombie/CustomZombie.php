<?php 

namespace soradore\ai\Zombie;

use pocketmine\entity\Entity;
use pocketmine\entity\Zombie;
use pocketmine\entity\Human;

use pocketmine\level\Position;
use pocketmine\math\Vector3;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;

class CustomZombie {

    const SPEED = 0.2;

    public $target = NULL;
    public $randomWalk = true;

    public function __construct(Entity $zombie, Human $target = NULL){
        $this->zombie = $zombie;
        if($target == NULL){
            $this->setTarget();
        }
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


    public function attack2target($value = 1){
        if($this->target == NULL) return;
        $event = new EntityDamageByEntityEvent($this->zombie, $this->target, EntityDamageEvent::CAUSE_ENTITY_ATTACK, $value);
        $this->target->attack($event);
    }


    public function getDistance($target = NULL){
        if($target == NULL) $target = $this->target;
        return sqrt($this->zombie->distance($target));
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