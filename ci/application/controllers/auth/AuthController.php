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

        dd('test');

        if($this->user_model->is_logged_in())
        {
            redirect('profile');
        }
        $user = $this->user_model->get_user('email', $this->input->post('email'));

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['logged_in'] = true;

        redirect('profile');
    }
    public function logout()
    {
        // OR session_destroy()
        unset($_SESSION['user_id'], $_SESSION['logged_in'] );
        redirect('profile');
    }
    public function sign_up()
    {
        $this->user_model->__insert();
        $this->send_email_verification($this->input->post('email'), $_SESSION['token']);
    }
    private function send_email_verification($email, $token)
    {
        $this->email->from("reihanagam7@gmail.com", "reihan agam");
        $this->email->to($email);
        $this->email->subject("registration application local");
        $this->email->message("Click Button for Confirmation Regisration
            <a href=".base_url()."/verify/$email/$token>Confirmation Email</a>");
        $this->email->set_mailtype('html');
        $this->email->send();
    }
}