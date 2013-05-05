<?php
/**
 * Created by Dan Prince via JetBrains PhpStorm.
 * Date: 5/4/13
 * Time: 11:23 AM
 * Description: 
 */
include_once "iReturnsHTML.php";

class TurnRecord implements iReturnsHTML {
    private $plays;

    function __construct($sentence)
    {
        $this->plays = array();
        $this->addPlay($sentence);
    }
    public function addPlay($sentence){
        array_Push($this->plays,$sentence);
    }
    public function toHTML()
    {
        $retHTML = '';
        foreach ($this->plays as $play) {
            $retHTML .= "$play<br/>";
        }
        return $retHTML;
    }
}