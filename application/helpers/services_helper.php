<?php

function run_on_local_server()
{
  if ($_SERVER['REMOTE_ADDR'] === '127.0.0.1' || $_SERVER['REMOTE_ADDR'] === '::1') {
    return true;
  } else {
    return false;
  }
}

if (!function_exists('view')) {
  /**
   *  @param string $token
   *  @return mixed
   */
  function view($body_view_path = null, $bdata = [], $title = "Portal")
  {
    try {
      $ci = &get_instance();
      $ci->load->view("layout/header", ['title' => $title, 'Logo' => Logo, 'HF_title' => HF_title]);
      if (!is_null($body_view_path)) {
        $ci->load->view($body_view_path, $bdata);
      }
      // $ci->load->view("layout/footer", ['Version' =>Version ,'HF_title' => HF_title]);
      $ci->load->view("layout/footer");
    } catch (\Throwable $th) {
      return false;
    }
  }
}

if (!function_exists('public_view')) {
  /**
   *  @param string $token
   *  @return mixed
   */
  function public_view($body_view_path = null, $bdata = [], $title = "Debasish Blog")
  {
    try {
      $ci = &get_instance();
      $ci->load->view("public/layout/header", ['title' => $title, 'Logo' => Logo, 'HF_title' => HF_title]);
      if (!is_null($body_view_path)) {
        $ci->load->view("public/".$body_view_path, $bdata);
      }
      $ci->load->view("public/layout/footer");
    } catch (\Throwable $th) {
      return false;
    }
  }
}

if (!function_exists('pp')) {
  function pp($value = null)
  {
    echo "<pre>";
    print_r($value);
    echo "</pre>";
    exit();
  }
}

if (!function_exists('portal_url')) {
  function portal_url($segment = '')
  {
    return base_url("portal/{$segment}");
  }
}



if (!function_exists('is_post')) {

  /**
   * If the method of request is POST return true else false.
   * 
   *  @return bool  */
  function is_post()
  {
    return (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST')  ? true : false;
  }
}

if (!function_exists('is_get')) {

  /**
   * If the method of request is GET return true else false.
   * 
   *  @return bool  */
  function is_get()
  {
    return (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET')  ? true : false;
  }
}


// form error modify
if (!function_exists('set_form_error')) {
  /**
   * form error show
   * Note: return string when error found else not
   * 
   * @param string $field field name
   * @param bool $error_bs_element if true error element html return else ' is-invalid ' string return
   * 
   * @return mixed
   */
  function set_form_error($field = "", $error_bs_element = true)
  {
    if ($error = form_error($field)) {
      if ($error_bs_element) {
        return "<div class='invalid-feedback'>{$error}</div>";
      } else {
        return " is-invalid ";
      }
    } else return "";
  }
}

if (!function_exists('set_message')) {
  /**
   * set message for ci flashdata
   * 
   * @param string $type bs alet class end part like :- (success,danger,info,warning)
   * @param string $message if you want
   * 
   * @return void
   */
  function set_message($type = "info", $message = "")
  {
    $ci = &get_instance();
    $ci->session->set_flashdata('type', $type);
    $ci->session->set_flashdata('message', $message);
  }
}

if (!function_exists('get_message')) {
  /**
   * bs alert message of ci flashdata
   * 
   * @return string
   */
  function get_message()
  {
    $ci = &get_instance();
    $type = $ci->session->flashdata('type');
    $message = $ci->session->flashdata('message');
    if ($type && $message) {
      return "<div class='alert alert-{$type}' role='alert'>{$message}</div>";
    } else return "";
  }
}
