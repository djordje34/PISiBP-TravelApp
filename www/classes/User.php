<?php

require_once 'functions/user_type.php';
class User
{
    private $_db,
            $_data,
            $_sessionName,
            $_isLoggedIn,
            $_permissionLevel;

    public function __construct($user = null)
    {
        $this->_db = DB::getInstance();

        $this->_sessionName = Config::get('session/session_name');

        if (!$user) {
            if (Session::exists($this->_sessionName)) {
                $user = Session::get($this->_sessionName);
                if ($this->find($user)) {
                    $this->_isLoggedIn = true;
                } else {
                    $this->logout();
                }
            }
        } else {
            $this->find($user);
        }
    }
    public function setDB($db)
    {
        $this->_db = $db;
    }
    public function create($tip_korisnika, $fields = array(), $db = '')
    {
        if ($db == ''){
            $db = $this->_db;
        }
        if (!is_array($fields)) {
            throw new Exception('Pogresni podaci');
        }
        if (count($fields) != 2) {
            throw new Exception('Nedovoljno polja');
        }
        if (!in_array($tip_korisnika, array('zaposleni', 'admin'))) {
            throw new Exception('Nepostojeca tabela');
        }
        if ($tip_korisnika == 'zaposleni') {
            $tip_db = 1;
        } elseif ($tip_korisnika == 'admin') {
            $tip_db = 2;
        } else {
            throw new Exception('Unreachable');
        }
        if (!$db->insert('korisnik', array('tip' => $tip_db))) {
            throw new Exception('Desio se problem tokom kreiranja naloga.');
        }
        $id = $db->query('SELECT * FROM korisnik ORDER BY korisnik_id DESC LIMIT 1')->first()->korisnik_id;
        $fields['korisnik_id'] = $id;
        if (!$db->insert($tip_korisnika, $fields)) {
            throw new Exception('Desio se problem tokom kreiranja naloga.');
        }
    }
    public function update($table, $fields = array(), $id = null)
    {
        if (!$id && $this->isLoggedIn()) {
            $id = $this->data()->korisnik_id;
        }
        if (!$this->_db->updateUser($table, $id, $fields)) {
            throw new Exception('Desio se problem tokom azuriranja.');
        }
    }
    public function find($user = null)
    {
        if ($user) {
            // if user had a numeric username this FAILS...
            $data = '';
            if (is_numeric($user)) {
                $field = 'korisnik_id';
                $this->_permissionLevel = $this->_db->get('korisnik', array($field, '=', $user))->results()[0]->tip;
                if ($this->_permissionLevel == 1) {
                    $data = $this->_db->get('zaposleni', array($field, '=', $user))->results();
                }
                if ($this->_permissionLevel == 2) {
                    $data = $this->_db->get('admin', array($field, '=', $user))->results();
                }
                if ($data) {
                    $this->_data = $data[0];
                    return true;
                }
            } else {
                $field = 'email';
                $zaposleni = $this->_db->get('zaposleni', array($field, '=', $user))->results();
                $admin = $this->_db->get('admin', array($field, '=', $user))->results();
            }
            if (!is_numeric($user) && $zaposleni) {
                $this->_data = $zaposleni[0];
                $this->_permissionLevel = 1;
                return true;
            }
            if (!is_numeric($user) && $admin) {
                $this->_data = $admin[0];
                $this->_permissionLevel = 2;
                return true;
            }//THIS WHOLE THING IS DUMB BUT IT WORKS
        }
        return false;
    }
    public function login($email = null, $password = null)
    {
        // check if username has been defined
        if (!$email && !$password && $this->exists()) {
            Session::put($this->_sessionName, $this->data()->korisnik_id);
        } else {
            $user = $this->find($email);
            if ($user) {
                if (password_verify($password, $this->data()->password)) {
                    Session::put($this->_sessionName, $this->data()->korisnik_id);
                    $this->_isLoggedIn = true;
                    return true;
                }
            }
        }
        return false;
    }
    public function exists()
    {
        return (!empty($this->_data)) ? true : false;
    }
    public function logout()
    {
        $this->_isLoggedIn = false;
        Session::delete($this->_sessionName);
    }
    public function data()
    {
        return $this->_data;
    }
    public function isLoggedIn()
    {
        return $this->_isLoggedIn;
    }
    public function permissionLevel()
    {
        return $this->_permissionLevel;
    }
}
