<?php
session_start();
//Database Configuration File
include('includes/config.php');
error_reporting(0);
//verifying Session
if(strlen($_SESSION['jsid'])==0)
  { 
header('location:emp-login.php');
}
else{
if(isset($_POST['update']))
{
//getting resume
$img=$_FILES["image"]["name"];
$uid=$_SESSION['jsid'];
$resume=$_FILES["resume"]["name"];
// get the image extension
$extension = substr($resume,strlen($resume)-4,strlen($resume));
// allowed extensions
$allowed_extensions = array(".pdf","docx",".doc");
// Validation for allowed extensions .in_array() function searches an array for a specific value.
if(!in_array($extension,$allowed_extensions))
{
echo "<script>alert('Invalid Resume format. Only pdf, doc, docx format allowed');</script>";
}
else
{
//rename the image file
$resumename=md5($resume).time().$extension;
// Code for move image into directory
move_uploaded_file($_FILES["resume"]["tmp_name"],"Jobseekersresumes/".$resumename);

$sql="update  tbljobseekers set Resume=:resumename where id=:uid";
$query = $dbh->prepare($sql);
// Binding Post Values
$query-> bindParam(':uid', $uid, PDO::PARAM_STR);
$query->bindParam(':resumename',$resumename,PDO::PARAM_STR);
$query->execute();

echo '<script>alert("Your resume has been updated");</script>';
    echo "<script>window.location.href ='profile.php'</script>";

}

}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Resume | Hanap-Kita</title>
    
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
    <?php include('includes/header.php');?>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-blue-50 to-indigo-100 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-primary rounded-2xl mb-6">
                    <i class="fas fa-file-upload text-2xl text-white"></i>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Update Your Resume
                </h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Keep your resume current to attract the best job opportunities. Upload your latest resume here.
                </p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Success/Error Messages -->
            <?php if(@$error){ ?>
            <div class="mb-8 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
                    <div>
                        <strong class="text-red-800">Error:</strong>
                        <span class="text-red-700"><?php echo htmlentities($error);?></span>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if(@$msg){ ?>
            <div class="mb-8 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-600 mr-3"></i>
                    <div>
                        <strong class="text-green-800">Success:</strong>
                        <span class="text-green-700"><?php echo htmlentities($msg);?></span>
                    </div>
                </div>
            </div>
            <?php } ?>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-primary to-primary-light px-8 py-6">
                    <h2 class="text-2xl font-bold text-white">Resume Management</h2>
                    <p class="text-blue-100 mt-2">Manage and update your professional resume</p>
                </div>

                <!-- Card Content -->
                <div class="p-8">
                    <form name="empsignup" enctype="multipart/form-data" method="post" class="space-y-8">
                        
                        <?php
                        //Getting Employer Id
                        $uid=$_SESSION['jsid'];

                        $sql = "SELECT * from  tbljobseekers  where id=:uid";
                        $query = $dbh -> prepare($sql);
                        $query-> bindParam(':uid', $uid, PDO::PARAM_STR);
                        $query->execute();
                        $results=$query->fetchAll(PDO::FETCH_OBJ);
                        if($query->rowCount() > 0)
                        {
                        foreach($results as $result)
                        {
                        ?>

                        <div class="grid md:grid-cols-2 gap-8">
                            
                            <!-- Current Resume -->
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                        <i class="fas fa-file-pdf text-red-600 mr-2"></i>
                                        Current Resume
                                    </h3>
                                    
                                    <div class="bg-gray-50 rounded-xl p-6 text-center border-2 border-dashed border-gray-300">
                                        <div class="mb-4">
                                            <i class="fas fa-file-pdf text-4xl text-red-600 mb-3"></i>
                                            <p class="text-sm text-gray-600 mb-3">Click below to view your current resume</p>
                                        </div>
                                        
                                        <a href="Jobseekersresumes/<?php echo $result->Resume;?>" 
                                           target="_blank"
                                           class="inline-flex items-center justify-center px-6 py-3 bg-white border-2 border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition-all font-medium">
                                            <i class="fas fa-eye mr-2"></i>
                                            View Current Resume
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Upload New Resume -->
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                        <i class="fas fa-cloud-upload-alt text-primary mr-2"></i>
                                        Upload New Resume
                                    </h3>
                                    
                                    <div class="relative">
                                        <input type="file" 
                                               name="resume" 
                                               required 
                                               accept=".pdf,.doc,.docx"
                                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                               id="resume-upload">
                                        
                                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-dashed border-primary rounded-xl p-8 text-center hover:from-blue-100 hover:to-indigo-100 transition-colors">
                                            <div class="mb-4">
                                                <i class="fas fa-cloud-upload-alt text-4xl text-primary mb-3"></i>
                                                <p class="text-lg font-medium text-gray-900 mb-2">
                                                    Choose Resume File
                                                </p>
                                                <p class="text-sm text-gray-600 mb-4">
                                                    or drag and drop your file here
                                                </p>
                                            </div>
                                            
                                            <div class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg font-medium">
                                                <i class="fas fa-folder-open mr-2"></i>
                                                Browse Files
                                            </div>
                                            
                                            <div class="mt-4 text-xs text-gray-500">
                                                Supported formats: PDF, DOC, DOCX (Max 10MB)
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div id="file-info" class="mt-4 hidden">
                                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                            <div class="flex items-center">
                                                <i class="fas fa-file-check text-green-600 mr-3"></i>
                                                <div>
                                                    <p class="font-medium text-green-800" id="file-name"></p>
                                                    <p class="text-sm text-green-600" id="file-size"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php 
                        }}
                        ?>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 pt-8 border-t border-gray-200">
                            <button type="submit" 
                                    name="update" 
                                    class="flex-1 bg-primary text-white px-8 py-4 rounded-xl font-semibold hover:bg-primary-dark transition-colors flex items-center justify-center">
                                <i class="fas fa-save mr-2"></i>
                                Update Resume
                            </button>
                            
                            <a href="profile.php" 
                               class="flex-1 bg-gray-100 text-gray-700 px-8 py-4 rounded-xl font-semibold hover:bg-gray-200 transition-colors flex items-center justify-center text-center">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Back to Profile
                            </a>
                        </div>

                    </form>
                </div>
            </div>

            <!-- Help Section -->
            <div class="mt-12 bg-blue-50 rounded-2xl p-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-lightbulb text-accent mr-2"></i>
                    Resume Tips
                </h3>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-check-circle text-green-600 mt-1"></i>
                            <p class="text-gray-700">Keep your resume updated with your latest skills and experience</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-check-circle text-green-600 mt-1"></i>
                            <p class="text-gray-700">Use clear, professional formatting for better readability</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-check-circle text-green-600 mt-1"></i>
                            <p class="text-gray-700">Include relevant keywords for your target job positions</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-check-circle text-green-600 mt-1"></i>
                            <p class="text-gray-700">Keep file size under 10MB for faster processing</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Footer -->
    <?php include('includes/footer.php');?>

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
    
    <!-- File Upload Enhancement -->
    <script>
        document.getElementById('resume-upload').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const fileInfo = document.getElementById('file-info');
            const fileName = document.getElementById('file-name');
            const fileSize = document.getElementById('file-size');
            
            if (file) {
                fileName.textContent = file.name;
                fileSize.textContent = `Size: ${(file.size / 1024 / 1024).toFixed(2)} MB`;
                fileInfo.classList.remove('hidden');
            } else {
                fileInfo.classList.add('hidden');
            }
        });
        
        // Drag and drop functionality
        const uploadArea = document.querySelector('#resume-upload').parentElement;
        
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
            uploadArea.classList.add('border-primary-dark', 'bg-blue-100');
        }
        
        function unhighlight(e) {
            uploadArea.classList.remove('border-primary-dark', 'bg-blue-100');
        }
        
        uploadArea.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                document.getElementById('resume-upload').files = files;
                const event = new Event('change', { bubbles: true });
                document.getElementById('resume-upload').dispatchEvent(event);
            }
        }
    </script>
</body>
</html>
<?php }
?>