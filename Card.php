<?php
/**
 * Created by Dan Prince via JetBrains PhpStorm.
 * Date: 5/4/13
 * Time: 9:00 AM
 * Description: 
 */
include_once "iReturnsHTML.php";

class Card implements iReturnsHTML {
    private $value;
    private $name;
    private $suit;

    function __construct($positionInDeck = -1)
    {
        $value = ($positionInDeck % 13) + 2;
        switch($value) {
            case 11:
                $name = "Jack";
                break;
            case 12:
                $name = "Queen";
                break;
            case 13:
                $name = "King";
                break;
            case 14:
                $name = "Ace";
                break;
            default:
                $name = $value;
        }
        switch(floor($positionInDeck / 13)) {
            case 0:
                $suit = "Clubs";
                break;
            case 1:
                $suit = "Hearts";
                break;
            case 2:
                $suit = "Diamonds";
                break;
            case 3:
                $suit = "Spades";
                break;
            default:
                $suit = "Ooops!";
        }

        $this->value = $value;
        $this->name = $name;
        $this->suit = $suit;
    }
    public function getValue()
    {
        return $this->value;
    }
    public function isLargerThan(Card $card) {
        return $this->value > $card->getValue();
    }
    function __toString()
    {
        return "{$this->name} of {$this->suit}";
    }
    public function toHTML()
    {
        return "{$this->name} of {$this->suit}<br/>";
    }
}