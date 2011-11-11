Plink is short for Push Link, a bookmarklet that takes the current browser tab's URL and pushes it up to the server.  Once a link has been stored, a second instance of the bookmarklet on another machine is used to pull the link down to the current browser.

Plink is a simple solution to a specialized problem in my office where some of us run multiple machines.  Most of us run chat, email, etc. on one machine and develop on another, this bookmarklet solves the problem of links ending up on one machine via chat/social media and having to retype them or otherwise resend them to the other.

A running and free-to-use version of Plink is available at [http://plink.ericdelabar.com/](http://plink.ericdelabar.com/).

Plink has been tested in Chrome, Firefox, and Safari, but it probably works in other browsers.

Plink runs on a LAMP stack and uses twitter-async for Twitter/OAuth authentication: [https://github.com/jmathai/twitter-async](https://github.com/jmathai/twitter-async)

## Want to install your own instance?

1. git clone git://github.com/edelabar/plink.git
2. cd plink
3. git submodule init
4. git submodule update
5. Edit the properties.ini.example and rename to properties.ini
6. Add the following table to your database: 


	CREATE TABLE `stash` (
		`uuid` VARCHAR( 36 ) NOT NULL ,
		`twitter` VARCHAR( 128 ) NOT NULL ,
		`url` VARCHAR( 2048 ) NOT NULL DEFAULT  '',
		PRIMARY KEY (  `uuid` ) ,
		UNIQUE (
			`twitter`
		)
	) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_bin;