<?php
/**
 * Created by Dan Prince via JetBrains PhpStorm.
 * Date: 5/4/13
 * Time: 9:38 AM
 * Description: 
 */

include_once "Deck.php";
include_once "TurnRecord.php";
include_once "StackOfCards.php";

class WAR {
    const MAX_RECORDS = 2000;
    private $player1Hand;
    private $player2Hand;
    private $playedCards;
    private $turns;
    private $lastTurn;
    private $lastTurnResult;
    private $totalTurns;

    function __construct($shuffleTimes = 1)
    {
        $this->turns = array();
        $deck = new Deck();
        for($i = 0; $i < $shuffleTimes; $i++) {
            $deck->shuffleCards();
        }
        $this->deal($deck);
    }
    private function deal(Deck $deck){
        $this->player1Hand = new StackOfCards();
        $this->player2Hand = new StackOfCards();
        while($deck->hasCards()){
            $this->player1Hand->addCard($deck->draw());
            $this->player2Hand->addCard($deck->draw());
        }
    }
    public function getPlayer1Hand()
    {
        return $this->player1Hand;
    }
    public function getPlayer2Hand()
    {
        return $this->player2Hand;
    }
    public function getLogHTML(){
        $retHTML = "";
        $reversedTurnsArray = array_reverse($this->turns);

        $idx = $this->totalTurns;
        $idxMin = $idx - self::MAX_RECORDS;
        foreach($reversedTurnsArray as $turnRecord){
            $retHTML .= "<b>Turn #{$idx})</b> {$turnRecord->toHTML()}";
            if(--$idx < $idxMin)
                break;
        }
        return $retHTML;
    }
    public function getTotalTurnsPlayed(){
        return $this->totalTurns;
    }
    public function getLastTurnResult(){
        return $this->lastTurnResult;
    }
    public function playUntilFinished(){
        while(!$this->isGameOver()) {
            $this->playTurn();
        }
    }
    public function playTurns($count = 1, TurnRecord $turnRecord = null){
        for($i = 0; $i < $count; $i++){
            if(!$this->isGameOver())
                $this->playTurn();
            else {
                break;
            }
        }
    }
    private function playTurn(TurnRecord $turnRecord = null){
        if($this->isGameOver()){
                    # There are 3 functions calling playTurn and 2
                    # of them check for gameOver before calling.
                    # Therefore, if we make it here, it was the 3rd
                    # function, 'itsAWar' that called us.
            $this->cleanUpAfterAnAbortedWar($turnRecord);
            return;
        }

        $player1Card = $this->player1Hand->draw();
        $player2Card = $this->player2Hand->draw();
        $this->addWarCard($player1Card);
        $this->addWarCard($player2Card);

        $turnRecord = $this->prepareTurnRecord($turnRecord,$player1Card,$player2Card);

        if($player1Card->isLargerThan($player2Card)){
            $this->processWin($turnRecord, "Player 1 WON!",$this->player1Hand);
        }
        elseif($player2Card->isLargerThan($player1Card)){
            $this->processWin($turnRecord, "Player 2 WON!",$this->player2Hand);
        }
        else {
            $this->itsAWar($turnRecord);
        }
    }
    public function getWinner(){
        if($this->player1Hand->stackSize() > 0)
            return "Player 1";
        else
            return "Player 2";
    }
    public function isGameOver(){
        $gameOver = ! ($this->player1Hand->hasCards() && $this->player2Hand->hasCards());
        return $gameOver;
    }
    public function itsAWar(TurnRecord $turnRecord){
        $playResult = "The play resulted in a WAR!";
        $this->lastTurnResult .= $playResult;
        $turnRecord->addPlay($playResult);

        $this->lastTurnResult .= "<br/>";

        $this->addWarCard($this->player1Hand->draw());
        $this->addWarCard($this->player2Hand->draw());

        $this->playTurn($turnRecord);
    }
    public function addWarCard(Card $card = null){
        if(is_null($this->playedCards)){
            $this->playedCards = new StackOfCards();
        }

        $this->playedCards->addCard($card);
    }
    public function processWin(TurnRecord $turnRecord,$result,StackOfCards $playerHand){
        $lastPlayResult = $result;
        $this->lastTurnResult .= $lastPlayResult;
        $turnRecord->addPlay($lastPlayResult);
        $this->collectCards($playerHand,$turnRecord);
    }
    public function prepareTurnRecord(TurnRecord $turnRecord = null,Card $card1,Card $card2){
        $playText = $this->getPlayText($card1,$card2);
        if(is_null($turnRecord)){
            $turnRecord = new TurnRecord($playText);
            $this->addTurn($turnRecord);
            $this->lastTurn = $turnRecord;
            $this->lastTurnResult = "";
        }
        else
            $turnRecord->addPlay($playText);

        return $turnRecord;
    }
    private function addTurn(TurnRecord $turnRecord){
        $this->totalTurns++;
        array_push($this->turns,$turnRecord);
        while(count($this->turns) > self::MAX_RECORDS){
            array_shift($this->turns);
        }
    }
    private function getPlayText(Card $card1,Card $card2){
        return "Player 1 played the $card1. Player 2 played the $card2.";
    }
    public function collectCards(StackOfCards $playerHand, TurnRecord $turnRecord){
        if(is_null($this->playedCards))
            return;

        $recordString = "Cards taken:<br/> ";
        $warCardRowCount = 0;
        while($this->playedCards->hasCards()){
            $card = $this->playedCards->draw();
            $warCardRowCount++;
            $recordString .= "$card, ";
            $playerHand->addCard($card);
            if($warCardRowCount == 6 && $this->playedCards->hasCards()){
                $warCardRowCount = 0;
                $recordString .= "<br/>";
            }
        }
        $recordString = substr($recordString,0,strlen($recordString)-2).".";
        $turnRecord->addPlay($recordString);
        $this->lastTurnResult .= "<br/>$recordString";
        $this->playedCards = null;
    }
    private function cleanUpAfterAnAbortedWar(TurnRecord $turnRecord)
    {
        if(is_null($turnRecord))
            echo "This should not be the case. Turn Record was NULL during a war.";
        else{

            if($this->player1Hand->hasCards())
                $turnRecord->addPlay("Player 2 ran out of cards.");
            else
                $turnRecord->addPlay("Player 1 ran out of cards.");

            if($this->player1Hand->hasCards() && $this->player1Hand->stackSize() < 52)
                $this->collectCards($this->player1Hand,$turnRecord);
            elseif( $this->player2Hand->stackSize() < 52)
                $this->collectCards($this->player2Hand,$turnRecord);

            $this->lastTurnResult = "Game Over! ";
            $this->lastTurnResult .= "{$this->getWinner()} wins.";
            $turnRecord->addPlay($this->lastTurnResult);
        }

    }
}