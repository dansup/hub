<?php namespace App\Hub\Map;

use App\Hub\Map\GexfAttribute;
use App\Hub\Map\GexfEdge;
use App\Hub\Map\GexfNode;

define('GEXF_EDGE_DIRECTED', 0);
define('GEXF_EDGE_UNDIRECTED', 1);
define('GEXF_MODE_STATIC', 3);
define('GEXF_MODE_DYNAMIC', 4);
define('GEXF_TIMEFORMAT_DATE', 5);

class Gexf {

    private $title = "";
    private $edgeType = "undirected";
    private $creator = "hub";
    private $mode = "static";
    private $timeformat = false;
    public $nodeObjects = array();
    public $edgeObjects = array();
    private $nodeAttributeObjects = array();
    private $edgeAttributeObjects = array();
    public $gexfFile = "";

    /**
     * 
     * @param int $edgeType either GEXF_EDGE_DIRECTED or GEXF_EDGE_UNDIRECTED
     */
    public function setEdgeType($edgeType) {
        if ($edgeType == GEXF_EDGE_DIRECTED)
            $this->edgeType = 'directed';
        else if ($edgeType == GEXF_EDGE_UNDIRECTED)
            $this->edgeType = 'undirected';
        else
            throw new Exception("Unsupported edge type: $edgeType");
    }

    public function setTitle($title) {
        $this->title = str_replace("&", "&amp;", str_replace("'", "&quot;", str_replace('"', "'", strip_tags(trim($title)))));
    }

    public function setCreator($creator) {
        $this->creator = str_replace("&", "&amp;", str_replace("'", "&quot;", str_replace('"', "'", strip_tags(trim($creator)))));
    }

    public function setMode($mode) {
        if ($mode == GEXF_MODE_STATIC)
            $this->mode = 'static';
        else if ($mode == GEXF_MODE_DYNAMIC)
            $this->mode = 'dynamic';
        else
            throw new Exception("Unsupported mode: $mode");
    }

    public function setTimeFormat($format) {
        if ($format == GEXF_TIMEFORMAT_DATE)
            $this->timeformat = 'date';
        else
            throw new Exception("Unsupported time format: $format");
    }

    public function addNode($node) {
        if (!$this->nodeExists($node))
            $this->nodeObjects[$node->id] = $node;
        //else throw new Exception("Node ".$node->id." already exists");
        return $node->id;
    }

    public function nodeExists($node) {
        return array_key_exists($node->id, $this->nodeObjects);
    }

    /**
     * Add child node
     * 
     * @todo this belongs in GexfNode, not here
     * 
     * @param GexfNode $child
     * @param GexfNode $parent
     * @return string 
     */
    public function addNodeChild($child, $parent) {
        if (!$this->childExists($child, $parent))
            $this->nodeObjects[$parent->id]->addNodeChild($child);
        //else throw new Exception("Child node ".$node->id." already exists");
        return $child->id;
    }

    public function childExists($node, $child) {
        return array_key_exists($child->id, $node->children);
    }

    public function addEdge($source, $target, $weight = 1) {
        $edge = new GexfEdge($source, $target, $weight, $this->edgeType);
        // if edge did not exist, add to list
        if (array_key_exists($edge->id, $this->edgeObjects) == false)
            $this->edgeObjects[$edge->id] = $edge;
        // else add weight to existing edge
        else
            $this->edgeObjects[$edge->id]->addToEdgeWeight($weight);
        return $edge->id;
    }

    public function addEdgeSpell($eid, $start, $end) {
        if (array_key_exists($eid, $this->edgeObjects) == false)
            die('make an edge before you add a spell');
        $this->edgeObjects[$eid]->addEdgeSpell($start, $end);
    }

    // @todo, go through gexf primer to include all options 
    public function render() {
        $nodes = $this->renderNodes($this->nodeObjects);
        $edges = $this->renderEdges($this->edgeObjects);
        $nodeAttributes = $this->renderNodeAttributes();
        $edgeAttributes = $this->renderEdgeAttributes();

        $this->gexfFile = chr(239) . chr(187) . chr(191) . '<?xml version="1.0" encoding="UTF-8"?>
        <gexf xmlns="http://www.gexf.net/1.2draft"
            xmlns:xsi="http://wwww.w3.org/2001/XMLSchema-instance"
            xsi:schemaLocation="http://www.gexf.net/1.2draft 
            http://www.gexf.net/1.2draft/gexf.xds"
            xmlns:viz="http://www.gexf.net/1.2draft/viz"
            version="1.2">
            <meta>
                <creator>' . $this->creator . '</creator>
                <description>' . $this->title . '</description>
            </meta>
            <graph defaultedgetype="' . $this->edgeType . '" mode="' . $this->mode . '"' . (!empty($this->timeformat) ? ' timeformat="' . $this->timeformat . '"' : '') . '>
                ' . $nodeAttributes . '
                ' . $edgeAttributes . '
                ' . $nodes . '
                ' . $edges . '
            </graph>
        </gexf>';
    }

    public function renderNodes($nodeObjects) {
        $xmlNodes = "<nodes>\n";

        foreach ($nodeObjects as $id => $node) {

            $xmlNodes .= '<node id="' . $node->id . '" label="' . $node->name . '">' . "\n";

            // add color
            if ($node->color != array())
                $xmlNodes .= '<viz:color r="' . $node->color['r'] . '" g="' . $node->color['g'] . '" b="' . $node->color['b'] . '" a="' . $node->color['a'] . '"/>';

            // add attributes
            if (count($node->attributes)) {
                foreach ($node->attributes as $attribute) {
                    $xmlNodes .= '<attvalue for="' . $attribute->id . '" value="' . $attribute->value . '"/>' . "\n";

                    if (array_key_exists($attribute->id, $this->nodeAttributeObjects) === false)
                        $this->nodeAttributeObjects[$attribute->id] = $attribute;
                }
            }

            // add spells (the times this node lives)
            if (count($node->spells)) {
                $xmlNodes .= "<spells>\n";
                foreach ($node->spells as $spell) {
                    $xmlNodes .= '<spell' . (isset($spell->startdate) ? ' start="' . $spell->startdate . '"' : '') . (isset($spell->enddate) ? ' end="' . $spell->enddate . '"' : '') . " />\n";
                }
                $xmlNodes .= "</spells>\n";
            }

            // add children
            if (count($node->children)) {
                $xmlNodes .= $this->renderNodes($node->children);
            }

            $xmlNodes .= "</node>\n";
        }
        $xmlNodes .= "</nodes>\n";

        return $xmlNodes;
    }

    public function renderEdges($edgeObjects) {
        $xmlEdges = "<edges>\n";
        foreach ($edgeObjects as $edge) {

            $xmlEdges .= '<edge id="' . $edge->id . '" source="' . $edge->source . '" target="' . $edge->target . '" Weight="' . $edge->weight . '">' . "\n";

            // add attributes
            if (count($edge->attributes)) {
                foreach ($edge->attributes as $attribute) {
                    $xmlEdges .= '<attvalue for="' . $attribute->id . '" value="' . $attribute->value . '"/>' . "\n";

                    if (array_key_exists($attribute->id, $this->edgeAttributeObjects) === false)
                        $this->edgeAttributeObjects[$attribute->id] = $attribute;
                }
            }

            // add spells (the times this edge lives)
            if (count($edge->spells)) {
                $xmlEdges .= "<spells>\n";
                foreach ($edge->spells as $spell) {
                    $xmlEdges .= '<spell' . (isset($spell->startdate) ? ' start="' . $spell->startdate . '"' : '') . (isset($spell->enddate) ? ' end="' . $spell->enddate . '"' : '') . " />\n";
                }
                $xmlEdges .= "</spells>\n";
            }

            $xmlEdges .= "</edge>\n";
        }
        $xmlEdges .= "</edges>\n";

        return $xmlEdges;
    }

    public function renderNodeAttributes() {
        $xmlNodeAttributes = "";
        if (count($this->nodeAttributeObjects)) {
            $xmlNodeAttributes = '<attributes class="node">';
            foreach ($this->nodeAttributeObjects as $attribute) {
                $xmlNodeAttributes .= '<attribute id="' . $attribute->id . '" title="' . $attribute->name . '" type="' . $attribute->type . '"/>' . "\n";
                // @ todo add time attribute
            }
            $xmlNodeAttributes .= "</attributes>\n";
        }
        return $xmlNodeAttributes;
    }

    public function renderEdgeAttributes() {
        $xmlEdgeAttributes = "";
        if (count($this->edgeAttributeObjects)) {
            $xmlEdgeAttributes .= '<attributes class="edge">';
            foreach ($this->edgeAttributeObjects as $attribute) {
                $xmlEdgeAttributes .= '<attribute id="' . $attribute->id . '" title="' . $attribute->name . '" type="' . $attribute->type . '"/>' . "\n";
                // @ todo add time attribute
            }
            $xmlEdgeAttributes .= "</attributes>\n";
        }
        return $xmlEdgeAttributes;
    }

}