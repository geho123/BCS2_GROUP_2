<?php 

// Include config file
include 'database/db_connect.php';

// Initialize the variable
$message = "";
$toastClass = "";
// Start session
session_start();
// Retrieve the user ID from the session
$userID = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : null;

if (is_null($userID)) {
    echo "User Id is not set in the session.";
    exit;
}

$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
$toastClass = isset($_SESSION['toastClass']) ? $_SESSION['toastClass'] : '';
// Clear the session messages after displaying
unset($_SESSION['message']);
unset($_SESSION['toastClass']);

// Query to retrive user's information from database
$sql = "SELECT firstname, lastname, username, email, bio, gender, relationship, photo, facebook, instagram, twitter, youtube, github FROM user WHERE UserID = ?";
$stmt = mysqli_prepare($conn, $sql);

// Bind parameters and execute the query
mysqli_stmt_bind_param($stmt, "i", $userID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Check if user info is found
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    // Store user information in variables
    $fname = $row['firstname'];
    $lname = $row['lastname'];
    $username = $row['username'];
    $email = $row['email'];
    $bio = $row['bio'];
    $gender = $row['gender'];
    $rship = $row['relationship'];
    $photo = $row['photo'];
    $facebook = $row['facebook'];
    $instagram = $row['instagram'];
    $twitter = $row['twitter']; 
    $youtube = $row['youtube']; 
    $github = $row['github'];
} else {
    echo "No user information found.";
    exit;
}

// Define gender and relationship options
$genderOptions = ['Male', 'Female'];
$relationshipOptions = ['Single', 'Relationship', 'Married', 'Engaged'];

// Close the connection
mysqli_close($conn);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicon -->
    <link href="assets/images/favicon.png" rel="icon" type="image/png">

    <!-- for toastr to work correctly -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">


    <!-- title and description-->
    <title>Socialite</title>
    <meta name="description" content="Socialite - Social sharing network HTML Template">
   
    <!-- css files -->
    <link rel="stylesheet" href="assets/css/tailwind.css">
    <link rel="stylesheet" href="assets/css/style.css">  
    
    <!-- google font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
 
    <div id="wrapper">

        <!-- header -->
        <header class="z-[100] h-[--m-top] fixed top-0 left-0 w-full flex items-center bg-white/80 sky-50 backdrop-blur-xl border-b border-slate-200 dark:bg-dark2 dark:border-slate-800">

            <div class="flex items-center w-full xl:px-6 px-2 max-lg:gap-10">

                <div class="2xl:w-[--w-side] lg:w-[--w-side-sm]">

                    <!-- left -->
                    <div class="flex items-center gap-1"> 

                        <!-- icon menu -->
                        <button uk-toggle="target: #site__sidebar ; cls :!-translate-x-0"
                                class="flex items-center justify-center w-8 h-8 text-xl rounded-full hover:bg-gray-100 xl:hidden dark:hover:bg-slate-600 group"> 
                                <ion-icon name="menu-outline" class="text-2xl group-aria-expanded:hidden"></ion-icon>
                                <ion-icon name="close-outline" class="hidden text-2xl group-aria-expanded:block"></ion-icon>
                        </button>
                        <div id="logo">
                            <a href="feed.php"> 
                                <img src="assets/images/logo.png" alt="" class="w-28 md:block hidden dark:!hidden">
                                <img src="assets/images/logo-light.png" alt="" class="dark:md:block hidden">
                                <img src="assets/images/logo-mobile.png" class="hidden max-md:block w-20 dark:!hidden" alt="">
                                <img src="assets/images/logo-mobile-light.png" class="hidden dark:max-md:block w-20" alt="">
                            </a>
                        </div>
                         
                    </div>

                </div>
                <div class="flex-1 relative">

                    <div class="max-w-[1220px] mx-auto flex items-center">

                        <!-- header icons -->
                        <div class="flex items-center sm:gap-4 gap-2 absolute right-5 top-1/2 -translate-y-1/2 text-black">
        
                            <!-- profile -->
                            <div  class="rounded-full relative bg-secondery cursor-pointer shrink-0">
                                <img src="<?php echo !empty($photo) ? htmlspecialchars($photo) : 'assets/images/avatars/avatar-2.jpg'; ?>" alt="" class="sm:w-9 sm:h-9 w-7 h-7 rounded-full shadow shrink-0"> 
                            </div>
                            <div  class="hidden bg-white rounded-lg drop-shadow-xl dark:bg-slate-700 w-64 border2"
                                uk-drop="offset:6;pos: bottom-right;animate-out: true; animation: uk-animation-scale-up uk-transform-origin-top-right ">
                                <a href="#">
                                    <div class="p-4 py-5 flex items-center gap-4">
                                        <img src="<?php echo !empty($photo) ? htmlspecialchars($photo) : 'assets/images/avatars/avatar-2.jpg'; ?>" alt="" class="w-10 h-10 rounded-full shadow">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-black">
                                            <?php echo htmlspecialchars($fname)." ".htmlspecialchars($lname); ?>
                                            </h4>
                                            <div class="text-sm mt-1 text-blue-600 font-light dark:text-white/70">
                                            <?php echo htmlspecialchars($email); ?>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <hr class="dark:border-gray-600/60">
                                <nav class="p-2 text-sm text-black font-normal dark:text-white">
                                    <a href="setting.php">
                                        <div class="flex items-center gap-2.5 hover:bg-secondery p-2 px-2.5 rounded-md dark:hover:bg-white/10"> 
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            My Account
                                        </div>
                                    </a>
                                    <hr class="-mx-2 my-2 dark:border-gray-600/60">
                                    <a href="logout.php">
                                        <div class="flex items-center gap-2.5 hover:bg-secondery p-2 px-2.5 rounded-md dark:hover:bg-white/10"> 
                                            <svg class="w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            Log Out 
                                        </div>
                                    </a>
                                </nav>
                            </div> 

                            <div class="flex items-center gap-2 hidden">
                                <img src="assets/images/avatars/avatar-2.jpg" alt="" class="w-9 h-9 rounded-full shadow">
        
                                <div class="w-20 font-semibold text-gray-600"> Hamse </div>
        
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg> 
        
                            </div> 
        
                        </div>

                    </div> 

                </div>

            </div>

        </header>
    
        <!-- sidebar -->
        <div id="site__sidebar" class="fixed top-0 left-0 z-[99] pt-[--m-top] overflow-hidden transition-transform xl:duration-500 max-xl:w-full max-xl:-translate-x-full">

            <!-- sidebar inner -->
            <div class="p-2 max-xl:bg-white shadow-sm 2xl:w-72 sm:w-64 w-[80%] h-[calc(100vh-64px)] relative z-30 max-lg:border-r dark:max-xl:!bg-slate-700 dark:border-slate-700">
        
                <div class="pr-4" data-simplebar>

                    <nav id="side">
                    
                        <ul>
                            <li class="active">
                                <a href="feed.php">
                                    <img src="assets/images/icons/home.png" alt="feeds" class="w-6">
                                    <span> Feed </span> 
                                </a>
                            </li>
                    </ul>
                    </nav>

                </div>

            </div>

            <!-- sidebar overly -->
            <div id="site__sidebar__overly" 
                class="absolute top-0 left-0 z-20 w-screen h-screen xl:hidden backdrop-blur-sm"
                uk-toggle="target: #site__sidebar ; cls :!-translate-x-0"> 
            </div>
        </div>

        <!-- main contents -->
        <main id="site__main" class="2xl:ml-[--w-side]  xl:ml-[--w-side-sm] p-2.5 h-[calc(100vh-var(--m-top))] mt-[--m-top]">

        
            <div class="max-w-3xl mx-auto">

                <div class="box relative rounded-lg shadow-md">
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
                    <div class="flex md:gap-8 gap-4 items-center md:p-8 p-6 md:pb-4">

                        <div class="relative md:w-20 md:h-20 w-12 h-12 shrink-0"> 
                            <!-- <form id="uploadForm" action="upload-profile-pic.php" method="post" enctype="multipart/form-data"> -->
                                <label for="file" class="cursor-pointer">
                                    <img id="img" src="<?php echo !empty($photo) ? htmlspecialchars($photo) : 'assets/images/avatars/avatar-2.jpg'; ?>" class="object-cover w-full h-full rounded-full" alt=""/>
                                    <input type="file" id="file"  class="hidden" accept="image/*"/>
                                    <!-- <input type="file" name="profilePicture" class="hidden" id="file" accept="image/*" onchange="uploadProfilePicture()"> -->
                                </label>

                                <label for="file" class="md:p-1 p-0.5 rounded-full bg-slate-600 md:border-4 border-white absolute -bottom-2 -right-2 cursor-pointer dark:border-slate-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="md:w-4 md:h-4 w-3 h-3 fill-white">
                                        <path d="M12 9a3.75 3.75 0 100 7.5A3.75 3.75 0 0012 9z" />
                                        <path fill-rule="evenodd" d="M9.344 3.071a49.52 49.52 0 015.312 0c.967.052 1.83.585 2.332 1.39l.821 1.317c.24.383.645.643 1.11.71.386.054.77.113 1.152.177 1.432.239 2.429 1.493 2.429 2.909V18a3 3 0 01-3 3h-15a3 3 0 01-3-3V9.574c0-1.416.997-2.67 2.429-2.909.382-.064.766-.123 1.151-.178a1.56 1.56 0 001.11-.71l.822-1.315a2.942 2.942 0 012.332-1.39zM6.75 12.75a5.25 5.25 0 1110.5 0 5.25 5.25 0 01-10.5 0zm12-1.5a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
                                    </svg>
                                    <input type="file" id="file"  class="hidden" accept="image/*"/>
                                    <!-- <input type="file" name="profilePicture" class="hidden" id="file" accept="image/*" onchange="uploadProfilePicture()"> -->
                                    <!-- <input  type="file" name="profile_pic" id="file" accept="image/*" onchange="this.form.submit();" class="hidden" /> -->
                                </label> 
                            <!-- </form> -->
                        </div>
    
                        <div class="flex-1">
                            <h3 class="md:text-xl text-base font-semibold text-black dark:text-white"> 
                            <?php echo htmlspecialchars($fname)." ".htmlspecialchars($lname); ?>        
                        </h3>
                            <p class="text-sm text-blue-600 mt-1 font-normal">
                            <?php echo htmlspecialchars($email); ?>     
                            </p>
                        </div>
                    </div>
    
                    <!-- nav tabs -->
                    <div class="relative border-b" tabindex="-1" uk-slider="finite: true">
    
                        <nav class="uk-slider-container overflow-hidden nav__underline px-6 p-0 border-transparent -mb-px">
            
                            <ul class="uk-slider-items w-[calc(100%+10px)] !overflow-hidden" 
                                uk-switcher="connect: #setting_tab ; animation: uk-animation-slide-right-medium, uk-animation-slide-left-medium"> 
                                
                                <li class="w-auto pr-2.5"> <a href="#"> Description </a> </li>
                                <li class="w-auto pr-2.5"> <a href="#"> Social</a> </li>
                                <li class="w-auto pr-2.5"> <a href="#"> Passwords</a> </li> 
                                
                            </ul>
                        
                        </nav>
                                
                        <a class="absolute -translate-y-1/2 top-1/2 left-0 flex items-center w-20 h-full p-2 py-1 justify-start bg-gradient-to-r from-white via-white dark:from-slate-800 dark:via-slate-800" href="#" uk-slider-item="previous"> <ion-icon name="chevron-back" class="text-2xl ml-1"></ion-icon> </a>
                        <a class="absolute right-0 -translate-y-1/2 top-1/2 flex items-center w-20 h-full p-2 py-1 justify-end bg-gradient-to-l from-white via-white dark:from-slate-800 dark:via-slate-800" href="#" uk-slider-item="next">  <ion-icon name="chevron-forward" class="text-2xl mr-1"></ion-icon> </a>
                
                    </div> 
    
    
                    <div id="setting_tab" class="uk-switcher md:py-12 md:px-20 p-6 overflow-hidden text-black text-sm"> 
                        
                        <!-- tab user basic info -->
                        <div>
                            <div>
    
                            <form method="POST" action="user-decsription.php">
                                
                                <div class="space-y-6">
    
                                    <div class="md:flex items-center gap-10">
                                        <label class="md:w-32 text-right"> Username </label>
                                        <div class="flex-1 max-md:mt-4">
                                            <input type="text" name="username" placeholder="Monroe" class="lg:w-1/2 w-full" value="<?php echo htmlspecialchars($username); ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="md:flex items-center gap-10">
                                        <label class="md:w-32 text-right"> Email </label>
                                        <div class="flex-1 max-md:mt-4">
                                            <input name="email" type="text" placeholder="info@mydomain.com" class="w-full" value="<?php echo htmlspecialchars($email); ?>">
                                        </div>
                                    </div> 
            
                                    <div class="md:flex items-start gap-10">
                                        <label class="md:w-32 text-right"> Bio </label>
                                        <div class="flex-1 max-md:mt-4">
                                            <textarea name="bio" class="w-full" rows="5" placeholder="Inter your Bio">
                                            <?php echo htmlspecialchars($bio); ?>
                                            </textarea>
                                        </div>
                                    </div> 
        
                                    <div class="md:flex items-center gap-10">
                                        <label class="md:w-32 text-right"> Gender </label>
                                        <div class="flex-1 max-md:mt-4">
                                            <select name="gender" id="gender" class="!border-0 !rounded-md lg:w-1/2 w-full">
                                            <?php foreach ($genderOptions as $option): ?>
                                                <option value="<?php echo htmlspecialchars($option); ?>" 
                                                    <?php echo ($gender === $option) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($option); ?>
                                                </option>
                                            <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="md:flex items-center gap-10">
                                        <label class="md:w-32 text-right"> Relationship </label>
                                        <div class="flex-1 max-md:mt-4">
                                            <select name="relationship" id="relationship" class="!border-0 !rounded-md lg:w-1/2 w-full">
                                               <?php foreach ($relationshipOptions as $option): ?>
                                                    <option value="<?php echo htmlspecialchars($option); ?>" 
                                                        <?php echo ($rship === $option) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($option); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <!-- <div class="md:flex items-start gap-10 " hidden>
                                        <label class="md:w-32 text-right"> Avatar </label>
                                        <div class="flex-1 flex items-center gap-5 max-md:mt-4">
                                            <img src="assets/images/avatars/avatar-3.jpg" alt="" class="w-10 h-10 rounded-full">
                                            <button type="submit" class="px-4 py-1 rounded-full bg-slate-100/60 border dark:bg-slate-700 dark:border-slate-600 dark:text-white"> Change</button>
                                        </div>
                                    </div> -->
    
                                </div>
      
                                <div class="flex items-center gap-4 mt-16 lg:pl-[10.5rem]">
                                    <button type="submit" class="button lg:px-6 bg-secondery max-md:flex-1"> Cancle</button>
                                    <button type="submit" class="button lg:px-10 bg-primary text-white max-md:flex-1"> Save <span class="ripple-overlay"></span></button>
                                </div>
                                </form>
                            </div> 
    
                        </div>
    
                        <!-- tab socialinks -->   
                        <div>
    
                            <div class="max-w-md mx-auto">
                                <form method="POST" action="user-socialmedia.php">
                                <div class="font-normal text-gray-400">
                                
                                    <div>
                                        <h4 class="text-xl font-medium text-black dark:text-white"> Social Links </h4>
                                        <p class="mt-3 font-normal text-gray-600 dark:text-white">We may still send you important notifications about your account and content outside of you preferred notivications settings</p>
                                    </div>
    
                                    <div class="space-y-6 mt-8">
    
                                        <div class="flex items-center gap-3">
                                            <div class="bg-blue-50 rounded-full p-2 flex ">
                                                <ion-icon name="logo-facebook" class="text-2xl text-blue-600"></ion-icon> 
                                            </div>
                                            <div class="flex-1">
                                                <input type="text" name="facebook" class="w-full" placeholder="http://www.facebook.com/myname" value="<?php echo htmlspecialchars($facebook); ?>">
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <div class="bg-pink-50 rounded-full p-2 flex ">
                                                <ion-icon name="logo-instagram" class="text-2xl text-pink-600"></ion-icon> 
                                            </div>
                                            <div class="flex-1">
                                                <input type="text" name="instagram" class="w-full" placeholder="http://www.instagram.com/myname" value="<?php echo htmlspecialchars($instagram); ?>">
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <div class="bg-sky-50 rounded-full p-2 flex ">
                                                <ion-icon name="logo-twitter" class="text-2xl text-sky-600"></ion-icon> 
                                            </div>
                                            <div class="flex-1">
                                                <input type="text" name="twitter" class="w-full" placeholder="http://www.twitter.com/myname" value="<?php echo htmlspecialchars($twitter); ?>">
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <div class="bg-red-50 rounded-full p-2 flex ">
                                                <ion-icon name="logo-youtube" class="text-2xl text-red-600"></ion-icon> 
                                            </div>
                                            <div class="flex-1">
                                                <input type="text" name="youtube" class="w-full" placeholder="http://www.youtube.com/myname" value="<?php echo htmlspecialchars($youtube); ?>">
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <div class="bg-slate-50 rounded-full p-2 flex ">
                                                <ion-icon name="logo-github" class="text-2xl text-black"></ion-icon> 
                                            </div>
                                            <div class="flex-1">
                                                <input type="text" name="github" class="w-full" placeholder="http://www.github.com/myname" value="<?php echo htmlspecialchars($github); ?>">
                                            </div>
                                        </div>
    
                                    </div> 
                                   
                                </div> 
                                
                                <div class="flex items-center justify-center gap-4 mt-16">
                                    <button type="submit" class="button lg:px-6 bg-secondery max-md:flex-1"> Cancle</button>
                                    <button type="submit" class="button lg:px-10 bg-primary text-white max-md:flex-1"> Save</button>
                                </div>
                                </form>
                            </div>
    
                        </div>
                        
                        <!-- tab password-->
                        <div>
    
                            <div>
                                <form action="user-password-reset.php" method="POST">
                                <div class="space-y-6 max-w-lg mx-auto">
    
                                    <div class="md:flex items-center gap-16 justify-between max-md:space-y-3">
                                        <label class="md:w-40 text-right"> Current Password </label>
                                        <div class="flex-1 max-md:mt-4">
                                            <input type="password" name="currentPassword" placeholder="******" class="w-full">
                                        </div>
                                    </div>
                                  
                                    <div class="md:flex items-center gap-16 justify-between max-md:space-y-3">
                                        <label class="md:w-40 text-right"> New password </label>
                                        <div class="flex-1 max-md:mt-4">
                                            <input type="password" name="newPassword" placeholder="******" class="w-full">
                                        </div>
                                    </div>
    
                                    <div class="md:flex items-center gap-16 justify-between max-md:space-y-3">
                                        <label class="md:w-40 text-right"> Repeat password </label>
                                        <div class="flex-1 max-md:mt-4">
                                            <input type="password" name="confirmNewPassword" placeholder="******" class="w-full">
                                        </div>
                                    </div>
    
                                    <hr class="border-gray-100 dark:border-gray-700">
    
                                    <div class="md:flex items-center gap-16 justify-between">
                                        <label class="md:w-40 text-right"> Two-factor authentication </label>
                                        <div class="flex-1 max-md:mt-4">
                                            <select class="w-full !border-0 !rounded-md">
                                                <option value="1">Enable</option>
                                                <option value="2">Disable</option> 
                                            </select>
                                        </div>
                                    </div>
    
    
                                </div>
                                
                                <div class="flex items-center justify-center gap-4 mt-16">
                                    <button type="submit" class="button lg:px-6 bg-secondery max-md:flex-1"> Cancle</button>
                                    <button type="submit" class="button lg:px-10 bg-primary text-white max-md:flex-1"> Save</button>
                                </div>
                                </form>
                            </div>
                            
                        </div>
                    </div>
                    
                
                </div>
    
                
            </div>
            
        </main>

    </div>


    <!-- toast r -->
    <script>
        let toastElList = [].slice.call(document.querySelectorAll('.toast'))
        let toastList = toastElList.map(function (toastEl) {
            return new bootstrap.Toast(toastEl, { delay: 3000 });
        });
        toastList.forEach(toast => toast.show());
    </script> 

    <!-- Javascript  -->
    <script src="assets/js/uikit.min.js"></script>
    <script src="assets/js/simplebar.js"></script>
    <script src="assets/js/script.js"></script>
 

    <!-- script for profile picture -->
    <script>
        document.getElementById('file').addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const formData = new FormData();
                formData.append('profile_picture', file);

                fetch('upload-profile-pic.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        });
    </script>
 
    <!-- Ion icon -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>