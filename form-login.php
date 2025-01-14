<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to feed page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: feed.php");
    exit;
}
 
// Include config file
include 'database/db_connect.php';
 
//Declare variables and initialize with empty values
// $userID = "";
// $email = "";
// $password = "";
$message = "";
$toastClass = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["email"]))){
      $message = "Please enter email.";
      $toastClass = "#c30010"; // Primary color
    } else{
        $email = trim($_POST["email"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
      $message = "Please enter password.";
      $toastClass = "#c30010"; // Primary color
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($message) ){
        // Prepare a select statement
        $sql = "SELECT UserID, password FROM user WHERE email = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = $email;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $userID, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["UserID"] = $userID;
                            $_SESSION["loggedin"] = true;
                            
                                                     
                            
                            // Redirect user to welcome page
                            header("location: feed.php");
                        } else{
                            // Password is not valid, display a generic error message
                            $message = "Invalid username or password.";
                            $toastClass = "#c30010";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $message = "Invalid username or password.";
                    $toastClass = "#c30010";
                    
                }
            } else{
              $message = "Oops! Something went wrong. Please try again later.";
              $toastClass = "#c30010";
              
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($conn);
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicon -->
    <link href="assets/images/favicon.png" rel="icon" type="image/png">

    <!-- title and description-->
    <title>Socialite</title>
    <meta name="description" content="Socialite - Social sharing network HTML Template">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
   
    <!-- css files -->
    <link rel="stylesheet" href="assets/css/tailwind.css">
    <link rel="stylesheet" href="assets/css/style.css">  
    
    <!-- google font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

  <div class="sm:flex">
    
    <div class="relative lg:w-[580px] md:w-96 w-full p-10 min-h-screen bg-white shadow-xl flex items-center pt-10 dark:bg-slate-900 z-10">

      <div class="w-full lg:max-w-sm mx-auto space-y-10" uk-scrollspy="target: > *; cls: uk-animation-scale-up; delay: 100 ;repeat: true">

        <!-- logo image-->
        <a href="#"> <img src="assets/images/logo.png" class="w-28 absolute top-10 left-10 dark:hidden" alt=""></a>
        <a href="#"> <img src="assets/images/logo.png" class="w-28 absolute top-10 left-10 hidden dark:!block" alt=""></a>

        <!-- logo icon optional -->
        <div class="hidden">
          <img class="w-12" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&amp;shade=600" alt="Socialite html template">
        </div>
 <!-- error message display -->
        <?php if ($message): ?>
            <div class="toast align-items-center text-white border-0" 
          role="alert" aria-live="assertive" aria-atomic="true"
                style="background-color: <?php echo $toastClass; ?>;">
                <div class="d-flex">
                    <div class="toast-body">
                        <?php echo $message; ?>
                    </div>
                    <button type="button" class="btn-close
                    btn-close-white me-2 m-auto" 
                          data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>

        <!-- title -->
        <div>
          <h2 class="text-2xl font-semibold mb-1.5"> Sign in to your account </h2>
          <p class="text-sm text-gray-700 font-normal">If you haven’t signed up yet. <a href="form-register.php" class="text-blue-700">Register here!</a></p>
        </div>
 

        <!-- form -->
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="space-y-7 text-sm text-black font-medium dark:text-white"  uk-scrollspy="target: > *; cls: uk-animation-scale-up; delay: 100 ;repeat: true">
            
          <!-- email -->
          <div>
              <label for="email" class="">Email address</label>
              <div class="mt-2.5">
                  <input id="email" name="email" type="email" autofocus=""  placeholder="Email" required="" class="!w-full !rounded-lg !bg-transparent !shadow-sm !border-slate-200 dark:!border-slate-800 dark:!bg-white/5"> 
              </div>
          </div>
          <!-- password -->
          <div>
            <label for="email" class="">Password</label>
            <div class="mt-2.5">
                <input id="password" name="password" type="password" placeholder="***"  class="!w-full !rounded-lg !bg-transparent !shadow-sm !border-slate-200 dark:!border-slate-800 dark:!bg-white/5">  
            </div>
          </div>

          <div class="flex items-center justify-between">

            <div class="flex items-center gap-2.5">
              <input id="rememberme" name="rememberme" type="checkbox">
              <label for="rememberme" class="font-normal">Remember me</label>
            </div>
            <a href="#" class="text-blue-700">Forgot password </a>
          </div>

          <!-- submit button -->
          <div>
            <button type="submit" class="button bg-primary text-white w-full">Sign in</button>
          </div>
          
        </form>


      </div>

    </div>

    <!-- image slider -->
    <div class="flex-1 relative bg-primary max-md:hidden">


      <div class="relative w-full h-full" tabindex="-1" uk-slideshow="animation: slide; autoplay: true">
    
        <ul class="uk-slideshow-items w-full h-full"> 
            <li class="w-full">
                <img src="assets/images/post/img-3.jpg"  alt="" class="w-full h-full object-cover uk-animation-kenburns uk-animation-reverse uk-transform-origin-center-left">
                <div class="absolute bottom-0 w-full uk-tr ansition-slide-bottom-small z-10">
                    <div class="max-w-xl w-full mx-auto pb-32 px-5 z-30 relative"  uk-scrollspy="target: > *; cls: uk-animation-scale-up; delay: 100 ;repeat: true" > 
                        <img class="w-12" src="assets/images/logo.png" alt="Socialite html template">
                        <h4 class="!text-white text-2xl font-semibold mt-7"  uk-slideshow-parallax="y: 600,0,0">  Connect With Friends </h4> 
                        <p class="!text-white text-lg mt-7 leading-8"  uk-slideshow-parallax="y: 800,0,0;"> This phrase is more casual and playful. It suggests that you are keeping your friends updated on what’s happening in your life.</p>   
                    </div> 
                </div>
                <div class="w-full h-96 bg-gradient-to-t from-black absolute bottom-0 left-0"></div>
            </li>
            <li class="w-full">
              <img src="assets/images/post/img-2.jpg"  alt="" class="w-full h-full object-cover uk-animation-kenburns uk-animation-reverse uk-transform-origin-center-left">
              <div class="absolute bottom-0 w-full uk-tr ansition-slide-bottom-small z-10">
                  <div class="max-w-xl w-full mx-auto pb-32 px-5 z-30 relative"  uk-scrollspy="target: > *; cls: uk-animation-scale-up; delay: 100 ;repeat: true" > 
                      <img class="w-12" src="assets/images/logo.png" alt="Socialite html template">
                      <h4 class="!text-white text-2xl font-semibold mt-7"  uk-slideshow-parallax="y: 800,0,0">  Connect With Friends </h4> 
                      <p class="!text-white text-lg mt-7 leading-8"  uk-slideshow-parallax="y: 800,0,0;"> This phrase is more casual and playful. It suggests that you are keeping your friends updated on what’s happening in your life.</p>   
                  </div> 
              </div>
              <div class="w-full h-96 bg-gradient-to-t from-black absolute bottom-0 left-0"></div>
          </li>
        </ul>
 
        <!-- slide nav -->
        <div class="flex justify-center">
            <ul class="inline-flex flex-wrap justify-center  absolute bottom-8 gap-1.5 uk-dotnav uk-slideshow-nav"> </ul>
        </div>
      
        
    </div>
  

    </div>
  
  </div>
  
<!-- toast r -->
  <script>
        let toastElList = [].slice.call(document.querySelectorAll('.toast'))
        let toastList = toastElList.map(function (toastEl) {
            return new bootstrap.Toast(toastEl, { delay: 3000 });
        });
        toastList.forEach(toast => toast.show());
    </script> 
    <!-- Uikit js you can use cdn  https://getuikit.com/docs/installation  or fine the latest  https://getuikit.com/docs/installation -->
    <script src="assets/js/uikit.min.js"></script>
    <script src="assets/js/script.js"></script>

    <!-- Ion icon -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

      <!-- Dark mode -->
      <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark')
        } else {
        document.documentElement.classList.remove('dark')
        }

        // Whenever the user explicitly chooses light mode
        localStorage.theme = 'light'

        // Whenever the user explicitly chooses dark mode
        localStorage.theme = 'dark'

        // Whenever the user explicitly chooses to respect the OS preference
        localStorage.removeItem('theme')
    </script>

</body>
</html>