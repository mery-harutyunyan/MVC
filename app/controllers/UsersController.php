<?php
require_once(ROOT . DS . 'app' . DS . 'controllers' . DS . 'components' . DS . 'Mailer.php');

class UsersController extends Controller
{
    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
        $this->load_model('User');
    }

    public function register()
    {

        if (isset($_SESSION['login_user'])) {
            $this->redirect('/users/profile');
        }

        if (isset($_POST) && !empty($_POST)) {
            $isValid = $this->get_model('User')->validateAdd($_POST + $_FILES);
            if ($isValid === true) {
                $this->uploadImage($_FILES["image"]);

                $data = array(
                    'first_name' => $_POST['first_name'],
                    'last_name' => $_POST['last_name'],
                    'email' => $_POST['email'],
                    'password' => md5($_POST['password']),
                    'image' => $_FILES['image']['name'],
                    'verify_token' => md5(microtime()),
                    'active' => 0
                );
                $allUsers = $this->get_model('User')->insert($data);

                if ($allUsers) {

                    $mailData = $this->get_model('User')
                        ->select(array(
                            'first_name',
                            'last_name',
                            'email',
                            'verify_token'
                        ))
                        ->where(
                            'id', "=", $allUsers
                        )
                        ->first();

                    $mail = new Mailer();
                    $mail->sendVerificationEmail($mailData);
                    $this->redirect('/');
                }
            } else {
                if (!empty($isValid)) {
                    $this->get_view()->viewData($isValid);
                }

            }
        }
        $this->get_view()->render('users/register');
    }

    /**
     * verify account and login user
     */
    public function accountVerification($token)
    {

        if (!$token) {
            $this->redirect('/');
        }

        $userExists = $this->get_model('User')
            ->select(array(
                'id',
                'first_name',
                'last_name',
                'email',
                'verify_token'
            ))
            ->where(
                'verify_token', '=', $token
            )
            ->where('active', '=', 0)
            ->first();


        if (empty($userExists)) {
            $this->redirect('/');
        }
        $update = $this->get_model('User')
            ->where(
                'id', '=', $userExists['id']
            )
            ->update(array(
                'verify_token' => null,
                'active' => 1
            ));

        if ($update) {
            $_SESSION['login_user'] = $userExists['id'];
            $this->redirect('/users/profile');
        }
    }

    /**
     * upload image for user
     */
    public function uploadImage($file)
    {
        $target_dir = ROOT . DS . 'public' . DS . 'img' . DS . 'profile_images' . DS;
        $target_file = $target_dir . basename($file['name']);


        $check = getimagesize($file["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($file["tmp_name"], $target_file)) {

                $this->createThumb($target_file, 30, 30);
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * create thumb for user image
     */
    public function createThumb($file, $thumbWidth = 30, $thumbHeight = 30)
    {

        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $fileName = pathinfo($file, PATHINFO_FILENAME);
        $thumb_dir = ROOT . DS . 'public' . DS . 'img' . DS . 'profile_images' . DS . 'thumbs' . DS;
        $thumb_file = $thumb_dir . $fileName . '.' . $ext;

        switch ($ext) {
            case 'png':
                $imageCreateFunction = 'imagecreatefrompng';
                $imageFunction = 'imagepng';
                break;
            case 'gif':
                $imageCreateFunction = 'imagecreatefromgif';
                $imageFunction = 'imagegif';
                break;
            case 'jpg' :
                $imageCreateFunction = 'imagecreatefromjpeg';
                $imageFunction = 'imagejpeg';
                break;
            case 'jpeg':
                $imageCreateFunction = 'imagecreatefromjpeg';
                $imageFunction = 'imagejpeg';
                break;
            default:
                $imageCreateFunction = 'imagecreatefromjpeg';
                $imageFunction = 'imagejpeg';
                break;
        }


        list($originWidth, $originHeight) = getimagesize($file);


        $thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);
        if ($ext == 'png') {
            imagealphablending($thumb, false);
            imagesavealpha($thumb, true);
        }

        $image = $imageCreateFunction($file);

        $minRect = min($originWidth, $originHeight);


        imagecopyresized($thumb, $image, 0, 0, ($originWidth - $minRect) / 2, ($originHeight - $minRect) / 2, $thumbWidth, $thumbHeight, $minRect, $minRect);

// Output
        $imageFunction($thumb, $thumb_file);
    }

    public function login()
    {
        if (isset($_SESSION['login_user'])) {
            $this->redirect('/users/profile');
        }

        if (isset($_POST) && !empty($_POST)) {
            $isValid = $this->get_model('User')->validateLogin($_POST);
            if ($isValid === true) {

                $exists = $this->get_model('User')
                    ->select(array(
                        'id',
                        'first_name',
                        'last_name',
                        'email',
                        'image',
                        'active'
                    ))
                    ->where('email', '=', $_POST['email'])
                    ->where('password', '=', md5($_POST['password']))
                    ->first();


                if ($exists && $exists['active'] == 1) {
                    $_SESSION['login_user'] = $exists['id'];
                    $this->redirect('/users/profile');
                } elseif ($exists && $exists['active'] == 0) {
                    $this->get_view()->viewData(array(
                        'login_error' => 'Please verify your account'
                    ));
                } else {
                    $this->get_view()->viewData(array(
                        'login_error' => 'User with that credentials does not exists'
                    ));
                }
            } else {
                if (!empty($isValid)) {
                    $this->get_view()->viewData($isValid);
                }

            }
        }

        $this->get_view()->render('users/login');
    }


    public function logout()
    {
        unset($_SESSION['login_user']);
        session_destroy();
        $this->redirect('/');
    }

    public function profile()
    {
        if (!isset($_SESSION['login_user'])) {
            $this->redirect('/');
        }

        $user_id = $_SESSION['login_user'];
        $user = $this->get_model('User')->getById($user_id);

        $this->get_view()->render('users/profile', $user);
    }

    /**
     * forgot Password
     */
    public function forgotPassword()
    {
        $this->get_view()->render('users/forgot_password');
    }

    /**
     * send Password Email
     */
    public function sendPasswordEmail()
    {

        if (isset($_SESSION['login_user'])) {
            $this->redirect('/users/profile');
        }

        if (isset($_POST) && !empty($_POST)) {
            $isValid = $this->get_model('User')->validateEmail($_POST);
            if ($isValid === true) {
                $userExists = $this->get_model('User')
                    ->select(array(
                        'id',
                        'first_name',
                        'last_name',
                        'email',
                        'password_token'
                    ))
                    ->where('email', '=', $_POST['email'])
                    ->where('active', '=', 1)
                    ->first();

                if ($userExists) {

                    $passwordToken = md5(microtime());
                    $update = $this->get_model('User')
                        ->where('id', '=', $userExists['id'])
                        ->update(array(
                            'password_token' => $passwordToken,
                        ));

                    $userExists['password_token'] = $passwordToken;

                    $mail = new Mailer();
                    $mail->sendPasswordChangeEmail($userExists);
                    $this->redirect('/');
                } else {

                    $this->get_view()->viewData(array(
                        'forgotpassword_error' => 'User does not exists or not verified account'
                    ));

                    $this->redirect('/users/forgotPassword');
                }
            } else {
                if (!empty($isValid)) {

                    $this->get_view()->viewData($isValid);
                }

            }
        }
    }


    /**
     * password Verification
     */
    public function passwordVerification($token = null)
    {

        if (isset($_POST) && !empty($_POST)) {
            $isValid = $this->get_model('User')->validatePasswords($_POST);

            if ($isValid === true) {
                $update = $this->get_model('User')
                    ->where(
                        'id', "=", $_POST['user_id']
                    )
                    ->update(array(
                        'password_token' => null,
                        'password' => md5($_POST['password'])
                    ));

                if ($update) {
                    $_SESSION['login_user'] = $_POST['user_id'];
                    $this->redirect('/users/profile');
                }
            } else {
                if (!empty($isValid)) {
                    $this->get_view()->viewData($isValid);
                }
            }
        } else {
            if (!$token) {
                $this->redirect('/');
            }

            $userExists = $this->get_model('User')
                ->select(array(
                    'id',
                    'first_name',
                    'last_name',
                    'email',
                    'password_token'
                ))
                ->where('password_token', '=', $token)
                ->where('active', '=', 1)
                ->first();

            $this->get_view()->viewData(array(
                'user_id' => $userExists['id']
            ));
            if (empty($userExists)) {
                $this->redirect('/');
            }
        }

        $this->get_view()->render('users/password_verification');
    }

}