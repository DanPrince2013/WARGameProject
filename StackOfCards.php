<?php
/**
 * Created by Dan Prince via JetBrains PhpStorm.
 * Date: 5/3/13
 * Time: 8:26 PM
 * Description: 
 */
include_once "Card.php";

class StackOfCards implements iReturnsHTML {
    protected $stack;
    protected $lastCardDrawn;

    function __construct($stack = null)
    {
        $this->stack = $stack;
    }
    public function shuffleCards() {
        shuffle($this->stack);
    }
    public function getCards() {
        return $this->stack;
    }
    public function stackSize(){
        return count($this->stack);
    }
    public function draw() {
        if(empty($this->stack))
            return null;
        $this->lastCardDrawn = array_shift($this->stack);
        return $this->getLastCardDrawn();
    }
    public function haveCardsBeenDrawn(){
        return ! is_null($this->lastCardDrawn);
    }
    public function getLastCardDrawn() {
        return $this->lastCardDrawn;
    }
    public function hasCards() {
        return count($this->stack) > 0;
    }
    public function addCard(Card $card = null) {
        if(is_null($card)) return;
        if(is_null($this->stack))
            $this->stack = array($card);
        else
            array_push($this->stack,$card);
    }
    function __toString() {
        $retStr = '';
        foreach($this->stack as $key => $card) {
            $idx = $key + 1;
            $retStr .= "$idx) {$card}";
        }
        return $retStr;
    }
    public function toHTML()
    {
        $retHTML = '';
        foreach($this->stack as $key => $card) {
            $idx = $key + 1;
            $retHTML .= "$idx) {$card->toHTML()}";
        }
        return $retHTML;
    }
}