<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 *
 * Libraries MY_Form_validation
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

class MY_Form_validation extends CI_Form_validation
{

    // ------------------------------------------------------------------------

    /**
     * @var object CI_Controller
     */
    public $ci;

    public function __construct()
    {
        parent::__construct();
        $this->ci = &get_instance();
    }

    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------

    /**
     * Is Unique
     *
     * Check if the input value doesn't already exist
     * in the specified database field.
     * 
     * @deprecated 2.0.0
     *
     * @param	string	$str
     * @param	string	$field
     * @return	bool
     * Ex . rules : is_unique_custom[table_name.column.{$except_value}.except_column]
     */
    public function is_unique_custom($str, $field)
    {
        sscanf($field, '%[^.].%[^.].%[^.].%[^.]', $table, $field, $except_value, $except_column);

        $where = array($field => $str);
        if (!empty($except_value) && !empty($except_column)) {
            $where = array_merge($where, ["{$except_column} !=" => $except_value]);
        }
        return ($this->ci->db->limit(1)->get_where($table, $where)->num_rows() === 0);
    }

    /**
     * Is Unique with unlimited filter args
     *
     * Check if the input value and filter values doesn't already exist
     * in the specified database fields.
     *
     * @param	string	$str
     * @param	string	$field
     * @return	bool
     * mandatory : table_name & column 
     * optional : col1 ,col2
     * Note : we can add unlimited col like col1 ,col2 ,col3 ,col4..... in given below format
     * if want not equal then use like ` col2 !=/col2_val `
     * Ex . rules : is_unique_filter[table_name.column.col1/cal1_val.col2 !=/col2_val]
     */
    public function is_unique_filter($str, $field)
    {
        $prt = explode(".", $field);
        $table = $prt[0];
        $const_field = $prt[1];

        unset($prt[0]);
        unset($prt[1]);

        $where = array($const_field => $str);
        if ($prt) {
            $where += array_reduce($prt, function ($s, $d) {
                sscanf($d, '%[^/]/%[^/]', $key, $val);
                if (!empty($key)) {
                    return array_merge($s, [$key => $val]);
                }
            }, []);
        }
        return ($this->ci->db->limit(1)->get_where($table, $where)->num_rows() === 0);
    }

    /**
     * Is Exist Record
     *
     * Check if the input value exist or not
     * in the specified database field.
     *
     * @param	string	$str
     * @param	string	$field
     * @return	bool
     * Ex . rules : is_exist[table_name.column]
     */
    public function is_exist($str, $field)
    {
        sscanf($field, '%[^.].%[^.]', $table, $field);
        $where = array($field => $str);
        return ($this->ci->db->limit(1)->get_where($table, $where)->num_rows() > 0);
    }

    /**
     * 
     * Check if the input value valid or not
     *
     * @param	string	$date
     * @return	bool
     * Ex . rules : valid_date[mm/dd/yyyy]
     */
    function valid_date($date)
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        if (($d && $d->format('Y-m-d') === $date) === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * 
     * Check if the input value valid or not
     *
     * @param	string	$range_str
     * @param	string	$sep default `-`
     * @return	bool
     * Ex . rules : date_range_valid[-]
     */
    function date_range_valid($range_str, $sep = "-")
    {
        $rstr_part = str_replace(" ", "", explode($sep, $range_str));
        if (!strtotime($rstr_part[0]) || !strtotime($rstr_part[1])) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * Is Exist Record
     *
     * Check if the input value exist or not
     * in the specified database field.
     * 
     * @param	string	$str
     * @param	string	$field
     * @return	bool
     * Ex . rules : is_exist_where_in[table_name.column]
     */
    public function is_exist_where_in($str, $field)
    {
        try {
            $pts = explode(".", $field);
            $table = $pts[0];
            unset($pts[0]);
            $column = $pts[1];
            unset($pts[1]);
            $whr_in_column = $pts[2];
            unset($pts[2]);
            $where = array($column => $str);
            return ($this->ci->db->limit(1)->where($where)->where_in($whr_in_column, $pts)->get($table)->num_rows() > 0);
        } catch (\Throwable $th) {
            //throw $th;
            return false;
        }
    }

    /**
     * Field require when another field value exist
     *
     * @param	string	$str
     * @param	string	$field
     * @return	bool
     * Ex . rules : required_when_equal[another_field_name]
     */

    public function required_when_equal($str, $field)
    {
        try {
            sscanf($field, '%[^.].%[^.]', $post_name, $value);
            if ($_POST[$post_name] && ($_POST[$post_name] == $value)) {
                return $str != "" ? true : false;
            } else {
                return true;
            }
        } catch (\Throwable | Exception | ParseError $e) {
            return false;
        }
    }

    /**
     * Field array value required
     *
     * @param	string	$str
     * @param	string	$field
     * @return	bool
     * Ex . rules : required_array[field_name[]]
     */

    public function required_array($str, $field)
    {
        try {
            sscanf($field, '%[^.].%[^.]', $post_name, $value);
            $post = isset($_POST[$post_name]) ? $_POST[$post_name] : "";
            if (is_array($post)) {
                return array_reduce($post, function ($s, $v) {
                    if ($s === false) {
                        return false;
                    } else {
                        return $v != "" ? true : false;
                    }
                }, null);
            } else {
                return false;
            }
        } catch (\Throwable | Exception | ParseError $e) {
            return false;
        }
    }

    /**
     * IMPORTANT NOTE: PLEASE SEND POST/GET REQUEST FOR SAME FILE FIELD OTHERWISE NOT WORKING THIS VALIDATION 4
     * LIKE : $_POST['field_name'] = 'true';
     * 
     * Custom validation rule to check if the file is a valid image.
     * Note: single file or multiple file validate
     * 
     * Example : file_required[field_name]
     *
     * @return  boolean
     */
    public function file_required($val, $field)
    {
        $name = str_replace(['[', ']'], '', $field);
        if (is_array($_FILES[$name]['name'])) {
            if (!empty($_FILES[$name]['name'][0])) {
                return true;
            } else {
                return false;
            }
        } elseif (!empty($_FILES[$name]['name'])) {
            return  true;
        } else {
            return false;
        }
    }

    /**
     * IMPORTANT NOTE: PLEASE SEND POST/GET REQUEST FOR SAME FILE FIELD OTHERWISE NOT WORKING THIS VALIDATION 4
     * LIKE : $_POST['field_name'] = 'true';
     * 
     * Custom validation rule to check if the file size is within the allowed limit.
     * Note: single file or multiple file validate
     * 
     * Example : file_maxsize[field_name.2000] (maximum allowed file size in kilobytes.)
     *
     * @param   int     $field   
     * @return  boolean
     */
    public function file_maxsize($val, $field)
    {
        sscanf($field, '%[^.].%[^.]', $name, $size);
        $name = str_replace(['[', ']'], '', $name);
        if (is_array($_FILES[$name]['name'])) {
            if (!empty($_FILES[$name]['size'][0])) {
                $flag = false;
                for ($i = 0; $i < count($_FILES[$name]['size']); $i++) {
                    if (($_FILES[$name]['size'][$i] / 1024) <= $size) {
                        $flag = true;
                    } else {
                        $flag = false;
                        break;
                    }
                }
                return $flag;
            } else {
                return true;
            }
        } elseif (!empty($_FILES[$name]['name'])) {
            return ($_FILES[$name]['size'] / 1024 <= $size);
        } else {
            return true;
        }
    }

    /**
     * IMPORTANT NOTE: PLEASE SEND POST/GET REQUEST FOR SAME FILE FIELD OTHERWISE NOT WORKING THIS VALIDATION 4
     * LIKE : $_POST['field_name'] = 'true';
     * 
     * Custom validation rule to check if the file has an allowed extension.
     * Note: single file or multiple file validate
     * 
     * Example : file_extension[field_name.jpg|png|pdf]  -- Allowed file types separated by '|'.
     *
     * @param   string  $types  
     * @return  boolean
     */
    public function file_extension($val, $field)
    {
        sscanf($field, '%[^.].%[^.]', $name, $types);
        $allowed_types = explode('|', $types);
        $name = str_replace(['[', ']'], '', $name);
        if (is_array($_FILES[$name]['name'])) {
            if (!empty($_FILES[$name]['name'][0])) {
                $flag = false;
                for ($i = 0; $i < count($_FILES[$name]['name']); $i++) {
                    $ext = pathinfo($_FILES[$name]['name'][$i], PATHINFO_EXTENSION);
                    if (in_array($ext, $allowed_types)) {
                        $flag = true;
                    } else {
                        $flag = false;
                        break;
                    }
                }

                return $flag;
            } else {
                return true;
            }
        } elseif (!empty($_FILES[$name]['name'])) {
            $ext = pathinfo($_FILES[$name]['name'], PATHINFO_EXTENSION);
            return (in_array($ext, $allowed_types));
        } else {
            return true;
        }
    }

    /**
     * Validate base64Content and return decodedContent
     *
     * @param   string  $base64Content  
     * @return  boolean|string
     */
    private function is_base64($base64Content)
    {
        // Check if the string is valid Base64
        if (base64_encode(base64_decode($base64Content, true)) === $base64Content) {
            // Check if the Base64-decoded string is identical to the original string
            $decodedContent = base64_decode($base64Content, true);
            if ($decodedContent !== false && base64_encode($decodedContent) === $base64Content) {
                return $decodedContent;
            }
        }
        return false;
    }

    /**
     * Get base64Content info and validate
     *
     * @param   string  $base64Content  
     * @return  boolean|object
     */
    private function base64_info($base64Content)
    {
        try {
            $decodedContent = $this->is_base64($base64Content);
            if ($decodedContent) {
                // Get file information
                $fileInfo = finfo_open(FILEINFO_MIME_TYPE); // You can also use other options here
                $mimeType = finfo_buffer($fileInfo, $decodedContent);
                $fileSizeBytes = strlen($decodedContent);

                $extension = explode('/', $mimeType)[1];

                // Close the file info resource
                finfo_close($fileInfo);

                return (object)[
                    "type" => $mimeType,
                    "fileSize" => round($fileSizeBytes / 1024, 2),
                    "extension" => $extension,
                    "decodedContent" => $decodedContent
                ];
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Base64Content Custom validation rule to check if the file is a valid image.
     * Note: single file or multiple file validate of coma separated values
     * 
     * Example : file_required[field_name]
     *
     * @return  boolean
     */
    public function base64_file_required($val, $field)
    {
        $list = explode(",", $val);
        $flag = false;
        for ($i = 0; $i < count($list); $i++) {
            if ($this->is_base64($list[$i])) {
                $flag = true;
            } else {
                $flag = false;
                break;
            }
        }
        return $flag;
    }

    /**
     * Base64Content Custom validation rule to check if the file size is within the allowed limit.
     * Note: single file or multiple file validate of coma separated values
     * 
     * Example : base64_file_maxsize[2000] (maximum allowed file size in kilobytes.)
     *
     * @param   int     $field  as size 
     * @return  boolean
     */
    public function base64_file_maxsize($val, $field)
    {
        $list = explode(",", $val);
        $flag = true;
        $list_count = count($list);
        if ($list_count > 0) {
            for ($i = 0; $i < $list_count; $i++) {
                if ($d = $this->base64_info($list[$i])) {
                    if ($d->fileSize <= $field) {
                        $flag = true;
                    } else {
                        $flag = false;
                        break;
                    }
                } else {
                    $flag = false;
                    break;
                }
            }
        }
        return $flag;
    }

    /**
     * Base64Content Custom validation rule to check if the file has an allowed extension.
     * Note: single file or multiple file validate of coma separated values
     * 
     * Example : base64_file_extension[jpg|png|pdf]
     *
     * @param   string  $field Allowed file types separated by '|'.
     * @return  boolean
     */
    public function base64_file_extension($val, $field)
    {
        $list = explode(",", $val);
        $allowed_types = explode('|', $field);
        $flag = true;
        $list_count = count($list);
        if ($list_count > 0) {
            for ($i = 0; $i < $list_count; $i++) {
                if ($d = $this->base64_info($list[$i])) {
                    if (in_array($d->extension, $allowed_types)) {
                        $flag = true;
                    } else {
                        $flag = false;
                        break;
                    }
                } else {
                    $flag = false;
                    break;
                }
            }
        }
        return $flag;
    }



    // ------------------------------------------------------------------------
}

/* End of file MY_Form_validation.php */
/* Location: ./application/libraries/MY_Form_validation.php */