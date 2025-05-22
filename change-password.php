<?php
session_start();
//Database Configuration File
include('includes/config.php');
error_reporting(0);
//verifying Session
if(strlen($_SESSION['jsid'])==0)
  { 
header('location:logout.php');
}
else{
if(isset($_POST['change']))
{
//Getting User Id
$uid=$_SESSION['jsid'];
// Getting Post Values
$currentpassword=$_POST['currentpassword'];
$newpassword=$_POST['newpassword'];
//new password hasing 
$options = ['cost' => 12];
$hashednewpass=password_hash($newpassword, PASSWORD_BCRYPT, $options);

  // Fetch data from database on the basis of Employee session if
    $sql ="SELECT Password FROM tbljobseekers WHERE (id=:uid )";
    $query= $dbh -> prepare($sql);
    $query-> bindParam(':uid', $uid, PDO::PARAM_STR);
    $query-> execute();
    $results=$query->fetchAll(PDO::FETCH_OBJ);
if($query->rowCount() > 0)
{
foreach ($results as $row) {
$hashpass=$row->Password;
}
//if current password verfied new password wil be updated in the databse
if (password_verify($currentpassword, $hashpass)) {
$sql="update  tbljobseekers set Password=:hashednewpass where id=:uid";
$query = $dbh->prepare($sql);
// Binding Post Values
$query->bindParam(':hashednewpass',$hashednewpass,PDO::PARAM_STR);
$query-> bindParam(':uid', $uid, PDO::PARAM_STR);
$query->execute();

echo '<script>alert("Your password successully changed")</script>';
} else {
echo '<script>alert("Your current password is wrong")</script>';

}
}

}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password | Hanap-Kita</title>
    
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
    
    <script type="text/javascript">
    function valid()
    {
    if(document.chngpwd.newpassword.value!= document.chngpwd.confirmpassword.value)
    {
    alert("New Password and Confirm Password Field do not match  !!");
    document.chngpwd.confirmpassword.focus();
    return false;
    }
    return true;
    }
    </script>
</head>

<body class="bg-gray-50 font-sans">
    <!-- Header -->
    <?php include('includes/header.php'); ?>

    <!-- Main Content -->
    <div class="min-h-screen py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-8">
                <a href="index.php" class="hover:text-primary transition-colors">Home</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="my-profile.php" class="hover:text-primary transition-colors">Profile</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-gray-900">Change Password</span>
            </nav>

            <!-- Page Header -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-primary bg-opacity-10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-lock text-2xl text-primary"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Change Password</h1>
                <p class="text-gray-600">Update your password to keep your account secure</p>
            </div>

            <!-- Change Password Form -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                <form name="chngpwd" method="post" onSubmit="return valid();" class="space-y-6">
                    <!-- Current Password -->
                    <div>
                        <label for="currentpassword" class="block text-sm font-medium text-gray-700 mb-2">
                            Current Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" 
                                   id="currentpassword"
                                   name="currentpassword" 
                                   required
                                   class="block w-full pl-10 pr-3 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                   placeholder="Enter your current password">
                        </div>
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="newpassword" class="block text-sm font-medium text-gray-700 mb-2">
                            New Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-key text-gray-400"></i>
                            </div>
                            <input type="password" 
                                   id="newpassword"
                                   name="newpassword" 
                                   required
                                   class="block w-full pl-10 pr-3 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                   placeholder="Enter your new password">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            Password must be at least 6 characters long
                        </p>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="confirmpassword" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirm New Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-check-circle text-gray-400"></i>
                            </div>
                            <input type="password" 
                                   id="confirmpassword"
                                   name="confirmpassword" 
                                   required
                                   class="block w-full pl-10 pr-3 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                   placeholder="Confirm your new password">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" 
                                name="change"
                                class="w-full bg-primary text-white py-4 px-6 rounded-xl font-semibold hover:bg-primary-dark focus:ring-4 focus:ring-primary focus:ring-opacity-20 transition-all">
                            <i class="fas fa-save mr-2"></i>
                            Change Password
                        </button>
                    </div>
                </form>
            </div>

            <!-- Security Tips -->
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 mt-8">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-blue-900 mb-2">Password Security Tips</h3>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>• Use a combination of letters, numbers, and special characters</li>
                            <li>• Make your password at least 8 characters long</li>
                            <li>• Avoid using personal information in your password</li>
                            <li>• Don't reuse passwords from other accounts</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Back to Profile -->
            <div class="text-center mt-8">
                <a href="my-profile.php" class="text-primary hover:text-primary-dark font-medium transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Profile
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include('includes/footer.php'); ?>

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

<!-- Done 9 -->