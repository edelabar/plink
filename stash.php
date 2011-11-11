<?php header('Content-Type: application/javascript');

include 'properties.php';

$username = $_GET['username'];
$token = $_GET['token'];
$url = $_GET['url'];
$callback = $_GET['callback'];
		
$dsn = "mysql:host=$dbServer;dbname=$dbName;"; 
$dbh = new PDO($dsn, $user, $password); 
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

try {

	$sql = "UPDATE $dbTable SET url = :url WHERE uuid = :token AND twitter = :twitter";
	$stmt = $dbh->prepare($sql);  
	$stmt->bindParam(':url', $url);
	$stmt->bindParam(':token', $token);
	$stmt->bindParam(':twitter', $username);
	if( $stmt->execute() ) {
		$success = true;
	} else {
		$success = false;
	}

} catch (PDOException $e) {

	$err = "An error has occurred saving your data, the site administrator has been notified, please try back later.";
					
	$subject = "Plink ERROR!";
 
	$body = "Error Details:\nPDO Exception Caught. \nError with the database: \nSQL Query: $sql\n\nError: " . $e->getMessage();
 
	mail($adminEmail, $subject, $body, "From:$adminEmail");

	$success = false;

}

?>
<?php echo $callback; ?>({'success':<?php echo $success; ?>,'url':'<?php echo $url; ?>','username':'<?php echo $username; ?>','token':'<?php echo $token; ?>'});