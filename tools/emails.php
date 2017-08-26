<?php

if ( ! isset( $_GET['format_email_content'] ) ) {
	return;
}



/*----- to Indycar drivers -------
SUBJECT: Undycar Series (undycar.com) invitation
*/

$message = "Hi  \" + shortened_name + \",
I hope you don't mind the impromptu message, but I saw you sometimes do races in the Indycar series here on iRacing and thought you may be interested in the new Undycar Series which is starting in just over a weeks time. The races are on Tuesdays at 21:00 GMT (see below for other time zones), starting in a week and a half on September 5th. 

[b][size=24][url]https://undycar.com/[/url][/size][/b]

The tracks and car are all free, so no need to buy anything. The car is the free Dallara Indycar (as used in the Dallara Dash series) with a fixed setup. Half the tracks are ovals, and half are road courses.

It would be awesome to see you on the track in the Undycar Series :)


Times in other time zones:
07:00 AEST (Sydney)
14:00 PDT (California)
17:00 EDT (New York)
21:00 GMT
23:00 CEST (Berlin)";


// ---------- To Pro Mazda drivers -------------
$message = "Hi  \" + shortened_name + \",
I hope you don't mind the impromptu message, but I saw you sometimes do races in the Pro Mazda series here on iRacing.
I also drive in that and really enjoy it, but also want to compete in some races in faster more powerful cars so I\'ve created a new league called the Undycar Series and thought you may be interested in it too. It\'s starting in just over a weeks time. The races are on Tuesdays at 21:00 GMT (see below for other time zones), starting in a week and a half on September 5th. 

[b][size=24][url]https://undycar.com/[/url][/size][/b]

The tracks and car are all free, so no need to buy anything. The car is the free Dallara Indycar with a fixed setup. Half the tracks are ovals, and half are road courses.

It would be awesome to see you on the track in the Undycar Series :)


Times in other time zones:
07:00 AEST (Sydney)
14:00 PDT (California)
17:00 EDT (New York)
21:00 GMT
23:00 CEST (Berlin)";


// ---------- To Dallara Dash drivers -------------
$message = "Hi  \" + shortened_name + \",
I hope you don't mind the impromptu message, but I saw you sometimes do races in the Dallara Dash series here on iRacing and thought you may be interested in the new Undycar Series which is starting in just over a weeks time. The races are on Tuesdays at 21:00 GMT (see below for other time zones), starting in a week and a half on September 5th. 

[b][size=24][url]https://undycar.com/[/url][/size][/b]

The tracks and car are all free, so no need to buy anything. The car is the free Dallara Indycar (as used in the Dallara Dash series) with a fixed setup. Half the tracks are ovals, and half are road courses.

It would be awesome to see you on the track in the Undycar Series :)


Times in other time zones:
07:00 AEST (Sydney)
14:00 PDT (California)
17:00 EDT (New York)
21:00 GMT
23:00 CEST (Berlin)";



$message = str_replace( "\n", '\n', $message );
echo $message;
die;


/*
----- to Sim Racers TM on Facebook #1 ----

What are the best rules to have for a sim racing league?

I'm trying to write up the rules for the new Undycar Series starting soon and am keen on some input:
https://undycar.com/rules/

I'd prefer to keep things super simple, as this is intended just as a fun league, so delving into tons of detail and nuances that competitors are never going to read or care about is something I'd like to avoid. But if you think that's the wrong approach please let me know :)






----- to friends -------
SUBJECT: Undycar Series (undycar.com) invitation

MESSAGE:
Hi [NAME],
Are you interested in competing in the Undycar Series? The races are on Tuesdays at 21:00 GMT (see below for other time zones), starting in a week and a half on September 5th. 

[b][size=24][url]https://undycar.com/[/url][/size][/b]

The tracks and car are all free, so no need to buy anything. The car is the free Dallara Indycar (as used in the Dallara Dash series) with a fixed setup. Half the tracks are ovals, and half are road courses.

It would be awesome to see on the track in the Undycar Series :)


Times in other time zones:
07:00 AEST (Sydney)
14:00 PDT (California)
17:00 EDT (New York)
21:00 GMT
23:00 CEST (Berlin)




------- to SRC ----------
SUBJECT: Undycar Series (undycar.com) invitation

MESSAGE:
Hi,
If you want to try out iRacing without costing you a fortune, I'm making a new series called the "Undycar Series" for it which only uses their free cars and tracks (still need the US$5/month membership fee, but no need to buy cars or tracks).

The races are on Tuesdays at 23:00 German time, starting in a week and a half on September 5th. 

There's a website for it here:
https://undycar.com/

The car is a Dallara Indycar and will use a fixed setup. They're super fun to drive and no more difficult than the cars in SRC. Half the races on road courses and half on ovals (just like real Indycars).

Assuming the league is successful, we can use it to try to recruit new members for SRC too as I can pimp it via the Undycar Series website.

*/