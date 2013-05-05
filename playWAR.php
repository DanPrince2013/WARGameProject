<?php
/**
 * Created by Dan Prince via JetBrains PhpStorm.
 * Date: 5/4/13
 * Time: 9:57 AM
 * Description: 
 */
include_once "WAR.php";
include_once "start.php";

$action = $_GET["Action"];
$showLog = $_SESSION["SHOWLOG"];

if($_SESSION["GAME"])
    $game = $_SESSION["GAME"];

switch($action){
    case "PlayATurn":
        $count = $_GET["Count"];
        if($count == "ALL")
            $game->playUntilFinished();
        else
            $game->playTurns($count);
        break;
    case "SeeLog":
        $showLog = !$showLog;
        break;
    case "EndGame":
        session_destroy();
        $game = null;
        break;
    default:
        $shuffleCount = 5;
        $game = new WAR($shuffleCount);
        break;
}

$_SESSION["GAME"] = $game;
$_SESSION["SHOWLOG"] = $showLog;

if(!is_null($game)){

    if($showLog){
        $tmpHTML .= $game->getLogHTML();
        if(strlen($tmpHTML) > 0) {
            $logHTML = "<div style='text-align: center;font-size: 20pt;'><b>Turn Log</b></div>";
            $logHTML .= '<div style="border-top: 2 solid black;">';
            $logHTML .= $tmpHTML;
            $logHTML .= "</span>";
        }
    }

    if($game->getPlayer1Hand()->haveCardsBeenDrawn())
        $player1LastCardDrawnHTML = $game->getPlayer1Hand()->getLastCardDrawn()->toHTML();
    else
        $player1LastCardDrawnHTML = "";

    if($game->getPlayer2Hand()->haveCardsBeenDrawn())
        $player2LastCardDrawnHTML = $game->getPlayer2Hand()->getLastCardDrawn()->toHTML();
    else
        $player2LastCardDrawnHTML = "";

    $seeLogLinkText = $showLog ? "Turn Off Log":"Turn On Log";
    $gameOverText = $game->isGameOver() ? "GAME OVER!" : "";

    $gameHTML = <<<HTML
    <table id="gameTable" border="0">
        <tr>
            <td style="border: 1 solid black">{$game->getPlayer1Hand()->toHTML()}</td>
            <td align="center">
                <div style="position: relative; top: -50px;text-align: center;font-size: 24pt; font-weight: bold;height: 100px;vertical-align: top">
                    <div style="text-align: center;font-size: 10pt;font-weight: normal;">
                        <a href="http://primcsoftwarellc.com/WARGame/playWAR.php">http://primcsoftwarellc.com/WARGame/playWAR.php</a><br/><br/>
                        Created by:
                    </div>
                    Dan Prince<br/>
                    <span style="text-align: center;font-size: 12pt;font-weight: normal;">(816) 564-9595</span>
                    <div><a href="readme.php"><button>Read Me</button></a></div>
                    <div><a href="WARGameProject.zip"><button>WarGameProject.zip</button></a></div>
                </div>
                <table border="0">
                    <tr><td colspan="3" align="center" style="border-bottom: 2 solid black">Turns Played: {$game->getTotalTurnsPlayed()}</td></tr>
                    <tr>
                        <td align="center"><b>Player 1</b></td>
                        <td></td>
                        <td align="center"><b>Player 2</b></td>
                    </tr>
                    <tr>
                        <td align="center">$player1LastCardDrawnHTML</td>
                        <td align="center">vs.</td>
                        <td align="center">$player2LastCardDrawnHTML</td>
                    </tr>
                    <tr>
                        <td colspan="3" align="center" style="border-top: 2 solid black; border-bottom: 2 solid black">{$game->getLastTurnResult()}</td>
                    </tr>
                    <tr>
                        <td colspan="3" height="50" valign="bottom" align="center">
                            <a href="playWAR.php?Action=PlayATurn&amp;Count=1"><button>Play a turn</button></a>
                            <a href="playWAR.php?Action=SeeLog"><button>$seeLogLinkText</button></a>
                            <a href="playWAR.php?Action=NewGame"><button>New Game</button></a>
                            <a href="playWAR.php?Action=EndGame"><button>End Game</button></a>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" valign="bottom" align="center">
                            <a href="playWAR.php?Action=PlayATurn&amp;Count=5"><button>Play 5 turns</button></a>
                            <a href="playWAR.php?Action=PlayATurn&amp;Count=10"><button>Play 10 turns</button></a>
                            <a href="playWAR.php?Action=PlayATurn&amp;Count=25"><button>Play 25 turns</button></a>
                            <a href="playWAR.php?Action=PlayATurn&amp;Count=5000"><button>Play 5000 turns</button></a>
                        </td>
                    </tr>
                </table>
                <div style="font-size:36pt;height:50px;vertical-align:middle;">$gameOverText</div>
            </td>
            <td style="border: 1 solid black">{$game->getPlayer2Hand()->toHTML()}</td>
        </tr>
        <tr>
            <td align="center"><b>Score: {$game->getPlayer1Hand()->stackSize()}</b></td>
            <td></td>
            <td align="center"><b>Score: {$game->getPlayer2Hand()->stackSize()}</b></td>
        </tr>
        <tr>
            <td colspan="3">
                <div>$logHTML</div>
            </td>
        </tr>
    </table><br/>

HTML;

    echo $gameHTML;
}
else {

    $nonGameHTML = <<<HTML
    <div>Thanks for playing!</div>
    <a href="playWAR.php?Action=NewGame"><button>New Game</button>

HTML;

    echo $nonGameHTML;
}