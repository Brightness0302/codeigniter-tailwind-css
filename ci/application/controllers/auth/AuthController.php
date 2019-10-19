<?php

defined("BASEPATH") OR exit('No direct script access allowed');

class AuthController extends Master_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function sign_in()
    {
        $login = [];

        $check_login = $this->User->check_login($this->input->post("email"), $this->input->post("password"));

        if($check_login)
        {

            $user = $this->User->get_user('email', $this->input->post('email'));

            $data =
            [
                "id"         => $user["id"],
                "avatar"     => $user["avatar"],
                "first_name" => $user['first_name'],
                "last_name"  => $user['last_name'],
                "username"   => $user["username"],
                "email"      => $user["email"],
                "age"        => $user["age"],
                "gender"     => $user["gender"],
                "created_at" => $user["created_at"],
                "updated_at" => $user["updated_at"]
            ];

            $this->session->set_userdata("logged_in", $data);

            if($this->session->has_userdata("logged_in"))
            {
                $login["has_session"] = TRUE;
            }
            else
            {
                $login["has_session"] = FALSE;
            }

            $session_user = $this->session->userdata();

            $login['logged_in'] = TRUE;
            $login['id'] = $user['id'];
            $login['username'] = $user['username'];
            $login['title'] = 'Successfully !';
            $login['desc'] = '';
            $login['type'] = 'success';
        }
        else
        {
            $login['logged_in'] = FALSE;
            $login['title'] = 'Oops !';
            $login['desc'] = 'Please check your e-mail and password and try again !';
            $login['type'] = 'error';
        }

        echo json_encode($login);
    }
    public function sign_up()
    {
        $this->User->insert_user();
        $this->send_email_verification($this->input->post('email'), $_SESSION['token']);
    }
    public function sign_up_page()
    {
        $this->load->view("master_global/header");
        $this->load->view("auth/sign_up_page");
        $this->load->view("master_global/footer");
    }
    public function sign_out()
    {
        // OR session_destroy()
        $this->session->unset_userdata("logged_in");
    }
    private function send_email_verification($email, $token)
    {
        $this->email->from("reihanagam7@gmail.com", "reihan agam");
        $this->email->to($email);
        $this->email->subject("registration application local");
        $this->email->message("Click Button for Confirmation Regisration
            <a href=".base_url()."verify/$email/$token>Confirmation Email</a>");
        $this->email->set_mailtype('html');
        $this->email->send();
    }
    public function verify($email, $token)
    {
        $user = $this->User->get_user('email', $email);

        if(!$user)
            die('Email not Exists !');

        if($user['token'] !== $token)
            die('Token not Match !');

        $this->User->update_role($user['id'], 1);

        redirect('profile');
    }
    public function check_reserved_username()
    {
        $username = $this->input->post("username");
        $check_reserved_username = $this->User->check_reserved_username($username);

        $data = [];

        if($check_reserved_username)
        {
            $data["title"] = "Username Already Exists !";
            $data["desc"] = "Please change your Username and Try Again !";
            $data["type"] = "error";
            $data["status"] = TRUE;
        }
        else
        {
            $data["status"] = FALSE;
        }
        echo json_encode($data);
    }
    public function check_reserved_email()
    {
        $email = $this->input->post("email");
        $check_reserved_email = $this->User->check_reserved_email($email);

        $data = [];

        if($check_reserved_email)
        {
            $data["title"] = "Email Already Exists !";
            $data["desc"] = "Please change your Email and Try Again !";
            $data["type"] = "error";
            $data["status"] = TRUE;
        }
        else
        {
            $data["status"] = FALSE;
        }
        echo json_encode($data);
    }
    public function check_is_verified_email($email)
    {
        $user = $this->User->get_user('email', $email);
        if($user['is_verified'] == 0)
        {
            return FALSE;
        }

        return TRUE;
    }
}
