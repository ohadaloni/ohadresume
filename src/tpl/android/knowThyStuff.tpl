
knowThyStuff is a collection of simple guessing games
designed for the several age groups.

A picture is presented, and one gets to select what it is
from a multiple choice menu.

The collection of images is stored on an app server,
so the contents of the games is independent of the apps themselves.

Special care is taken to asynchronously fetch the images from the server
while the game has already started.
When you first start a game, the server is queried to initialize a game,
and returns with a json contaning the collection of images the are
the content of the game instance.

The app then fetches the the first image.
Once it is gotten, the app start the asynchroness threads to fetch
all the other images, and starts the game.

Game questions will appear in the order they are received by the device.


<a
	target="googlePlay"
	href="https://play.google.com/store/apps/details?id=com.theora.Musicians"
>Know the Musicians: <img height="50" src="/images/LetItBe.jpg" /></a>

<a
	target="googlePlay"
	href="https://play.google.com/store/apps/details?id=com.theora.GuessWhatAnimal"
>Guess What: Animals: <img height="50" src="/images/nautilus.jpg" /></a>

<br />


