<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$Subject_category = $Subject_Title = $Status = "";
$Sub_cat_err = $Sub_Title_err = $Status_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate Subject_category
    $input_Subject_category = isset($_POST["Subject_category"]);
    if(empty($input_Subject_category)){
        $Subject_category_err = "Please enter a Subject_category.";
    } elseif(!filter_var($input_Subject_category, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $Subject_category_err = "Please enter a valid Subject_category.";
    } else{
        $Subject_category = $input_Subject_category;
    }
    
    // Validate Subject_Title
    $input_Subject_Title = isset($_POST["Subject_Title"]);
    if(empty($input_Subject_Title)){
        $Subject_Title_err = "Please enter an Subject_Title.";     
    } else{
        $Subject_Title = $input_Subject_Title;
    }
    
    // Validate Status
    $input_Status = isset($_POST["Status"]);
    if(empty($input_Status)){
        $Status_err = "Please enter the Status amount.";     
    } elseif(!ctype_digit($input_Status)){
        $Status_err = "Please enter a positive integer value.";
    } else{
        $Status = $input_Status;
    }
    
    // Check input errors before inserting in database
    if(empty($Subject_category_err) && empty($Subject_Title_err) && empty($Status_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO subjects (Subject_category, Subject_Title, Status) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_Subject_category, $param_Subject_Title, $param_Status);
            
            // Set parameters
            $param_Subject_category = $Subject_category;
            $param_Subject_Title = $Subject_Title;
            $param_Status = $Status;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Subject_category</label>
                            <input type="text" Subject_category="Subject_category" class="form-control <?php echo (!empty($Subject_category_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Subject_category; ?>">
                            <span class="invalid-feedback"><?php echo $Subject_category_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Subject_Title</label>
                            <textarea Subject_category="Subject_Title" class="form-control <?php echo (!empty($Subject_Title_err)) ? 'is-invalid' : ''; ?>"><?php echo $Subject_Title; ?></textarea>
                            <span class="invalid-feedback"><?php echo $Subject_Title_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <input type="text" Subject_category="Status" class="form-control <?php echo (!empty($Status_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Status; ?>">
                            <span class="invalid-feedback"><?php echo $Status_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
