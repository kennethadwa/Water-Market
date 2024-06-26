<?php
// Include database configuration file
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['signup-name'];
    $email = $_POST['signup-email'];
    $password = $_POST['signup-password'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Prepare SQL query
        $sql = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, 'admin')";
        
        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind parameters to statement
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect to login page after successful signup
            header("Location: login.php");
            exit();
        } else {
            echo "Error: Could not execute the query.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en"> 
<head>
    <title>Portal - Bootstrap 5 Admin Dashboard Template For Developers</title>
    
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta name="description" content="Portal - Bootstrap 5 Admin Dashboard Template For Developers">
    <meta name="author" content="Xiaoying Riley at 3rd Wave Media">    
    <link rel="shortcut icon" href="favicon.ico"> 
    
    <!-- FontAwesome JS-->
    <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>
    
    <!-- App CSS -->  
    <link id="theme-style" rel="stylesheet" href="assets/css/portal.css">

</head> 

<body class="app app-signup p-0">    	
    <div class="row g-0 app-auth-wrapper">
        <div class="col-12 col-md-7 col-lg-6 auth-main-col text-center p-5">
            <div class="d-flex flex-column align-content-end">
                <div class="app-auth-body mx-auto">    
                    <div class="app-auth-branding mb-4"><a class="app-logo" href="index.html"><img class="logo-icon me-2" src="assets/images/app-logo.svg" alt="logo"></a></div>
                    <h2 class="auth-heading text-center mb-4">Sign up to Portal</h2>                    

                    <div class="auth-form-container text-start mx-auto">
                        <form class="auth-form auth-signup-form" action="signup.php" method="POST">         
                            <div class="email mb-3">
                                <label class="sr-only" for="signup-name">Your Name</label>
                                <input id="signup-name" name="signup-name" type="text" class="form-control signup-name" placeholder="Full name" required="required">
                            </div>
                            <div class="email mb-3">
                                <label class="sr-only" for="signup-email">Your Email</label>
                                <input id="signup-email" name="signup-email" type="email" class="form-control signup-email" placeholder="Email" required="required">
                            </div>
                            <div class="password mb-3">
                                <label class="sr-only" for="signup-password">Password</label>
                                <input id="signup-password" name="signup-password" type="password" class="form-control signup-password" placeholder="Create a password" required="required">
                            </div>
                            <div class="extra mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="RememberPassword">
                                    <label class="form-check-label" for="RememberPassword">
                                    I agree to Portal's <a href="#" class="app-link">Terms of Service</a> and <a href="#" class="app-link">Privacy Policy</a>.
                                    </label>
                                </div>
                            </div><!--//extra-->
                            
                            <div class="text-center">
                                <button type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto">Sign Up</button>
                            </div>
                        </form>
                        
                        <div class="auth-option text-center pt-5">Already have an account? <a class="text-link" href="login.php" >Log in</a></div>
                    </div>    
                    
                    
                    
                </div>
            
                <footer class="app-auth-footer">
                    <div class="container text-center py-3">
                       
                    </div>
                </footer>
            </div>
        </div>

        <div class="col-12 col-md-5 col-lg-6 h-100 auth-background-col">
                <img src="./assets/images/waterbg2.png" alt="" style="background-position: center; background-size:cover; height:100vh; min-height:100%">
            <div class="auth-background-overlay p-3 p-lg-5">
                <div class="d-flex flex-column align-content-end h-100">
                    <div class="h-100"></div>
                </div>
            </div>
        </div>
    
    </div>
</body>
</html>
