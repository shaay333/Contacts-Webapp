<?php

require_once "db_conn.php";

$query = "SELECT * from contact_list";
$result = mysqli_query($link, $query);
// Define variables and initialize with empty values
$name = $Phno = $email = "";
$name_err = $Phno_err = $email_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate name
    if(empty(trim($_POST["name"]))){
        $name_err = "Please enter a name.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["name"]))){
        $name_err = "name can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM contact_list WHERE name = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_name);
            
            // Set parameters
            $param_name = trim($_POST["name"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $name_err = "This name is already taken.";
                } else{
                    $name = trim($_POST["name"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate Phno
    if(empty(trim($_POST["Phno"]))){
        $Phno_err = "Please enter a Phno.";     
    } //elseif(strlen(trim($_POST["Phno"])) < 11){
        //$Phno_err = "Phno must have atleast 10 characters.";
    //}
     else{
        $Phno = trim($_POST["Phno"]);
    }
    
    // Validate confirm Phno
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter Email";     
    } else{
        $email = trim($_POST["email"]);
        //if(empty($Phno_err) && ($Phno != $confirm_Phno)){
           // $email_err = "Phno did not match.";
        //}
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($Phno_err) && empty($email_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO contact_list (name , Phno, email) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_name, $param_Phno, $param_email);
            
            // Set parameters
            $param_name = $name;
            $param_Phno = $Phno; 
            $param_email = $email;
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<style>
table, th, td {
  border:1px solid black;
}
.center, h2 {
  margin-left: auto;
  margin-right: auto;
}
</style>
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper center">
        <h2 >Add Contacts</h2>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                <span class="invalid-feedback"><?php echo $name_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="Phno" class="form-control <?php echo (!empty($Phno_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Phno; ?>">
                <span class="invalid-feedback"><?php echo $Phno_err; ?></span>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Save">
                
            
            </div>
            
        </form>
    </div>
    <div class="my_contacts center">
    <h2 style="text-align:center; padding:2px;">My Contacts</h2>
    <table class="center">
    <thead>
        <tr>
            <th >Name</th>
            <th >Phone No</th>
            <th >Email</th>

        </tr>
    </thead>
    <tbody><?php
        while($rows=mysqli_fetch_assoc($result))
       {
           ?>
            <tr>
            <td><?php echo $rows['name']; ?></td>
            <td><?php echo $rows['Phno']; ?></td>
            <td><?php echo $rows['email']; ?></td>
            
        </tr>
        <?php
        }
        ?>
    </tbody> 
</table>

</div>

</body>
</html>