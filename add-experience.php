<?php
session_start();
//Database Configuration File
include('includes/config.php');
//error_reporting(0);
//verifying Session
if(strlen($_SESSION['jsid'])==0)
  { 
header('location:logout.php');
}
else{
if(isset($_POST['submit']))
{
//Getting Post Values
$employername=$_POST['employername'];  
$toe=$_POST['toe']; 
$desi=$_POST['designation'];  
$ctc=$_POST['ctc']; 
$fdate=$_POST['fdate'];  
$tdate=$_POST['tdate'];
$skills=$_POST['skills'];
//Getting User Id
$uid=$_SESSION['jsid'];
$sql="insert into tblexperience(UserID,EmployerName,EmployementType,Designation,Ctc,FromDate,ToDate)values(:uid,:employername,:toe,:desi,:ctc,:fdate,:tdate)";
$query = $dbh->prepare($sql);
// Binding Post Values
$query-> bindParam(':employername', $employername, PDO::PARAM_STR);
$query-> bindParam(':uid', $uid, PDO::PARAM_STR);
$query->bindParam(':toe',$toe,PDO::PARAM_STR);
$query->bindParam(':desi',$desi,PDO::PARAM_STR);
$query->bindParam(':ctc',$ctc,PDO::PARAM_STR);
$query->bindParam(':fdate',$fdate,PDO::PARAM_STR);
$query->bindParam(':tdate',$tdate,PDO::PARAM_STR);

$query->execute();

$LastInsertId=$dbh->lastInsertId();
   if ($LastInsertId>0) {
    echo '<script>alert("experience details has been added.")</script>';
echo "<script>window.location.href ='my-profile.php'</script>";
  }
  else
    {
         echo '<script>alert("Something Went Wrong. Please try again")</script>';
    }




}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Experience Details | Hanap-Kita</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#5F4DEE',
                        'primary-dark': '#4C3ED8',
                        'primary-light': '#7B6EF2',
                        secondary: '#F8FAFC',
                        accent: '#F59E0B'
                    },
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif']
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50 text-gray-900 font-sans">
    
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <a href="index.php" class="text-2xl font-bold text-primary">Hanap-Kita</a>
                    </div>
                </div>
                
                <!-- Navigation -->
                <nav class="hidden md:flex space-x-8">
                    <a href="index.php" class="text-gray-700 hover:text-primary font-medium transition-colors">Home</a>
                    <a href="about.php" class="text-gray-700 hover:text-primary font-medium transition-colors">About Us</a>
                    <a href="contact.php" class="text-gray-700 hover:text-primary font-medium transition-colors">Contact</a>
                </nav>
                
                <!-- Auth Buttons -->
                <div class="flex items-center space-x-4">
                    <?php if (strlen($_SESSION['jsid']==0)) {?>
                        <div class="relative group">
                            <button class="flex items-center space-x-1 text-gray-700 hover:text-primary font-medium transition-colors">
                                <i class="fas fa-user"></i>
                                <span>Account</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                                <a href="sign-up.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-t-lg">Job Seekers</a>
                                <a href="employers/emp-login.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Employers</a>
                                <a href="admin/index.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-b-lg">Admin</a>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="relative group">
                            <?php
                            $uid= $_SESSION['jsid'];
                            $sql="SELECT * from tbljobseekers where id='$uid'";
                            $query = $dbh -> prepare($sql);
                            $query->execute();
                            $results=$query->fetchAll(PDO::FETCH_OBJ);
                            $cnt=1;
                            if($query->rowCount() > 0) {
                                foreach($results as $row) {
                            ?>
                            <button class="flex items-center space-x-2 text-gray-700 hover:text-primary transition-colors">
                                <?php if($row->ProfilePic==''): ?>
                                    <img src="images/account.png" class="w-8 h-8 rounded-full">
                                <?php else: ?>
                                    <img src="images/<?php echo $row->ProfilePic;?>" class="w-8 h-8 rounded-full">
                                <?php endif;?>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                                <a href="my-profile.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-t-lg">My Profile</a>
                                <a href="change-password.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Change Password</a>
                                <a href="profile.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Edit Profile</a>
                                <a href="logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-b-lg">Log Out</a>
                            </div>
                            <?php $cnt=$cnt+1;}} ?>
                        </div>
                        <a href="applied-jobs.php" class="text-gray-700 hover:text-primary font-medium transition-colors">Applied Jobs</a>
                    <?php } ?>
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-blue-50 to-indigo-100 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="flex items-center justify-center mb-6">
                <div class="bg-primary/10 p-4 rounded-full">
                    <i class="fas fa-briefcase text-3xl text-primary"></i>
                </div>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Add Work Experience</h1>
            <p class="text-xl text-gray-600">Showcase your professional background and career journey</p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Success and Error Messages -->
            <?php if(@$error){ ?>
            <div class="mb-8 bg-red-50 border border-red-200 rounded-xl p-4">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                    <div>
                        <strong class="text-red-800">ERROR:</strong> 
                        <span class="text-red-700"><?php echo htmlentities($error);?></span>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if(@$msg){ ?>
            <div class="mb-8 bg-green-50 border border-green-200 rounded-xl p-4">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                    <div>
                        <strong class="text-green-800">Success:</strong> 
                        <span class="text-green-700"><?php echo htmlentities($msg);?></span>
                    </div>
                </div>
            </div>
            <?php } ?>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 bg-gradient-to-r from-primary to-primary-light">
                    <h2 class="text-2xl font-bold text-white">Work Experience Details</h2>
                    <p class="text-blue-100 mt-1">Add your professional work experience</p>
                </div>

                <form method="post" enctype="multipart/form-data" class="p-8">
                    <div class="grid md:grid-cols-2 gap-6">
                        
                        <!-- Employer Name -->
                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-building text-primary mr-2"></i>
                                Employer Name *
                            </label>
                            <input type="text" name="employername" required 
                                   placeholder="Name of Company/Employer"
                                   autocomplete="off"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-3 focus:ring-primary/20 focus:border-primary transition-all">
                        </div>

                        <!-- Type of Employment -->
                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-clock text-primary mr-2"></i>
                                Type of Employment *
                            </label>
                            <input type="text" name="toe" required 
                                   placeholder="e.g., Full-time, Part-time, Contract"
                                   autocomplete="off"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-3 focus:ring-primary/20 focus:border-primary transition-all">
                        </div>

                        <!-- Designation -->
                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-user-tie text-primary mr-2"></i>
                                Job Title/Designation *
                            </label>
                            <input type="text" name="designation" required 
                                   placeholder="Your job title or position"
                                   autocomplete="off"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-3 focus:ring-primary/20 focus:border-primary transition-all">
                        </div>

                        <!-- CTC -->
                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-peso-sign text-primary mr-2"></i>
                                CTC (per month)
                            </label>
                            <input type="text" name="ctc" 
                                   placeholder="Enter salary amount"
                                   autocomplete="off"
                                   pattern="[0-9]+"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-3 focus:ring-primary/20 focus:border-primary transition-all">
                        </div>

                        <!-- From Date -->
                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-calendar-alt text-primary mr-2"></i>
                                Start Date
                            </label>
                            <input type="date" name="fdate" 
                                   autocomplete="off"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-3 focus:ring-primary/20 focus:border-primary transition-all">
                        </div>

                        <!-- To Date -->
                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-calendar-check text-primary mr-2"></i>
                                End Date
                            </label>
                            <input type="date" name="tdate" 
                                   autocomplete="off"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-3 focus:ring-primary/20 focus:border-primary transition-all">
                            <p class="text-sm text-gray-500 mt-2">Leave blank if currently employed</p>
                        </div>

                        <!-- Skills (Hidden field in original, keeping for compatibility) -->
                        <input type="hidden" name="skills" value="">
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-center">
                        <button type="submit" name="submit" 
                                class="bg-primary hover:bg-primary-dark text-white font-semibold px-8 py-4 rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-3 focus:ring-primary/50">
                            <i class="fas fa-plus-circle mr-2"></i>
                            Add Work Experience
                        </button>
                    </div>

                    <!-- Back to Profile Link -->
                    <div class="mt-6 text-center">
                        <a href="my-profile.php" 
                           class="inline-flex items-center text-gray-600 hover:text-primary font-medium transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to My Profile
                        </a>
                    </div>
                </form>
            </div>

            <!-- Tips Card -->
            <div class="mt-8 bg-blue-50 border border-blue-200 rounded-2xl p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-lightbulb text-blue-500 text-xl mt-1"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-blue-900 mb-2">Tips for Adding Work Experience</h3>
                        <ul class="text-blue-800 space-y-1">
                            <li>• List your most recent experience first</li>
                            <li>• Use clear, descriptive job titles</li>
                            <li>• Include both full-time and part-time positions</li>
                            <li>• Be accurate with employment dates</li>
                            <li>• Add internships and volunteer work too</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <!-- Brand -->
                <div class="md:col-span-1">
                    <h3 class="text-2xl font-bold text-primary mb-4">Hanap-Kita</h3>
                    <?php
                    $sql="SELECT * from tblpages where PageType='contactus'";
                    $query = $dbh -> prepare($sql);
                    $query->execute();
                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                    $cnt=1;
                    if($query->rowCount() > 0) {
                        foreach($results as $row) {
                    ?>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-center space-x-2">
                            <i class="fas fa-phone"></i>
                            <span>+<?php echo htmlentities($row->MobileNumber);?></span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="fas fa-envelope"></i>
                            <span><?php echo htmlentities($row->Email);?></span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?php echo htmlentities($row->PageDescription);?></span>
                        </li>
                    </ul>
                    <?php $cnt=$cnt+1;}} ?>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h4 class="font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="about.php" class="text-gray-400 hover:text-white transition-colors">About</a></li>
                        <li><a href="contact.php" class="text-gray-400 hover:text-white transition-colors">Contact</a></li>
                        <li><a href="index.php" class="text-gray-400 hover:text-white transition-colors">Home</a></li>
                        <?php if (strlen($_SESSION['jsid']==0)) {?>
                        <li><a href="admin/index.php" class="text-gray-400 hover:text-white transition-colors">Admin</a></li>
                        <li><a href="sign-up.php" class="text-gray-400 hover:text-white transition-colors">Job Seeker</a></li>
                        <li><a href="employers/emp-login.php" class="text-gray-400 hover:text-white transition-colors">Employer</a></li>
                        <?php } ?>
                    </ul>
                </div>
                
                <!-- Job Categories -->
                <div>
                    <h4 class="font-semibold mb-4">Job Categories</h4>
                    <ul class="space-y-2">
                        <?php
                        $sql="SELECT jobCategory,count(jobId) as totaljobs from tbljobs group by jobCategory LIMIT 6";
                        $query = $dbh -> prepare($sql);
                        $query->execute();
                        $results=$query->fetchAll(PDO::FETCH_OBJ);
                        foreach($results as $row) {
                        ?>
                        <li>
                            <a href="view-categorywise-job.php?viewid=<?php echo htmlentities($row->jobCategory);?>" 
                               class="text-gray-400 hover:text-white transition-colors">
                                <?php echo htmlentities($row->jobCategory);?>
                            </a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
                
                <!-- Newsletter -->
                <div>
                    <h4 class="font-semibold mb-4">Stay Updated</h4>
                    <p class="text-gray-400 mb-4">Get the latest job opportunities delivered to your inbox.</p>
                    <div class="flex">
                        <input type="email" placeholder="Your email" 
                               class="flex-1 px-4 py-2 bg-gray-800 border border-gray-700 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        <button class="bg-primary px-4 py-2 rounded-r-lg hover:bg-primary-dark transition-colors">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-12 pt-8 text-center">
                <p class="text-gray-400">&copy; 2024 Hanap-Kita. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="js/jquery-1.11.3.min.js"></script> 
    <script src="js/bootstrap.min.js"></script> 
    <script src="js/owl.carousel.min.js"></script> 
    <script src="js/jquery.velocity.min.js"></script> 
    <script src="js/jquery.kenburnsy.js"></script> 
    <script src="js/jquery.mCustomScrollbar.concat.min.js"></script> 
    <script src="js/editor.js"></script> 
    <script src="js/jquery.accordion.js"></script> 
    <script src="js/jquery.noconflict.js"></script> 
    <script src="js/theme-scripts.js"></script> 
    <script src="js/custom.js"></script>
</body>
</html>
<?php }
?>