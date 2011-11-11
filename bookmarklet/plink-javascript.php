<?php header('Content-Type: application/javascript');

include 'properties.php';

$username = $_GET['username'];
$token = $_GET['token'];
		
$dsn = "mysql:host=$dbServer;dbname=$dbName;";
$dbh = new PDO($dsn, $user, $password); 
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

try {

	$sql = "SELECT url FROM $dbTable WHERE uuid = :token AND twitter = :twitter LIMIT 1";
	$stmt = $dbh->prepare($sql);  
	$stmt->bindParam(':token', $token);
	$stmt->bindParam(':twitter', $username);
	
	if( $stmt->execute() ) {	
		while( $row = $stmt->fetch() ) {	
			$url = $row[0];
		}
		
		$sql = "UPDATE $dbTable SET url = '' WHERE uuid = :token AND twitter = :twitter";
		$stmt = $dbh->prepare($sql);  
		$stmt->bindParam(':token', $token);
		$stmt->bindParam(':twitter', $username);
		$stmt->execute();
	}	
	
	$success = true;

} catch (PDOException $e) {

	$err = "An error has occurred saving your data, the site administrator has been notified, please try back later.";
					
	$subject = "Plink ERROR!";
 
	$body = "Error Details:\nPDO Exception Caught. \nError with the database: \nSQL Query: $sql\n\nError: " . $e->getMessage();
 
	mail($adminEmail, $subject, $body, "From:$adminEmail");

	$success = false;

}
?>
var plink={};plink.home="<?php echo $homeUrl; ?>";plink.success="<?php echo $success ?>";plink.url="<?php echo $url; ?>";plink.username="<?php echo $username; ?>";plink.token="<?php echo $token; ?>";<?php include("plink.js"); ?>