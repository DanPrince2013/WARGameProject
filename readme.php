<?php
/**
 * Created by Dan Prince
 * Date: 5/4/13
 * Time: 9:22 AM
 * Description: This should help with some of the less obvious things.
 */

echo <<<HTML
<pre>
    I probably took longer on this than the challenge called for. Partially because I had to refresh my PHP (it's been
    over a year doing mainly Java and C#), partially because I just don't like leaving things half done, and partially
    because I just love coding!

    The game is designed to be run as a web application. So, if you're running it from the shell you'll probably see
    some html tags and stuff. I didn't try it so I'm not sure.

    The code is arranged as follows:
        playWar.php - The game is played by running playWar.php. playWar.php is the interface from which you launched
        this readme. playWar.php starts a game of WAR by creating a new WAR class and passing it the number of times
        the deck should be shuffled.

        WAR.php - creates the deck and shuffles it, then deals the deck to the two players. It also handles turn
        management, turn record management, player hand management, and game play.

        StackOfCards.php - The idea was that decks and player's hands had many of the same innate attributes and
        therefore needed to provide many of the same functions. It also made it pretty handy for storing the cards
        that were on the table.

        Deck.php - Decks are derived from StackOfCards and defined as 52 cards from 2 - A in four suits.

        Card.php - Represents a single card. A card has a value, a name, and a suit. The value and suit are initially
        determined by examining the starting position in the deck and implies the presumption that a new deck is
        ordered 2 - Ace by Clubs, Hearts, Diamonds, then Spades when it is created.

        iReturnsHTML.php - an interface that declares 'toHTML()' used by the StackOfCards class and TurnRecord

        TurnRecord.php - keeps track of each turn and the plays that make up the turn. These include the original
        play, and whatever plays result from a war.

        start.php simply begins the session used to pass the game around while you're playing.


    I limit the number of saved turn records to 2000 to prevent running out of memory.

    I limit the number of turns the user can take by using the buttons because if I just allowed them to finish
    the game it could take long enough to time out. If it times out, card loss could occur. HOWEVER, if you look at
    the url, you can set the Count=xxx to whatever you want. WARNING: If you set it to more than say, 300000, you
    could risk timing out.

    *Note: If a player runs out of cards during a war, they lose.

    Happy Playing!

    Dan Prince
    dan@primc.com
</pre>
<a href="WARGameProject.zip"><button>WarGameProject.zip</button></a>
HTML;
