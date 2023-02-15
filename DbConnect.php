<?php
	class DbConnect {
		// Properties for the connection 
		private $server = '';
		private $dbname = '';
		private $user = '';
		private $pass = '';
		// PDO Object as database connection
		public function connect() {
			try {
				// New PDO Object with properties
				$conn = new PDO('mysql:host=' .$this->server .';dbname=' . $this->dbname, $this->user, $this->pass);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				// Return PDO Object 
				return $conn;
			} catch (\Exception $e) {
				// If error then, catch it, and display
				echo "Database Error: " . $e->getMessage();
			}
		}
        
	}
?>