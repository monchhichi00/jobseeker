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
if(isset($_POST['update']))
{
//Getting Post Values
$FullName=$_POST['fname'];  
$aboutme=$_POST['aboutme']; 
$skills=$_POST['skills'];
//Getting User Id
$uid=$_SESSION['jsid'];

$sql="update  tbljobseekers set FullName=:fname,AboutMe=:aboutme,Skills=:skills where id=:uid";
$query = $dbh->prepare($sql);
// Binding Post Values
$query-> bindParam(':fname', $FullName, PDO::PARAM_STR);
$query-> bindParam(':uid', $uid, PDO::PARAM_STR);
$query->bindParam(':aboutme',$aboutme,PDO::PARAM_STR);
$query->bindParam(':skills',$skills,PDO::PARAM_STR);
$query->execute();

echo '<script>alert("Account detail has been updated")</script>';
    echo "<script>window.location.href ='profile.php'</script>";
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Hanap-Kita</title>
    
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
                
                <!-- User Menu -->
                <div class="flex items-center space-x-4">
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
                            <span class="font-medium"><?php echo htmlentities($_SESSION['jsfname']); ?></span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                            <a href="my-profile.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-t-lg">My Profile</a>
                            <a href="change-password.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Change Password</a>
                            <a href="profile.php" class="block px-4 py-2 text-primary bg-blue-50 font-medium">Edit Profile</a>
                            <a href="logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-b-lg">Log Out</a>
                        </div>
                        <?php $cnt=$cnt+1;}} ?>
                    </div>
                    <a href="applied-jobs.php" class="text-gray-700 hover:text-primary font-medium transition-colors">Applied Jobs</a>
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

    <!-- Page Header -->
    <div class="bg-gradient-to-br from-blue-50 to-indigo-100 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Edit Your Profile</h1>
                <p class="text-xl text-gray-600">Update your account information and preferences</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <?php
        //Getting User Id
        $uid=$_SESSION['jsid'];
        // Fetching user details
        $sql = "SELECT * from  tbljobseekers  where id=:uid";
        $query = $dbh -> prepare($sql);
        $query-> bindParam(':uid', $uid, PDO::PARAM_STR);
        $query->execute();
        $results=$query->fetchAll(PDO::FETCH_OBJ);
        if($query->rowCount() > 0) {
            foreach($results as $result) {
        ?>

        <!-- Profile Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-primary to-primary-light p-6">
                <h2 class="text-2xl font-bold text-white">Profile Information</h2>
                <p class="text-blue-100 mt-1">Keep your profile up to date for better job opportunities</p>
            </div>
            
            <div class="p-8">
                <form method="post" class="space-y-8">
                    <!-- Personal Information Section -->
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-user text-primary mr-3"></i>
                            Personal Information
                        </h3>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Full Name -->
                            <div>
                                <label for="fname" class="block text-sm font-medium text-gray-700 mb-2">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="fname"
                                       name="fname" 
                                       placeholder="Enter your full name" 
                                       required 
                                       autocomplete="off" 
                                       value="<?php echo htmlentities($result->FullName)?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            </div>

                            <!-- Email (Read-only) -->
                            <div>
                                <label for="emailid" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email Address
                                </label>
                                <input type="email" 
                                       id="emailid"
                                       name="emailid" 
                                       readonly 
                                       autocomplete="off" 
                                       value="<?php echo htmlentities($result->EmailId)?>"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-gray-500 cursor-not-allowed">
                                <p class="text-xs text-gray-500 mt-1">Email cannot be changed</p>
                            </div>

                            <!-- Contact Number (Read-only) -->
                            <div>
                                <label for="contactnumber" class="block text-sm font-medium text-gray-700 mb-2">
                                    Contact Number
                                </label>
                                <input type="text" 
                                       id="contactnumber"
                                       name="contactnumber" 
                                       autocomplete="off" 
                                       value="<?php echo htmlentities($result->ContactNumber)?>" 
                                       readonly
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-gray-500 cursor-not-allowed">
                                <p class="text-xs text-gray-500 mt-1">Contact number cannot be changed</p>
                            </div>

                            <!-- Registration Date (Read-only) -->
                            <div>
                                <label for="regdate" class="block text-sm font-medium text-gray-700 mb-2">
                                    Registration Date
                                </label>
                                <input type="text" 
                                       id="regdate"
                                       name="regdate" 
                                       readonly 
                                       autocomplete="off" 
                                       value="<?php echo htmlentities($result->RegDate)?>"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-gray-500 cursor-not-allowed">
                            </div>
                        </div>
                    </div>

                    <!-- Professional Information Section -->
                    <div class="border-t border-gray-200 pt-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-briefcase text-primary mr-3"></i>
                            Professional Information
                        </h3>
                        
                        <div class="space-y-6">
                            <!-- About Me -->
                            <div>
                                <label for="aboutme" class="block text-sm font-medium text-gray-700 mb-2">
                                    About Me <span class="text-red-500">*</span>
                                </label>
                                <textarea id="aboutme"
                                          name="aboutme" 
                                          required 
                                          autocomplete="off" 
                                          rows="4"
                                          placeholder="Tell us about yourself, your experience, and career goals..."
                                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all resize-none"><?php echo htmlentities($result->AboutMe)?></textarea>
                            </div>

                            <!-- Skills -->
                            <div>
                                <label for="skills" class="block text-sm font-medium text-gray-700 mb-2">
                                    Skills
                                </label>
                                <input type="text" 
                                       id="skills"
                                       name="skills" 
                                       placeholder="e.g. PHP, PDO, HTML, Customer Service, etc." 
                                       autocomplete="off" 
                                       value="<?php echo htmlentities($result->Skills)?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                                <p class="text-xs text-gray-500 mt-1">Separate skills with commas</p>
                            </div>
                        </div>
                    </div>

                    <!-- Resume and Profile Picture Section -->
                    <div class="border-t border-gray-200 pt-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-file-alt text-primary mr-3"></i>
                            Documents & Media
                        </h3>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Resume -->
                            <div class="bg-gray-50 rounded-xl p-6">
                                <div class="flex items-center mb-4">
                                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-file-pdf text-red-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Resume</h4>
                                        <p class="text-sm text-gray-500">Current resume document</p>
                                    </div>
                                </div>
                                <div class="flex space-x-3">
                                    <a href="Jobseekersresumes/<?php echo $result->Resume;?>" 
                                       target="_blank"
                                       class="flex-1 bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors text-center">
                                        <i class="fas fa-eye mr-2"></i>View Resume
                                    </a>
                                    <a href="resume.php?updateid=<?php echo $result->id;?>" 
                                       class="flex-1 bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark transition-colors text-center">
                                        <i class="fas fa-upload mr-2"></i>Update Resume
                                    </a>
                                </div>
                            </div>

                            <!-- Profile Picture -->
                            <div class="bg-gray-50 rounded-xl p-6">
                                <div class="flex items-center mb-4">
                                    <div class="w-12 h-12 bg-gray-200 rounded-lg overflow-hidden mr-4">
                                        <?php if($result->ProfilePic!=""): ?>
                                            <img src="images/<?php echo $result->ProfilePic;?>" 
                                                 class="w-full h-full object-cover" 
                                                 alt="Profile Picture">
                                        <?php else: ?>
                                            <div class="w-full h-full flex items-center justify-center">
                                                <i class="fas fa-user text-gray-400"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Profile Picture</h4>
                                        <p class="text-sm text-gray-500">Your profile photo</p>
                                    </div>
                                </div>
                                <a href="change-profilepics.php" 
                                   class="block w-full bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark transition-colors text-center">
                                    <i class="fas fa-camera mr-2"></i>Change Profile Picture
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="border-t border-gray-200 pt-8">
                        <div class="flex justify-end space-x-4">
                            <a href="my-profile.php" 
                               class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">
                                Cancel
                            </a>
                            <button type="submit" 
                                    name="update" 
                                    class="px-8 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold">
                                <i class="fas fa-save mr-2"></i>Update Profile
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php }} ?>

        <!-- Quick Actions -->
        <div class="mt-8 grid md:grid-cols-3 gap-6">
            <a href="my-profile.php" 
               class="bg-white border border-gray-200 rounded-2xl p-6 hover:shadow-lg transition-all group">
                <div class="text-primary text-2xl mb-4 group-hover:scale-110 transition-transform">
                    <i class="fas fa-user-circle"></i>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">View Profile</h3>
                <p class="text-gray-600 text-sm">See how your profile looks to employers</p>
            </a>
            
            <a href="add-education.php" 
               class="bg-white border border-gray-200 rounded-2xl p-6 hover:shadow-lg transition-all group">
                <div class="text-primary text-2xl mb-4 group-hover:scale-110 transition-transform">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Add Education</h3>
                <p class="text-gray-600 text-sm">Update your educational background</p>
            </a>
            
            <a href="add-experience.php" 
               class="bg-white border border-gray-200 rounded-2xl p-6 hover:shadow-lg transition-all group">
                <div class="text-primary text-2xl mb-4 group-hover:scale-110 transition-transform">
                    <i class="fas fa-briefcase"></i>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Add Experience</h3>
                <p class="text-gray-600 text-sm">Share your work experience</p>
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-16 mt-16">
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
                        <li><a href="my-profile.php" class="text-gray-400 hover:text-white transition-colors">My Profile</a></li>
                        <li><a href="applied-jobs.php" class="text-gray-400 hover:text-white transition-colors">Applied Jobs</a></li>
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
                
                <!-- Support -->
                <div>
                    <h4 class="font-semibold mb-4">Support</h4>
                    <ul class="space-y-2">
                        <li><a href="change-password.php" class="text-gray-400 hover:text-white transition-colors">Change Password</a></li>
                        <li><a href="profile.php" class="text-gray-400 hover:text-white transition-colors">Edit Profile</a></li>
                        <li><a href="logout.php" class="text-gray-400 hover:text-white transition-colors">Logout</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-12 pt-8 text-center">
                <p class="text-gray-400">&copy; 2024 Hanap-Kita. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>
<?php } ?>