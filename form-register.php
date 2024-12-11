<?php
include 'database/db_connect.php';

//Declare variables and initialize with empty values
$message = "";
$toastClass = "";
$firtName = "";
$lastName = "";
$email = "";
$password = "";
$confirmPassword = "";

//Process form data afater submited
if($_SERVER["REQUEST_METHOD"] == "POST"){
  //perfom validation
  if(empty(trim($_POST["fname"]))){
        $message = "Please enter a First Name";
        $toastClass = "#ff8d21"; // Primary color
  }
  elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["fname"]))){
    $message = "First Name can only contain letters, numbers, and underscores.";
    $toastClass = "#c30010"; // Primary color
  }
  else{
    $firtName = trim($_POST["fname"]);
  }

    //perfom validation for last name
  if(empty(trim($_POST["lname"]))){
    $message = "Please enter a Last Name";
    $toastClass = "#ff8d21"; // Primary color
  }
  elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["lname"]))){
  $message = "Last Name can only contain letters, numbers, and underscores.";
  $toastClass = "#c30010"; // Primary color
  }
  else{
  $firtName = trim($_POST["lname"]);
  }

  //valdation for email
  if(empty(trim($_POST["email"]))){
    $message = "Please enter a Email";
    $toastClass = "#ff8d21"; // Primary color
  }
  elseif(!preg_match('/^[a-zA-Z0-9]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', 
  trim($_POST["email"]))){
    $message = "Invalid email address.";
    $toastClass = "#c30010"; // Primary color
    }
    else{
      //prepare a select statement used to checkif email exist in database
      $sql = "SELECT id FROM user WHERE email = ?";

      if($stmt = mysqli_prepare($conn, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_email);
        
        // Set parameters
        $param_email = trim($_POST["email"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            /* store result */
            mysqli_stmt_store_result($stmt);
            
            if(mysqli_stmt_num_rows($stmt) == 1){
                $message = "This email is already taken.";
                $toastClass = "#ff8d21"; // Primary color
            } else{
                $username = trim($_POST["email"]);
            }
        } else{
          $message = "Oops! Something went wrong. Please try again later.";
          $toastClass = "#c30010"; // Primary color
        }
        // Close statement
        mysqli_stmt_close($stmt);
      }
    }

     // Validate password
     if(empty(trim($_POST["password"]))){
      $message = "Please enter a password.";  
      $toastClass = "#ff8d21"; // Primary color   
      } elseif(strlen(trim($_POST["password"])) < 8){
          $message = "Password must have atleast 8 characters.";
          $toastClass = "#ff8d21"; // Primary color
      } else{
          $password = trim($_POST["password"]);
      }

      // Validate confirm password
    if(empty(trim($_POST["confirmPassword"]))){
          $message = "Please confirm password.";    
          $toastClass = "#ff8d21"; // Primary color 
      } else{
          $confirm_password = trim($_POST["confirmPassword"]);
          if(empty($password_err) && ($password != $confirm_password)){
              $message = "Password did not match.";
              $toastClass = "#c30010"; // Primary color
          }
      }
      
      // Check input errors before inserting in database
      if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){

        // Prepare an insert data to database statement
        $sql = "INSERT INTO user (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";

        if($stmt = mysqli_prepare($conn, $sql)){
          // Bind variables to the prepared statement as parameters
          mysqli_stmt_bind_param($stmt, "ssss",
          $param_firstname,
          $param_lastname,
                $param_email,
                $param_password);
          
          // Set parameters
          $param_firstname = $firtName;
          $param_lastname = $lastName;
          $param_email = $email;
          $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
          
          // Attempt to execute the prepared statement
          if(mysqli_stmt_execute($stmt)){
              // Redirect to login page
              header("location: form-login.php");
          } else{
            $message = "Oops! Something went wrong. Please try again later.";
            $toastClass = "#c30010"; // Primary color
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
   
    <!-- css files -->
    <link rel="stylesheet" href="assets/css/tailwind.css">
    <link rel="stylesheet" href="assets/css/style.css">  
    
    <!-- google font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
 
</head>
<body>

  <div class="sm:flex">
    
    <div class="relative lg:w-[580px] md:w-96 w-full p-10 min-h-screen bg-white shadow-xl flex items-center pt-10 dark:bg-slate-900 z-10">

      <div class="w-full lg:max-w-sm mx-auto space-y-10" uk-scrollspy="target: > *; cls: uk-animation-scale-up; delay: 100 ;repeat: true">

        <!-- logo image-->
        <a href="#"> <img src="assets/images/logo.png" class="w-28 absolute top-10 left-10 dark:hidden" alt=""></a>
        <a href="#"> <img src="assets/images/logo-light.png" class="w-28 absolute top-10 left-10 hidden dark:!block" alt=""></a>

        <!-- logo icon optional -->
        <div class="hidden">
          <img class="w-12" src="assets/images/logo-icon.png" alt="Socialite html template">
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
          <h2 class="text-2xl font-semibold mb-1.5"> Sign up to get started </h2>
          <p class="text-sm text-gray-700 font-normal">If you already have an account, <a href="form-login.html" class="text-blue-700">Login here!</a></p>
        </div>
 

        <!-- form -->
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="space-y-7 text-sm text-black font-medium dark:text-white"  uk-scrollspy="target: > *; cls: uk-animation-scale-up; delay: 100 ;repeat: true">
            
          <div class="grid grid-cols-2 gap-4 gap-y-7">
     
            <!-- first name -->
            <div>
                <label for="email" class="">First name</label>
                <div class="mt-2.5">
                    <input id="text" name="fname" type="text"  autofocus="" placeholder="First name"  class="!w-full !rounded-lg !bg-transparent !shadow-sm !border-slate-200 dark:!border-slate-800 dark:!bg-white/5"> 
                </div>
            </div>

            <!-- Last name -->
            <div>
                <label for="email" class="">Last name</label>
                <div class="mt-2.5">
                    <input id="text" name="lname" type="text" placeholder="Last name"  class="!w-full !rounded-lg !bg-transparent !shadow-sm !border-slate-200 dark:!border-slate-800 dark:!bg-white/5"> 
                </div>
            </div>
          
            <!-- email -->
            <div class="col-span-2">
                <label for="email" class="">Email address</label>
                <div class="mt-2.5">
                    <input id="email" name="email" type="email" placeholder="Email"  class="!w-full !rounded-lg !bg-transparent !shadow-sm !border-slate-200 dark:!border-slate-800 dark:!bg-white/5"> 
                </div>
            </div>

            <!-- password -->
            <div>
              <label for="email" class="">Password</label>
              <div class="mt-2.5">
                  <input id="password" name="password" type="password" placeholder="***"  class="!w-full !rounded-lg !bg-transparent !shadow-sm !border-slate-200 dark:!border-slate-800 dark:!bg-white/5">  
              </div>
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="email" class="">Confirm Password</label>
                <div class="mt-2.5">
                    <input id="password" name="confirmPassword" type="password" placeholder="***"  class="!w-full !rounded-lg !bg-transparent !shadow-sm !border-slate-200 dark:!border-slate-800 dark:!bg-white/5">  
                </div>
            </div>

            <div class="col-span-2">

              <label class="inline-flex items-center" id="rememberme">
                <input type="checkbox" id="accept-terms" class="!rounded-md accent-red-800" />
                <span class="ml-2">you agree to our <a href="#" class="text-blue-700 hover:underline">terms of use </a> </span>
              </label>
              
            </div>


            <!-- submit button -->
            <div class="col-span-2">
              <button type="submit" class="button bg-primary text-white w-full">Get Started</button>
            </div>

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
                        <img class="w-12" src="assets/images/logo-icon.png" alt="Socialite html template">
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
                      <img class="w-12" src="assets/images/logo-icon.png" alt="Socialite html template">
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