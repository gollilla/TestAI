<?php 

namespace soradore\ai\CustomEntities;

interface CustomEntity {

    public function attack2target();

    public function getX();

    public function getY();

    public function getZ();

    public function setPitch($deg);

    public function setYaw($deg);
}