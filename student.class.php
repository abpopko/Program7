<?php
class Student {
    public $id;
    public $name;
    public $email;
    public $class_standing;
	public $favorite_class;
    private $noerrors = true;
    private $nameError = null;
    private $emailError = null;
    private $classStandingError = null;
	private $favoriteClassError = null;
    private $title = "Student";
    private $tableName = "students";
    
    function create_record() { // display "create" form
        $this->generate_html_top (1);
        $this->control_group("name", $this->nameError, $this->name);
        $this->control_group("email", $this->emailError, $this->email);
        $this->control_group("class_standing", $this->classStandingError, $this->class_standing);
		$this->control-group("favorite_class", $this->favoriteClassError, $this->favorite_class);
        $this->generate_html_bottom (1);
    } // end function create_record()
    
    function read_record($id) { // display "read" form
        $this->select_db_record($id);
        $this->generate_html_top(2);
        $this->control_group("name", $this->nameError, $this->name, "readonly");
        $this->control_group("email", $this->emailError, $this->email, "readonly");
        $this->control_group("class_standing", $this->classStandingError, $this->class_standing, "readonly");
		$this->control_group("favorite_class", $this->favoriteClassError, $this->favorite_class, "readonly");
        $this->generate_html_bottom(2);
    } // end function read_record()
    
    function update_record($id) { // display "update" form
        if($this->noerrors) $this->select_db_record($id);
        $this->generate_html_top(3, $id);
        $this->control_group("name", $this->nameError, $this->name);
        $this->control_group("email", $this->emailError, $this->email);
        $this->control_group("class_standing", $this->classStandingError, $this->class_standing);
		$this->control_group("favorite_class", $this->favoriteClassError, $this->favorite_class);
        $this->generate_html_bottom(3);
    } // end function update_record()
    
    function delete_record($id) { // display "read" form
        $this->select_db_record($id);
        $this->generate_html_top(4, $id);
        $this->control_group("name", $this->nameError, $this->name, "readonly");
        $this->control_group("email", $this->emailError, $this->email, "readonly");
         $this->control_group("class_standing", $this->classStandingError, $this->class_standing, "readonly");
		$this->control_group("favorite_class", $this->favoriteClassError, $this->favorite_class, "readonly");
        $this->generate_html_bottom(4);
    } // end function delete_record()
    
    function insert_db_record () {
        if ($this->fieldsAllValid ()) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO $this->tableName (name,email,class_standing,favorite_class) values(?, ?, ?, ?)";
            $q = $pdo->prepare($sql);
            $q->execute(array($this->name,$this->email,$this->class_standing,$this->favorite_class));
            Database::disconnect();
            header("Location: $this->tableName.php");
        }
        else {
            $this->create_record(); // go back to "create" form
        }
    } // end function insert_db_record
    
    private function select_db_record($id) {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM $this->tableName where id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        Database::disconnect();
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->class_standing = $data['class_standing'];
		$this->favorite_class = $data['favorite_class'];
    } // function select_db_record()
    
    function update_db_record ($id) {
        $this->id = $id;
        if ($this->fieldsAllValid()) {
            $this->noerrors = true;
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE $this->tableName  set name = ?, email = ?, class_standing = ?, favorite_class = ? WHERE id = ?";
            $q = $pdo->prepare($sql);
            $q->execute(array($this->name,$this->email,$this->class_standing,$this->favorite_class,$this->id));
            Database::disconnect();
            header("Location: $this->tableName.php");
        }
        else {
            $this->noerrors = false;
            $this->update_record($id);  // go back to "update" form
        }
    } // end function update_db_record 
    
    function delete_db_record($id) {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "DELETE FROM $this->tableName WHERE id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        Database::disconnect();
        header("Location: $this->tableName.php");
    } // end function delete_db_record()
    
    private function generate_html_top ($fun, $id=null) {
        switch ($fun) {
            case 1: // create
                $funWord = "Create"; $funNext = 11; 
                break;
            case 2: // read
                $funWord = "Read"; $funNext = 0; 
                break;
            case 3: // update
                $funWord = "Update"; $funNext = "33&id=" . $id; 
                break;
            case 4: // delete
                $funWord = "Delete"; $funNext = "44&id=" . $id; 
                break;
            case 0: // list
            default:
                break;
        }
		
        echo "<!DOCTYPE html>
        <html>
            <head>
                <title>$funWord a $this->title</title>
                    ";
        echo "
                <meta charset='UTF-8'>
                <link href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css' rel='stylesheet'>
                <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js'></script>
                    "; 
        echo "
            </head>";
                echo "
            <body>
				<img width= 200px style='float: right' src='svsuCardinal.png' />
                <div class='container'>
                    <div class='span10 offset1'>
                        <p class='row'>
                            <h3>$funWord a $this->title</h3>
                        </p>
                        <form class='form-horizontal' action='$this->tableName.php?fun=$funNext' method='post'>                        
                    ";
    } // end function generate_html_top()
    
    private function generate_html_bottom ($fun) {
        switch ($fun) {
            case 1: // create
                $funButton = "<button type='submit' class='btn btn-success'>Create</button>"; 
                break;
            case 2: // read
                $funButton = "";
                break;
            case 3: // update
                $funButton = "<button type='submit' class='btn btn-warning'>Update</button>";
                break;
            case 4: // delete
                $funButton = "<button type='submit' class='btn btn-danger'>Delete</button>"; 
                break;
            case 0: // list
            default: // list
                break;
        }
        echo " 
                            <div class='form-actions'>
                                $funButton
                                <a class='btn btn-secondary' href='$this->tableName.php'>Back</a>
                            </div>
                        </form>
                    </div>
                </div> <!-- /container -->
            </body>
        </html>
                    ";
    } // end function generate_html_bottom()
    
    private function control_group ($label, $labelError, $val, $modifier="") {
        echo "<div class='control-group";
        echo !empty($labelError) ? ' alert alert-danger ' : '';
        echo "'>";
        echo "<label class='control-label'>$label</label>";
        echo "<div class='controls'>";
        echo "<input "
            . "name='$label' "
            . "type='text' "
            . "$modifier "
            . "placeholder='$label' "
            . "value='";
        echo !empty($val) ? $val : '';
        echo "'>";
        if (!empty($labelError)) {
            echo "<span class='help-inline'>";
            echo "&nbsp;&nbsp;" . $labelError;
            echo "</span>";
        }
        echo "</div>";
        echo "</div>";
    } // end function control_group()
    
    private function fieldsAllValid () {
        $valid = true;
        if (empty($this->name)) {
            $this->nameError = 'Please enter Name';
            $valid = false;
        }
        if (empty($this->email)) {
            $this->emailError = 'Please enter Email Address';
            $valid = false;
        } 
        else if ( !filter_var($this->email,FILTER_VALIDATE_EMAIL) ) {
            $this->emailError = 'Please enter a valid email address: me@mydomain.com';
            $valid = false;
        }
        if (empty($this->class_standing)) {
            $this->classStandingError = 'Please enter your class standing';
            $valid = false;
        }
		if (empty($this->favorite_class)) {
			$this->favoriteClassError = 'Please enter your favorite class.';
			$valid = false;
		}
        return $valid;
    } // end function fieldsAllValid() 
    
    function list_records() {
        echo "<!DOCTYPE html>
        <html>
            <head>
                <title>$this->title" . "s" . "</title>
                    ";
        echo "
                <meta charset='UTF-8'>
                <link href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css' rel='stylesheet'>
                <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js'></script>
                    ";  
        echo "
            </head>
            <body>
			<img width= 200px style='float: right' src='svsuCardinal.png' />
			<a href='https://github.com/abpopko/Program7'>Github</a>
                <div class='container'>
                    <p class='row'>
                        <h3>$this->title" . "s" . "</h3>
                    </p>
                    <p>
                        <a href='$this->tableName.php?fun=1' class='btn btn-success'>Create</a>
						<a href='$this->tableName.php?fun=5' class='btn btn-danger'>Log Out</a>
                    </p>
                    <div class='row'>
                        <table class='table table-striped table-bordered'>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Class Standing</th>
									<th>Favorite Class</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                    ";
        $pdo = Database::connect();
        $sql = "SELECT * FROM $this->tableName ORDER BY id DESC";
        foreach ($pdo->query($sql) as $row) {
            echo "<tr>";
            echo "<td>". $row["name"] . "</td>";
            echo "<td>". $row["email"] . "</td>";
            echo "<td>". $row["class_standing"] . "</td>";
			echo "<td>". $row["favorite_class"] . "</td>";
            echo "<td width=250>";
            echo "<a class='btn btn-info' href='$this->tableName.php?fun=2&id=".$row["id"]."'>Read</a>";
            echo "&nbsp;";
            echo "<a class='btn btn-warning' href='$this->tableName.php?fun=3&id=".$row["id"]."'>Update</a>";
            echo "&nbsp;";
            echo "<a class='btn btn-danger' href='$this->tableName.php?fun=4&id=".$row["id"]."'>Delete</a>";
            echo "</td>";
            echo "</tr>";
        }
        Database::disconnect();        
        echo "
                            </tbody>
                        </table>
                    </div>
                </div>
            </body>
        </html>
                    ";  
    } // end function list_records()
	
	function loginPage(){
		session_destroy(); // destroy any existing session
		session_start(); // and start a new one
		$loginError = '';
		
		if ( !empty($_POST)) { // if $_POST filled then process the form
			// initialize $_POST variables
			$username = $_POST['username']; // username is email address, db field is email
			$password = $_POST['password']; // db field is password_hash
			$passwordhash = MD5($password);

			// verify the username/password
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "SELECT * FROM students WHERE email = ? AND password_hash = ? LIMIT 1";
			$q = $pdo->prepare($sql);
			$q->execute(array($username,$passwordhash));
			$data = $q->fetch(PDO::FETCH_ASSOC);
	
			if($data) {
				$_SESSION['id']=$data['id'];
				header('Location: students.php');
				Database::disconnect();

				exit();
			}
			else { // otherwise show loginError
				session_destroy();
				Database::disconnect();
				$loginError = '<p style = "color: red;">Username or Password incorrect, Try Again!</p>';
			}
		}
		
		//HTML FOR LOGINPAGE
		//******************************************************************************************************
		echo "<!DOCTYPE html>
        <html>
            <head>
                <title>$this->title" . "s" . "</title>
                    ";
        echo "
                <meta charset='UTF-8'>
                <link href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css' rel='stylesheet'>
                <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js'></script>
                    ";  
        echo "
            </head>
            <body>
			<img width= 200px style='float: right' src='svsuCardinal.png' />
			<a href='https://github.com/abpopko/Program7'>Github</a>";
		echo '<body><div class="container"><div class="span10 offset1"><div class="row"><h3>Login</h3></div>';
		echo '<form class="form-horizontal" method="post"><div class="control-group"><label class="control-label">Username (Email)</label><div class="controls"><input name="username" type="text"  placeholder="me@email.com" required></div></div>';
		echo '<div class="control-group"><label class="control-label">Password</label><div class="controls"><input name="password" type="password" placeholder="password" required></div></div>';
		echo '<div class="form-actions"><button type="submit" class="btn btn-success">Sign in</button>&nbsp; &nbsp;<a class="btn btn-primary" href="join.php">Register</a></div>';
		echo $loginError;
		echo '<footer><small>&copy; Copyright 2019, Amanda Popko</small></footer></form></div></div></body></html>';
		//******************************************************************************************************
	}
	
		
		function logOut(){
			unset($_SESSION);
			session_destroy();
			header('Location: login.php');
			exit;

			
		}
		
	function joinPage(){
	// PHP FOR JOINPAGE()
	//******************************************************************************************************
		if ( !empty($_POST)) { // if not first time through
			// initialize user input validation variables
			$nameError = null;
			$emailError = null;
			$classStandingError = null;
			$favoriteClassError = null;
			$passwordError = null;
			
			// initialize $_POST variables
			$name = $_POST['name'];	
			$email = $_POST['email'];
			$class_standing = $_POST['class_standing'];
			$favorite_class = $_POST['favorite_class'];
			$password = $_POST['password'];		
			$valid = true;
			
			// validate user input
			if (empty($name)) {
				$nameError = 'Please enter your Name';
				$valid = false;
			}
			if (empty($email)) {
				$emailError = 'Please enter Email Address';
				$valid = false;
			} else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
				$emailError = 'Please enter a valid Email Address';
				$valid = false;
			}
			if (empty($class_standing)) {
				$classStandingError = 'Please enter your class standing';
				$valid = false;
			}
			if (empty($favorite_class)) {
				$favoriteClassError = 'Please enter your favorite class';
				$valid = false;
			}
			if (empty($password)) {
				$passwordError = 'Please enter a Password';
				$valid = false;
			}
		
			// insert data into database
			if ($valid) {
				$subject = "Registration Successful!";
				$message = "You have registered for Amanda Popko's Website! Congratulations!";
				$sender = "From: abpopko@svsu.edu" . "\r\n";
				// send confirmation email
				mail($_POST['email'], $subject, $message, $sender);

				
				$password_hash = MD5($password);
				$pdo = Database::connect();
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$sql = "INSERT INTO students (name,email,class_standing,favorite_class,password_hash) values(?, ?, ?, ?, ?)";
				$q = $pdo->prepare($sql);
				$q->execute(array($name, $email, $class_standing, $favorite_class, $password_hash));
				header('Location: students.php');
				Database::disconnect();
				
			}
		}
		//******************************************************************************************************
		
		//HTML FOR JOINPAGE()
		//******************************************************************************************************
		echo "<!DOCTYPE html>
        <html>
            <head>
                <title>$this->title" . "s" . "</title>
                    ";
        echo "
                <meta charset='UTF-8'>
                <link href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css' rel='stylesheet'>
                <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js'></script>
                    ";  
        echo "
            </head>
            <body>
			<img width= 200px style='float: right' src='svsuCardinal.png' />
			<a href='https://github.com/abpopko/Program7'>Github</a>";
		echo '<div class="container"><div class="span10 offset1"><div class="row"><h3>Register</h3></div>';
		echo '<form class="form-horizontal"  method="post">';
		
		//This displays the name text Box and label with exception handling
		echo '<div class="control-group';
		echo !empty($nameError)?'error':'';
		echo '">';
		echo '<label class="control-label">Name</label>';
		echo '<div class="controls">';
		echo '<input name="name" type="text" placeholder="Name" value="';
		echo !empty($name)?$name:'';
		echo '">';
		if (!empty($nameError)):
			echo '<span class="help-inline">';
			echo $nameError;
			echo '</span>';
		endif;
		echo '</div>';
		echo '</div>';
		
		//This displays the email text box and label with exception handling
		echo '<div class="control-group'; 
		echo !empty($emailError)?'error':'';
		echo '">';
		echo '<label class="control-label">Email</label>';
		echo '<div class="controls">';
		echo '<input name="email" type="text" placeholder="Email" value="'; 
		echo !empty($email)?$email:'';
		echo '">';
		if (!empty($emailError)):
			echo '<span class="help-inline">';
			echo $emailError;
			echo '</span>';
		endif;
		echo '</div>';
		echo '</div>';
		
		//This displays the class standing text box and label with exception handling
		echo '<div class="control-group'; 
		echo !empty($classStandingError)?'error':'';
		echo '">';
		echo '<label class="control-label">Class Standing</label>';
		echo '<div class="controls">';
		echo '<input name="class_standing" type="text" placeholder="Class Standing" value="'; 
		echo !empty($class_standing)?$class_standing:'';
		echo '">';
		if (!empty($classStandingError)):
			echo '<span class="help-inline">';
			echo $classStandingError;
			echo '</span>';
		endif;
		echo '</div>';
		echo '</div>';
		
			//This displays the favorite class text box and label with exception handling
		echo '<div class="control-group'; 
		echo !empty($favoriteClassError)?'error':'';
		echo '">';
		echo '<label class="control-label">Favorite Class</label>';
		echo '<div class="controls">';
		echo '<input name="favorite_class" type="text" placeholder="Favorite Class" value="'; 
		echo !empty($favorite_class)?$favorite_class:'';
		echo '">';
		if (!empty($favoriteClassError)):
			echo '<span class="help-inline">';
			echo $favoriteClassError;
			echo '</span>';
		endif;
		echo '</div>';
		echo '</div>';
		
		//This displays the password text box and label with exception handling
		echo '<div class="control-group'; 
		echo !empty($passwordError)?'error':'';
		echo '">';
		echo '<label class="control-label">Password</label>';
		echo '<div class="controls">';
		echo '<input name="password" type="text" placeholder="Password" value="'; 
		echo !empty($password)?$password:'';
		echo '">';
		if (!empty($passwordError)):
			echo '<span class="help-inline">';
			echo $passwordError;
			echo '</span>';
		endif;
		echo '</div>';
		echo '</div>';
		
		
		//Submit Button and Back Button
		echo '<div class="form-actions"><button type="submit" class="btn btn-success">Register</button><a class="btn" style="color: black;" href='; 
		if(isset($_SESSION['id'])){ echo 'students.php>Back</a></div>'; }else{ echo 'login.php>Back</a></div>';}
		echo '</form></div></div>';
	
	//******************************************************************************************************
	}
    
} // end class Student
