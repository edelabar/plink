<!DOCTYPE html>
<?php
	include 'twitter-async/EpiCurl.php';
	include 'twitter-async/EpiOAuth.php';
	include 'twitter-async/EpiTwitter.php';
	include 'properties.php';
		
	$dsn = "mysql:host=$dbServer;dbname=$dbName;"; 
	$dbh = new PDO($dsn, $user, $password); 
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
?>
<html>
	<head>
		<meta charset="utf-8">
		<title dir="ltr">Plink</title>
	</head>
	<body>
		<h1>Plink is under construction.</h1>
		<p>It might get prettier. It might not.</p>
		
		<p>Plink is a bookmarklet that stashes the current URL from one browser/machine and retrieves it on another.  To use it:</p>
		<ol>
			<li>Add your personal bookmarklet to both machines.</li>
			<li>View a webpage URL you'd like to transfer on machine 1.</li>
			<li>Click the Plink bookmarklet to save the URL.</li>
			<li>On machine 2, click the Plink bookmarklet to load the saved URL into the current tab.</li>
		</ol>
		<?php if( isset($_GET['oauth_token']) && isset($_GET['oauth_verifier']) ) { ?>
			<?php // First login, set the cookie
		
				$twitterObj = new EpiTwitter($consumer_key, $consumer_secret);
	
				$twitterObj->setToken($_GET['oauth_token']);
				$token = $twitterObj->getAccessToken();
				$twitterObj->setToken($token->oauth_token, $token->oauth_token_secret);
				
				$twitterInfo= $twitterObj->get_accountVerify_credentials();					
				$username = $twitterInfo->screen_name;
				
				// save to cookies
				setcookie('oauth_token', $token->oauth_token);
				setcookie('oauth_token_secret', $token->oauth_token_secret);
				
				// Persist Here...
				try 
				{ 
										
					$sql = "SELECT uuid FROM $dbTable WHERE twitter = :twitter";
					$stmt = $dbh->prepare($sql);  
					$stmt->bindParam(':twitter', $username);
					
					if( $stmt->execute() ) {
						while( $row = $stmt->fetch() ) {	
							$plinkToken = $row[0];
						}	
						
					}
					
					if( !isset($plinkToken) ) {
				
						$sql = "INSERT INTO $dbTable (uuid, twitter, url) VALUES (UUID(), :twitter, '')"; 
						$stmt = $dbh->prepare($sql);  
						$stmt->bindParam(':twitter', $username); 
						
						$stmt->execute(); 
						
					}
				} 
				catch (PDOException $e) 
				{ 
					$err = "An error has occurred saving your data, the site administrator has been notified, please try back later.";
					
					$subject = "Plink ERROR!";
				 
					$body = "Error Details:\nPDO Exception Caught. \nError with the database: \nSQL Query: $sql\n\nError: " . $e->getMessage();
				 
					mail($adminEmail, $subject, $body, "From:$adminEmail");
					
				}
		
			?>
		<?php } ?>
		<?php if( isset($twitterObj) || ( isset($_COOKIE['oauth_token']) && isset($_COOKIE['oauth_token_secret']) ) ) { ?>
			<?php // Additional logins, read the cookie
			
				if( !isset($twitterObj) ) {
					$twitterObj = new EpiTwitter($consumer_key, $consumer_secret, $_COOKIE['oauth_token'], $_COOKIE['oauth_token_secret']);
					$twitterInfo= $twitterObj->get_accountVerify_credentials();					
					$username = $twitterInfo->screen_name;
				}
				
				if( !isset($err) ) {
					try {
					
						$sql = "SELECT uuid FROM stash WHERE twitter = :twitter";
						$stmt = $dbh->prepare($sql);  
						$stmt->bindParam(':twitter', $username);
						
						if( $stmt->execute() ) {
							while( $row = $stmt->fetch() ) {	
								$plinkToken = $row[0];
							}	
							
						}

					} catch (PDOException $e) { 
					
						$err = "An error has occured saving your data, the site administrator has been notified, please try back later.";
						
						$subject = "Plink ERROR!";
					 
						$body = "Error Details:\nPDO Exception Caught. \nError with the database: \nSQL Query: $sql\n\nError: " . $e->getMessage();
					 
						mail($adminEmail, $subject, $body, "From:$adminEmail");
					}					
				}
				
				$bookmarklet = "javascript:(function(){cmc=document.createElement(%27SCRIPT%27);cmc.type=%27text/javascript%27;cmc.src=%27{$homeUrl}/bookmarklet/{$username}/{$plinkToken}/plink.js?x=%27+(Math.random());document.getElementsByTagName(%27head%27)[0].appendChild(cmc);})();";
				
			?>
			
			<?php if( !isset($err) && isset($plinkToken) ) { ?>
				<h2>Drag <a href="<?php echo $bookmarklet; ?>">Plink</a> to your bookmarks bar to to access <a href="http://www.twitter.com/<?php echo $username ?>">@<?php echo $username ?></a>'s Plink stash from this browser.</h2>		
			<?php } else { ?>
				<p><?php echo $err; ?></p>
			<?php } ?>
			
	
		<?php } else { ?>
			<?php
			
				$twitterObj = new EpiTwitter($consumer_key, $consumer_secret);
				
			?>
			
			<p><a href="<?php echo $twitterObj->getAuthenticateUrl(); ?>">Authenticate with Twitter</a> to generate or access your personal bookmarklet.</p>
	
		<?php } ?>
		
		<p>Plink is open source under the MIT license and available on GitHub: <a href="https://github.com/edelabar/plink">https://github.com/edelabar/plink</a></p>
		<p>Copyright © 2011 by <a href="http://ericdelabar.com/">Eric DeLabar</a>.</p>
	</body>
</html>