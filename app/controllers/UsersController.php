<?php

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
                    'active' => 1
                );
                $allUsers = $this->get_model('User')->save($data);

                if ($allUsers) {
                    $_SESSION['login_user'] = $allUsers;
                    $this->redirect('/users/profile');
                }
            } else {
                if (!empty($isValid)) {
                    foreach ($isValid as $key => $value) {
                        $this->get_view()->set($key, $value);
                    }
                }

            }
        }
        $this->get_view()->render('users/register');
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

        switch ($ext) {
            case 'png':
                $function = 'imagecreatefrompng';
                break;
            case 'gif':
                $function = 'imagecreatefromgif';
                break;
            case 'jpg' :
                $function = 'imagecreatefromjpeg';
                break;
            case 'jpeg':
                $function = 'imagecreatefromjpeg';
                break;
            default:
                $function = 'imagecreatefromjpeg';
                break;
        }

        list($width_orig, $height_orig) = getimagesize($filename);

        var_dump($fileName);
        die;
    }

    public function login()
    {
        if (isset($_SESSION['login_user'])) {
            $this->redirect('/users/profile');
        }

        if (isset($_POST) && !empty($_POST)) {
            $isValid = $this->get_model('User')->validateLogin($_POST);
            if ($isValid === true) {

                $exists = $this->get_model('User')->find('first', array(
                    'select' => array(
                        'id',
                        'first_name',
                        'last_name',
                        'email',
                        'image'
                    ),
                    'where' => array(
                        'email =' => $_POST['email'],
                        'password =' => md5($_POST['password'])
                    )
                ));


                if ($exists) {
                    $_SESSION['login_user'] = $exists['id'];
                    $this->redirect('/users/profile');
                } else {
                    $this->get_view()->set('login_error', 'User with that credentials does not exists');
                }
            } else {
                if (!empty($isValid)) {
                    foreach ($isValid as $key => $value) {
                        $this->get_view()->set($key, $value);
                    }
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

        $this->get_view()->set('user', $user);
        $this->get_view()->render('users/profile');
    }
}