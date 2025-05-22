<?php
session_start();
//Database Configuration File
include('includes/config.php');
//error_reporting(0);
//verifying Session
if(strlen($_SESSION['jsid'])==0)
  { 
header('location:emp-login.php');
}
else{
if(isset($_POST['update']))
{
//getting logo
$img=$_FILES["image"]["name"];
$uid=$_SESSION['jsid'];
$extension = substr($img,strlen($img)-4,strlen($img));
$allowed_extensions = array(".jpg","jpeg",".png",".gif");
if(!in_array($extension,$allowed_extensions))
{
echo "<script>alert('profile image has Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
}
else
{

$img=md5($img).time().$extension;
 move_uploaded_file($_FILES["image"]["tmp_name"],"images/".$img);

$sql="update  tbljobseekers set ProfilePic=:img where id=:uid";
$query = $dbh->prepare($sql);
// Binding Post Values
$query-> bindParam(':uid', $uid, PDO::PARAM_STR);
$query->bindParam(':img',$img,PDO::PARAM_STR);
$query->execute();

echo '<script>alert("Your profile pic has been updated")</script>';
    echo "<script>window.location.href ='profile.php'</script>";

}

}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Profile Picture | Hanap-Kita</title>
    
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
                <span class="text-gray-900">Change Profile Picture</span>
            </nav>

            <!-- Page Header -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-primary bg-opacity-10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-camera text-2xl text-primary"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Change Profile Picture</h1>
                <p class="text-gray-600">Upload a new profile picture to personalize your account</p>
            </div>

            <?php
            //Getting User Id
            $uid=$_SESSION['jsid'];
            $sql = "SELECT * from  tbljobseekers  where id=:uid";
            $query = $dbh -> prepare($sql);
            $query-> bindParam(':uid', $uid, PDO::PARAM_STR);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            if($query->rowCount() > 0) {
                foreach($results as $result) {
            ?>

            <!-- Profile Picture Form -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <form name="empsignup" enctype="multipart/form-data" method="post">
                    <!-- Current Profile Picture -->
                    <div class="p-8 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Current Profile Picture</h3>
                        <div class="flex justify-center">
                            <div class="relative">
                                <div class="w-32 h-32 rounded-full overflow-hidden bg-gray-100 ring-4 ring-gray-200">
                                    <?php if($result->ProfilePic != ""): ?>
                                    <img src="images/<?php echo $result->ProfilePic;?>" 
                                         alt="Current Profile" 
                                         class="w-full h-full object-cover">
                                    <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                        <i class="fas fa-user text-4xl text-gray-400"></i>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-primary rounded-full flex items-center justify-center">
                                    <i class="fas fa-camera text-white text-sm"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upload New Picture -->
                    <div class="p-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Upload New Picture</h3>
                        
                        <!-- File Upload Area -->
                        <div class="border-2 border-dashed border-gray-300 rounded-2xl p-8 text-center hover:border-primary transition-colors">
                            <div class="space-y-4">
                                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto">
                                    <i class="fas fa-cloud-upload-alt text-2xl text-gray-400"></i>
                                </div>
                                <div>
                                    <label for="image" class="cursor-pointer">
                                        <span class="text-primary font-semibold hover:text-primary-dark transition-colors">
                                            Click to upload
                                        </span>
                                        <span class="text-gray-600"> or drag and drop</span>
                                    </label>
                                    <p class="text-sm text-gray-500 mt-1">
                                        PNG, JPG, GIF up to 10MB
                                    </p>
                                </div>
                                <input type="file" 
                                       id="image"
                                       name="image" 
                                       required
                                       accept=".jpg,.jpeg,.png,.gif"
                                       class="hidden"
                                       onchange="previewImage(this)">
                            </div>
                        </div>

                        <!-- Preview Area -->
                        <div id="preview-container" class="hidden mt-6">
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Preview</h4>
                            <div class="flex justify-center">
                                <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-100 ring-2 ring-primary">
                                    <img id="preview-image" src="" alt="Preview" class="w-full h-full object-cover">
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-6">
                            <button type="submit" 
                                    name="update" 
                                    class="w-full bg-primary text-white py-4 px-6 rounded-xl font-semibold hover:bg-primary-dark focus:ring-4 focus:ring-primary focus:ring-opacity-20 transition-all">
                                <i class="fas fa-save mr-2"></i>
                                Update Profile Picture
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <?php }} ?>

            <!-- Guidelines -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6 mt-8">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-yellow-900 mb-2">Photo Guidelines</h3>
                        <ul class="text-sm text-yellow-800 space-y-1">
                            <li>• Use a clear, high-quality photo of yourself</li>
                            <li>• Make sure your face is clearly visible</li>
                            <li>• Avoid group photos or images with filters</li>
                            <li>• Keep it professional - this will be visible to employers</li>
                            <li>• Supported formats: JPG, JPEG, PNG, GIF</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Back to Profile -->
            <div class="text-center mt-8">
                <a href="profile.php" class="text-primary hover:text-primary-dark font-medium transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Profile
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include('includes/footer.php'); ?>

    <!-- JavaScript for Image Preview -->
    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    document.getElementById('preview-image').src = e.target.result;
                    document.getElementById('preview-container').classList.remove('hidden');
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Drag and drop functionality
        const uploadArea = document.querySelector('.border-dashed');
        const fileInput = document.getElementById('image');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            uploadArea.classList.add('border-primary', 'bg-blue-50');
        }

        function unhighlight(e) {
            uploadArea.classList.remove('border-primary', 'bg-blue-50');
        }

        uploadArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            fileInput.files = files;
            previewImage(fileInput);
        }
    </script>

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

<!-- Done 10 -->