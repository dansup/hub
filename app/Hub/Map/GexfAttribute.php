<?php namespace App\Hub\Map;

class GexfAttribute {

    public $id = "";
    public $name = "";
    public $value = "";
    public $type = "";

    public function __construct($name, $value, $type = "string") {
        $this->setAttributeName($name);
        $this->setAttributeId($this->name);
        $this->setAttributeValue($value);
        $this->setAttributeType($type);
    }

    public function getAttributeName() {
        return $this->name;
    }

    public function setAttributeName($name) {
        $this->name = str_replace("&", "&amp;", str_replace("'", "&quot;", str_replace('"', "'", strip_tags(trim($name)))));
    }

    public function getAttributeId() {
        return $this->id;
    }

    public function setAttributeId() {
        $this->id = "a-" . md5($this->name);
    }

    public function getAttributeValue() {
        return $this->value;
    }

    public function setAttributeValue($value) {
        $this->value = str_replace("&", "&amp;", str_replace("'", "&quot;", str_replace('"', "'", strip_tags(trim($value)))));
    }

    public function getAttributeType($type) {
        return $this->type;
    }

    public function setAttributeType($type) {
        $this->type = str_replace("&", "&amp;", str_replace("'", "&quot;", str_replace('"', "'", strip_tags(trim($type)))));
    }

}