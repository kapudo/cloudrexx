<?php

/**
 * 
 */

namespace Cx\Core\Html\Model\Entity;

/**
 * 
 */
class HtmlElement {
    private $name;
    private $attributes = array();
    private $children = array();
    private $output = null;
    
    public function __construct($elementName) {
        $this->setName($elementName);
    }
    
    public function getName() {
        return $this->name;
    }
    
    protected function setName($elementName) {
        $this->output = null;
        $this->name = $elementName;
    }
    
    public function setAttribute($name, $value = null) {
        if ($value === null) {
            $value = $name;
        }
        $this->output = null;
        $this->attributes[$name] = $value;
    }
    
    public function setAttributes($attributes) {
        $this->output = null;
        $this->attributes += $attributes;
    }
    
    public function getAttribute($name) {
        if (!isset($this->attributes[$name])) {
            return null;
        }
        return $this->attributes[$name];
    }
    
    public function getAttributes() {
        return $this->attributes;
    }
    
    public function getChildren() {
        return $this->children;
    }
    
    public function addChild(HtmlElement $element) {
        $this->output = null;
        $this->children[] = $element;
    }
    
    public function addChildren(array $elements) {
        $this->children += $elements;
    }
    
    /* addChildAfter, removeChild, getNthChild */
    
    public function render() {
        if ($this->output) {
            return $this->output;
        }
        $template = new \Cx\Core\Html\Sigma(ASCMS_CORE_PATH.'/Html/View/Template');
        $template->loadTemplateFile('HtmlElement.html');
        $parsedChildren = null;
        foreach ($this->getChildren() as $child) {
            $parsedChildren .= $child->render();
        }
        $template->setVariable(array(
            'ELEMENT_NAME' => $this->name,
        ));
        if ($parsedChildren === null) {
            $template->hideBlock('children');
            $template->touchBlock('nochildren');
        } else {
            $template->hideBlock('nochildren');
            $template->setVariable(array(
                'CHILDREN' => $parsedChildren,
            ));
        }
        foreach ($this->getAttributes() as $name=>$value) {
            $template->setVariable(array(
                'ATTRIBUTE_NAME' => $name,
                'ATTRIBUTE_VALUE' => $value,
            ));
            $template->parse('attribute');
        }
        $this->output = $template->get();
        return $this->output;
    }
    
    public function __toString() {
        return $this->render();
    }
}
