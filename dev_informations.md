Informations for developers
=======================


Background
----------

Sea-Watch is a private initiative founded in 2014 by a group of citizens, dedicated to putting an end to the dyin
g on the Mediterranean Sea.

The NGO started to operate in 2014 with a single ship in front of libya and expanded its operations to the greek
island of Lesbos after counts of people dying on the way from turkey to greece had risen. The operations at Lesbos
has been paused after the turkey-eu deal.

I am trying to give a short summarization of the quite dynamic development process of this application

***Spotting App for Lesbos***
When we started developing the system the request from sea-watch was to create a system to manage distress calls
at the greece island of lesbos in december 2015. We created an app for spotters which generated the coordinates
of a spotted boat out of the current location of the spotter, the distance and the angle of the boat. Because
of the short distance between the turkish and greece coast most of the areas had a gsm-network. So we created
an app for refugees which basically allowed people on a boat to communicate their position to one of the ngos.

We used the `[PHP Framework Laravel](https://laravel.com/) as a Backend Solution to administrate all the cases and spotters. For the
spotters we created a mobile application with [ionic](https://ionicframework.com/).


Shortly before the first in-use tests of the application the deal between the eu and turkey which made the application
obsolet.

***SAR managing for the central med***

After a short reorientation phase we decided to use the created system for the management of the rising number of
private search and rescue vessels which operate in front of the libyan coast at the moment. Those ships usually
only have a quite bad sat-connection which ment that we had to move some parts of the logic from the Laravel
Application to a Desktop and Mobile application which works partly offline.

After the presentation of the vague idea we had the chance to be part of a mission 2 month later. In this two months
we rebuild the administration interace and an offline map view with github electron which we tested in October 2016. 
Simultaniously we started to talk to other ngos to get them "on board" which resulted in a meetup of 5 NGO's in Hamburg
in mid of december 2016 in which we presented the prototype and to define the requirements for this application.



Requirements Dec 2017
------------


	Backend Application
		Manage Vehicles
		Backward compatible Api
			
		
	Desktop Application
		(offline) Map
			Show Vehicles
			Show Cases
			
		Caseview
			Show Cases
			Update Cases

		Create Case Modal
		
		Filter for Map- and Caseview	
		
		Chat
			Show Messages
			Show Logs
			Send Messages
			Filter
			Case-Hashtags to link messages with a case

	Unit Tests
	



Current Status
--------------
	We still need a good way to update the application
	
	Backend Application
		Manage Vehicles ✓
		Backward compatible Api
		(api calls are working but there are unused api actions inside the api_controller.php)
			
	Desktop Application
		(offline) Map ✓
			Show Vehicles ✓
			Show Cases ✓
				
			
		Caseview (mostly html, no functionallity so far)
			Show Cases 
			Update Cases

		Create Case Modal (can be improved)
		
		Filter for Map- and Caseview (can be improved)
		
		Chat
			Show Messages ✓
			Show Logs
			Send Messages ✓
			Filter
			Case-Hashtags to link messages with a case
