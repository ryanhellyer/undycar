
//alert('test');

/* WORKING
document.getElementsByName('toUsername' )[0].value = 'Ryan Hellyer';
document.getElementsByName('subject')[0].value = 'Test subject';
document.getElementsByName('message')[0].value = 'Can I send messages to myself?';
document.getElementById('btnSubmit').click();
*/


// Add data
if ( window.location.href === 'http://members.iracing.com/jforum/pm/send.page' ) {

	// Process data
	chrome.storage.sync.get({
		members_list: '',
	}, function(items) {

		var members_list_array = items.members_list.split(",");

		// If empty members list, then bail out
		items.members_list.replace(/\s+/, "") 
		if ( '' === items.members_list ) {
			return items;
		}

		var member_name = members_list_array[0];

		var random_time_between_2_and_10_secs = 10000 + ( Math.random() * 3000 );

		setTimeout( function() {

			var shortened_name = member_name.split( ' ' );
			shortened_name = shortened_name[0];

			document.getElementsByName('toUsername' )[0].value = member_name;
			document.getElementsByName('subject')[0].value = 'Undycar Series (undycar.com) invitation';
			document.getElementsByName('message')[0].value = "Hi  " + shortened_name + ",\nI hope you don't mind the impromptu message, but I saw you sometimes do races in the Pro Mazda series here on iRacing.\nI also drive in that and really enjoy it, but also want to compete in some races in faster more powerful cars so I\'ve created a new league called the Undycar Series and thought you may be interested in it too. It\'s starting in just over a weeks time. The races are on Tuesdays at 21:00 GMT (see below for other time zones), starting in a week and a half on September 5th. \n\n[b][size=24][url]https://undycar.com/[/url][/size][/b]\n\nThe tracks and car are all free, so no need to buy anything. The car is the free Dallara Indycar with a fixed setup. Half the tracks are ovals, and half are road courses.\n\nIt would be awesome to see you on the track in the Undycar Series :)\n\n\nTimes in other time zones:\n07:00 AEST (Sydney)\n14:00 PDT (California)\n17:00 EDT (New York)\n21:00 GMT\n23:00 CEST (Berlin)";

			members_list_array.splice( member_name, 1 ); // Remove the name
			members_list = members_list_array.join( ',', members_list_array );

			chrome.storage.sync.set({
				members_list: members_list,
			}, function() {
				// ...
			});

		}, random_time_between_2_and_10_secs);

	});

	// Check every 1 second if the 
	setInterval( function () {

		if ( '' != document.getElementsByName('toUsername' )[0].value ) {
			var submit_button = document.getElementById('btnSubmit').click();
		}

	}, 1000);

}

// Redirect back
if ( window.location.href === 'http://members.iracing.com/jforum/jforum.page' ) {
	window.location.href = 'http://members.iracing.com/jforum/pm/send.page';
}
