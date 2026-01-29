<?php

abstract class Command {
    protected $name;
    protected $description;

    public function __construct($name, $description) {
        $this->name = $name;
        $this->description = $description;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
    }

    abstract public function execute($args);
}
