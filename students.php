<?php
require "database.php";
require "student.class.php";
require "session.php";
$stud = new Student();
if(isset($_POST["name"])) $stud->name = $_POST["name"];
if(isset($_POST["email"])) $stud->email = $_POST["email"];
if(isset($_POST["class_standing"])) $stud->class_standing = $_POST["class_standing"];
if(isset($_POST["favorite_class"])) $stud->favorite_class = $_POST["favorite_class"];
if(isset($_GET["id"])) $id = $_GET["id"];
else $id = 0;
// "fun" (function): 0=list, 1=create, 2=read, 3=update, 4=delete
if(isset($_GET["fun"])) $fun = $_GET["fun"];
else $fun = 0;
switch ($fun) {
    case 1: // create
        $stud->joinPage(); // display "create" form
        break;
    case 2: // read
        if($id > 0) $stud->read_record($id); // display "read" form
        else $stud->list_records(); // if no id, go to list screen
        break;
    case 3: // update
        if($id > 0) $stud->update_record($id); // display "update" form
        else $stud->list_records(); // if no id, go to list screen
        break;
    case 4: // delete
        if($id > 0) $stud->delete_record($id); // display "delete" form
        else $stud->list_records(); // if no id, go to list screen
        break;
    case 11: // insert database record from create_record() form
        $stud->insert_db_record(); // insert a new db record
        break;
    case 33: // update database record from update_record() form
        $stud->update_db_record($id);
        break;
    case 44: // delete database record from delete_record() form
        $stud->delete_db_record($id);
        break;
	case 5:
		$stud->logOut();
		break;
    case 0: // list
    default: 
        $stud->list_records();
        break;
}
