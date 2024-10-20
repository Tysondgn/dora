<?PHP
include('config/dbconnection.php');

// Function to sanitize user input
function sanitize_input($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

// Function to hash the password
function hash_password($password)
{
    return password_hash($password, PASSWORD_BCRYPT);
}

// Signup Logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Signup"])) {
    $username = sanitize_input($_POST["logname"]);
    $email = sanitize_input($_POST["email"]);
    $password = sanitize_input($_POST["pass"]);
    $hashed_password = hash_password($password);
    $phone = sanitize_input($_POST["logphone"]);
    $userType = sanitize_input($_POST["userType"]);

    $sql = "INSERT INTO userprofiles (Username, Password, Email, PhoneNumber, UserType) VALUES ('$username', '$hashed_password', '$email', '$phone', '$userType')";

    if ($conn->query($sql) === TRUE) {
        echo "Signup successful!";
        echo "<script>alert('Signup successful!');</script>";

    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Login Logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Login"])) {
    $email = sanitize_input($_POST["logemail"]);
    $password = sanitize_input($_POST["logpass"]);

    $sql = "SELECT * FROM userprofiles WHERE Email='$email'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Check if the entered password matches the hashed one in database
        if (password_verify($password, $row["Password"])) {
            echo "Login successful!";
            echo "<script>alert('Login successful!');</script>";
            if($row["UserType"] == 'Admin'){
                $_SESSION['user-admin'] = $row["UserType"];
                header("Location: adminpanel.php");
            }elseif($row["UserType"] == 'KitchenStaff'){
                $_SESSION['user-kitchen'] = $row["UserType"];
                header("Location: adminpanel.php");
            }elseif($row["UserType"] == 'CounterStaff'){
                $_SESSION['user-counter'] = $row["UserType"];
                header("Location: adminpanel.php");
            }elseif($row["UserType"] == 'waiter'){
                $_SESSION['waiter'] = $row["UserType"];
                header("Location: adminpanel.php");
            }elseif($row["UserType"] == 'Customer'){
                $_SESSION['user-customer'] = $row["UserType"];
                header("Location: adminpanel.php");
            }

            // Redirect to the user's dashboard or homepage
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "User not found!";
    }
}

$conn->close();
?>

<div class="section">
    <div class="container">
        <div class="row full-height justify-content-center">
            <div class="col-12 text-center align-self-center py-5">
                <h1>Welcome</h1>
                <div class="section pb-5 pt-5 pt-sm-2 text-center">
                    <h6 class="mb-0 pb-3"><span>Log In </span><span>Sign Up</span></h6> <input class="checkbox"
                        type="checkbox" id="reg-log" name="reg-log" /> <label for="reg-log"></label>
                    <div class="card-3d-wrap mx-auto">
                        <div class="card-3d-wrapper">
                            <div class="card-front">
                                <div class="center-wrap">
                                    <div class="section text-center">
                                        <form action="" method="POST">
                                            <h4 class="mb-4 pb-3">Log In</h4>
                                            <div class="form-group">
                                                <input type="email" name="logemail" class="form-style"
                                                    placeholder="Your Email" id="logemail" autocomplete="none" required>
                                                <i class="input-icon fa fa-at"></i>
                                            </div>
                                            <div class="form-group mt-2">
                                                <input type="password" name="logpass" class="form-style"
                                                    placeholder="Your Password" id="logpass" autocomplete="none" required>
                                                <i class="input-icon fa fa-lock"></i>
                                            </div>
                                            <button type="submit" class="btn mt-4" name="Login">Login</button>
                                        </form>
                                        <p class="mb-0 mt-4 text-center"> <a href="#0" class="link">Forgot your
                                                password?</a> </p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-back">
                                <div class="center-wrap">
                                    <div class="section text-center">
                                        <form action="" method="POST">
                                            <h4 class="mb-4 pb-3">Sign Up</h4>
                                            <div class="form-group"> <input type="text" name="logname"
                                                    class="form-style" placeholder="Your Full Name" id="logname"
                                                    autocomplete="none"> <i class="input-icon fa fa-user" required></i> </div>
                                            <div class="form-group mt-2"> <input type="email" name="email"
                                                    class="form-style" placeholder="Your Email" id="email"
                                                    autocomplete="none" required> <i class="input-icon fa fa-at"></i> </div>
                                            <div class="form-group mt-2">
                                                <input type="password" name="pass" class="form-style"
                                                    placeholder="Your Password" id="pass" autocomplete="none" required>
                                                <i class="input-icon fa fa-lock" id="togglePassword"></i>
                                            </div>
                                            <div class="form-group mt-2"> <input type="tel" name="logphone"
                                                    class="form-style" placeholder="Your Phone Number" id="logphone"
                                                    autocomplete="none" required> <i class="input-icon fa fa-lock"></i> </div>
                                            <div class="form-group mt-2">
                                                <select name="userType" class="form-style" id="userType" required>
                                                    <option value="" disabled selected>Select User Type</option>
                                                    <option value="kitchenStaff">Kitchen Staff</option>
                                                    <option value="counterStaff">Counter Staff</option>
                                                    <option value="waiter">Waiter Staff</option>
                                                </select>
                                                <i class="input-icon fa fa-user"></i>
                                            </div>
                                            <button type="submit" class="btn mt-4" name="Signup">Signup</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('logpass');

    togglePassword.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Toggle eye icon between open and closed
        if (type === 'password') {
            togglePassword.classList.remove('fa-eye');
            togglePassword.classList.add('fa-eye-slash');
        } else {
            togglePassword.classList.remove('fa-eye-slash');
            togglePassword.classList.add('fa-eye');
        }
    });
</script>