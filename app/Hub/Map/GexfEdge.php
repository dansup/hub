<?php namespace App\Hub\Map;


class GexfEdge {

    public $id = "";
    public $source = "";
    public $target = "";
    public $weight = 1;
    public $attributes = array();
    public $spells = array();
    public $edgeType = "undirected";

    public function __construct($source, $target, $weight, $edgeType) {
        $this->setEdgeSource($source);
        $this->setEdgeTarget($target);
        $this->setEdgeWeight($weight);
        $this->setEdgeType($edgeType);
        $this->setEdgeId();
    }

    public function setEdgeType($edgeType) {
        $this->edgeType = $edgeType;
    }

    public function getEdgeType() {
        return $this->edgeType;
    }

    public function getEdgeSource() {
        return $this->source;
    }

    public function setEdgeSource($source) {
        $this->source = $source->id;
    }

    public function getEdgeTarget() {
        return $this->target;
    }

    public function setEdgeTarget($target) {
        $this->target = $target->id;
    }

    public function getEdgeWeight() {
        return $this->weight;
    }

    public function setEdgeWeight($weight) {
        $this->weight = $weight;
    }

    public function addToEdgeWeight($weight) {
        $this->weight += $weight;
    }

    public function getEdgeId() {
        return $this->id;
    }

    public function setEdgeId() {
        $sort = array($this->source, $this->target);
        if ($this->edgeType == "undirected")   // if undirected all concatenations need to be result in same id
            sort($sort);
        $this->id = "e-" . implode("", $sort);
    }

    public function getEdgeAttributes() {
        return $this->attributes;
    }

    public function addEdgeAttribute($name, $value, $type = "string") {
        $attribute = new GexfAttribute($name, $value, $type);
        $this->attributes[$attribute->id] = $attribute;
    }

    public function getEdgeSpells() {
        return $this->spells;
    }

    public function addEdgeSpell($start, $end) {
        $spell = new GexfSpell($start, $end);
        $this->spells[$spell->getSpellId()] = $spell;
    }

}


