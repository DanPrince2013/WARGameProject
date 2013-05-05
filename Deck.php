<?php
/**
 * Created by Dan Prince via JetBrains PhpStorm.
 * Date: 5/4/13
 * Time: 9:43 AM
 * Description: 
 */
include_once "StackOfCards.php";

class Deck extends StackOfCards {

    function __construct()
    {
        $this->stack = array_map( function ($deckPosition) {
            return new Card($deckPosition);
        }, range(0, 51));
    }
}