<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$Subject_category = $Subject_category = $Status = "";
$Subject_category_err = $Subject_category_err = $Status_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate Subject_category
    $input_Subject_category = trim($_POST["Subject_category"]);
    if(empty($input_Subject_category)){
        $Subject_category_err = "Please enter a Subject_category.";
    } elseif(!filter_var($input_Subject_category, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $Subject_category_err = "Please enter a valid Subject_category.";
    } else{
        $Subject_category = $input_Subject_category;
    }
    
    // Validate Subject_category Subject_category
    $input_Subject_category = trim($_POST["Subject_category"]);
    if(empty($input_Subject_category)){
        $Subject_category_err = "Please enter an Subject_category.";     
    } else{
        $Subject_category = $input_Subject_category;
    }
    
    // Validate Status
    $input_Status = trim($_POST["Status"]);
    if(empty($input_Status)){
        $Status_err = "Please enter the Status amount.";     
    } elseif(!ctype_digit($input_Status)){
        $Status_err = "Please enter a positive integer value.";
    } else{
        $Status = $input_Status;
    }
    
    // Check input errors before inserting in database
    if(empty($Subject_category_err) && empty($Subject_category_err) && empty($Status_err)){
        // Prepare an update statement
        $sql = "UPDATE subjects SET Subject_category=?, Subject_category=?, Status=? WHERE id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssi", $param_Subject_category, $param_Subject_category, $param_Status, $param_id);
            
            // Set parameters
            $param_Subject_category = $Subject_category;
            $param_Subject_category = $Subject_category;
            $param_Status = $Status;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
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
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM subjects WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $Subject_category = $row["Subject_category"];
                    $Subject_category = $row["Subject_category"];
                    $Status = $row["Status"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
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
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the employee record.</p>
                    <form action="<?php echo htmlspecialchars(baseSubject_category($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Subject_category</label>
                            <input type="text" Subject_category="Subject_category" class="form-control <?php echo (!empty($Subject_category_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Subject_category; ?>">
                            <span class="invalid-feedback"><?php echo $Subject_category_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Subject_category</label>
                            <textarea Subject_category="Subject_category" class="form-control <?php echo (!empty($Subject_category_err)) ? 'is-invalid' : ''; ?>"><?php echo $Subject_category; ?></textarea>
                            <span class="invalid-feedback"><?php echo $Subject_category_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <input type="text" Subject_category="Status" class="form-control <?php echo (!empty($Status_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Status; ?>">
                            <span class="invalid-feedback"><?php echo $Status_err;?></span>
                        </div>
                        <input type="hidden" Subject_category="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>