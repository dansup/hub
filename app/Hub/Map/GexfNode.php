<?php namespace App\Hub\Map;

class GexfNode {

    public $id = "";
    public $name = "";
    public $attributes = array();
    public $spells = array();
    public $children = array();
    public $color = array();

    public function __construct($name, $idprefix = null) {
        $this->setNodeName($name);
        $this->setNodeId($idprefix);
    }

    public function getNodeId() {
        return $this->id;
    }

    public function setNodeId($idprefix = null) {
        if (isset($idprefix))
            $this->id = $idprefix . md5($this->name);
        else
            $this->id = "n-" . md5($this->name);
    }

    public function getNodeName() {
        return $this->name;
    }

    public function setNodeName($name) {
        $this->name = str_replace("&", "&amp;", str_replace("'", "&quot;", str_replace('"', "'", strip_tags(trim($name)))));
    }

    public function getNodeAttributes() {
        return $this->attributes;
    }

    public function addNodeAttribute($name, $value, $type = "string") {
        $attribute = new GexfAttribute($name, $value, $type);
        $this->attributes[$attribute->id] = $attribute;
    }

    public function getNodeAttributeValue($attributeName) {
        $attribute = new GexfAttribute($attributeName, "");
        $aid = $attribute->getAttributeId();
        if (isset($this->attributes[$aid]))
            return $this->attributes[$aid]->getAttributeValue();
        else
            return false;
    }

    public function getNodeColor() {
        return $this->color;
    }

    public function setNodeColor($r = 255, $g = 255, $b = 255, $a = 1) {
        $this->color = array("r" => $r, "g" => $g, "b" => $b, "a" => $a);
    }

    public function getNodeSpells() {
        return $this->spells;
    }

    public function addNodeSpell($start, $end) {
        $spell = new GexfSpell($start, $end);
        $this->spells[$spell->getSpellId()] = $spell;
    }

    public function getNodeChildren() {
        return $this->children;
    }

    public function addNodeChild($node) {
        // @todo throw Exception if duplicate node
        $this->children[$node->id] = $node;
    }

}