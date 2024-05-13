<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 *
 * Libraries Http
 *
 * This Libraries for ...
 * 
 * @package		CodeIgniter
 * @category	Libraries
 * @author    Monirul Middya
 * @param     ...
 * @return    ...
 *
 */
include "http/Request.php";
include "http/Response.php";
class Http
{

  public $response;
  public $request;
  private $ci;

  public function __construct()
  {
    $this->initialize();
    $this->ci = get_instance();
  }

  public function initialize()
  {
    // initialize Response
    $this->response = new Response;
    // initialize Request
    $this->request = new Request;
  }

  /**
   *  @param array|object $data
   *  @return string
   */
  public function jwt_encode($data)
  {
    try {
      $time = time(); // current timestamp value
      $nbf = $time;
      $exp = $time + strtotime("+30 days");
      $payload = array(
        "iss" => "localhost",
        "aud" => "localhost",
        "iat" => $time, // issued at
        "nbf" => $nbf, //not before in seconds
        "exp" => $exp, // expire time in seconds
        "data" => $data
      );
      return JWT::encode($payload, JWT_KEY, 'HS256');
    } catch (\Throwable $th) {
      return false;
    }
  }

  /**
   *  @param string $token
   *  @return mixed
   */
  public function jwt_decode($token)
  {
    try {
      // JWT::$leeway = strtotime("+2 days");
      return JWT::decode($token, new Key(JWT_KEY, 'HS256'));
    } catch (\Throwable $th) {
      return $th->getMessage();
      // return false;
    }
  }

  public function get_token($token)
  {
    try {
      if ($token) {
        $d = $this->jwt_decode($token);
        //  pp($d );
        if (is_object($d)) {
          if (in_array($d->data->type, ["ADMIN", "STUDENT", "TEACHER", "SUPPORT"])) {
            return $d->data;
          } else {
            return $this->response->create(403, "You have no access");
          }
        } else {
          return $this->response->create(403, $d);
        }
      }
    } catch (\Throwable $th) {
      return $th->getMessage();
      // return false;
    }
  }

  public function auth($methods, $roles)
  {
    if (!is_array($roles)) $roles = [$roles];
    if (!is_array($methods)) $methods = [$methods];
    $token = $this->request->getHeader("api-key");

    $api_flag = ($this->request->is_ajax() || $this->request->is_json() || $this->request->url_segment("api"));

    if (in_array(strtolower($this->request->getMethod()), $methods)) {
      if ($api_flag) {
        if ($token) {
          $d = $this->jwt_decode($token);
          if (is_object($d)) {
            if (in_array($d->data->type, $roles)) {
              return $d->data;
            } else {
              return $this->response->create(403, "You have no access");
            }
          } else {
            return $this->response->create(403, $d);
          }
        } elseif (isset($this->ci->session->userdata['logged_in']->type)) {
          // session check
          $sd = $this->ci->session->userdata['logged_in'];
          if (in_array($sd->type, $roles)) {
            return $sd;
          } else {
            return $this->response->create(403, "You have no access");
          }
        } else {
          return $this->response->create(403, "Login to access");
        }
      } else {
        if (isset($this->ci->session->userdata['logged_in']->type)) {
          // session check
          $sd = $this->ci->session->userdata['logged_in'];
          if (in_array($sd->type, $roles)) {
            return $sd;
          } else {
            // redirect to dashboard
            redirect(base_url(), 'refresh');
          }
        } else {
          // redirect to login page
          redirect(base_url("portal/login"), 'refresh');
        }
      }
    } else {
      return $this->response->create(403, "{$this->request->getMethod()} method not allowed for this request");
    }
  }

  public function session_get($key = null)
  {

    if (isset($this->ci->session->userdata['logged_in']->type)) {
      $u = $this->ci->session->userdata['logged_in'];
      if (!is_null($key)) {
        return isset($u->$key) ? $u->$key : "";
      } else return $u;
    } else return false;
  }
  public function session_gets($key = null)
  {
    $token = $this->request->getHeader("api-key");
    $api_flag = ($this->request->is_ajax() || $this->request->is_json() || $this->request->url_segment("api"));

    if ($api_flag) {
      if ($token) {
        $d = $this->jwt_decode($token);
        if (is_object($d)) {
          return $d->data->$key;
        }
      }
    } else if (isset($this->ci->session->userdata['logged_in']->type)) {
      $u = $this->ci->session->userdata['logged_in'];
      if (!is_null($key)) {
        return isset($u->$key) ? $u->$key : "";
      } else return $u;
    }
    return false;
  }



  // ------------------------------------------------------------------------
}

/* End of file Http.php */
/* Location: ./application/libraries/Http.php */