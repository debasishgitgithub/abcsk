<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 *
 * Libraries Mfile
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


class Mfile
{

    private $response = [];

    public function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->library('upload');
    }

    private function destroy($field = null)
    {
        try {
            if (!empty($field)) {
                unset($_FILES[$field]);
            } else {
                $_FILES = [];
            }
        } catch (\Throwable $th) {
            $_FILES = [];
        }
    }

    public function upload($field_name, $config, $bool_return = false)
    {
        try {
            $this->ci->upload->initialize($config);

            if (isset($config["upload_path"])) {
                $this->make_dir($config["upload_path"]);
            }

            if (isset($_FILES[$field_name]['name']) && is_array($_FILES[$field_name]['name'])) {
                // Multiple file upload
                $resp = $this->uploadMultiple($field_name);
                if ($bool_return) {
                    return $resp;
                }
                return $this->response;
            } else if (isset($_FILES[$field_name]['name']) && !empty(isset($_FILES[$field_name]['name']))) {
                // Single file upload
                $resp = $this->uploadSingle($field_name);
                if ($bool_return) {
                    return $resp;
                }
                return $this->response;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }

    private function make_dir($path)
    {
        if (!is_dir($path)) {
            mkdir($path, 777, true);
            fopen(rtrim($path, '/') . '/' . 'index.html', 'w');
        }
    }

    private function uploadMultiple($field_name)
    {
        try {
            $files = $_FILES[$field_name];
            if (!empty($files['name'][0])) {
                $file_count = count($files['name']);
                for ($i = 0; $i < $file_count; $i++) {
                    $eachFile = "{$field_name}_{$i}";
                    $_FILES[$eachFile]['name'] = $files['name'][$i];
                    $_FILES[$eachFile]['type'] = $files['type'][$i];
                    $_FILES[$eachFile]['tmp_name'] = $files['tmp_name'][$i];
                    $_FILES[$eachFile]['error'] = $files['error'][$i];
                    $_FILES[$eachFile]['size'] = $files['size'][$i];

                    $this->uploadSingle($eachFile);
                    $this->destroy($eachFile);
                }

                if ($this->success_resp()) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }

    private function uploadSingle($field_name)
    {
        try {
            if ($this->ci->upload->do_upload($field_name)) {
                $this->response[] = $this->ci->upload->data();
                return true;
            } else {
                $this->response[] = array('error' => $this->ci->upload->display_errors());
                return false;
            }
        } catch (\Throwable $th) {
            $this->response[] = array('error' => "Server internal error");
            return false;
        }
    }

    public function file_names($conv_to_str = false, $sep_by = ",")
    {
        $fnames = [];
        if ($this->response) {
            for ($i = 0; $i < count($this->response); $i++) {
                $d = $this->response[$i];
                if (isset($d["file_name"])) {
                    $fnames[] = $d["file_name"];
                }
            }
        }
        if ($conv_to_str) {
            return implode($sep_by, $fnames);
        } else {
            return $fnames;
        }
    }

    public function unlink_files()
    {
        $resp = 0;
        if ($this->response) {
            for ($i = 0; $i < count($this->response); $i++) {
                $d = $this->response[$i];
                if (isset($d["file_name"]) && file_exists($d["full_path"])) {
                    if (is_file($d["full_path"])) {
                        if (unlink($d["full_path"])) {
                            $resp++;
                        }
                    }
                }
            }
        }
        return $resp;
    }

    public function success_resp()
    {
        $resp = [];
        if ($this->response) {
            for ($i = 0; $i < count($this->response); $i++) {
                $d = $this->response[$i];
                if (isset($d["file_name"])) {
                    $resp[] = $d;
                }
            }
        }
        return $resp;
    }

    public function error_resp()
    {
        $resp = [];
        if ($this->response) {
            for ($i = 0; $i < count($this->response); $i++) {
                $d = $this->response[$i];
                if (isset($d["error"])) {
                    $resp[] = $d;
                }
            }
        }
        return $resp;
    }

    // base64 section

    public function upload_base64($uploadDir, $base64Content)
    {
        try {
            $this->make_dir($uploadDir);
            if (is_dir($uploadDir)) {
                if (is_array($base64Content)) {
                    foreach ($base64Content as $bs) {
                        $this->single_upload_base64($uploadDir, $bs);
                    }
                } else {
                    $this->single_upload_base64($uploadDir, $base64Content);
                }
                return $this->response ?? false;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }

    private function single_upload_base64($uploadDir, $base64Content)
    {
        try {
            if ($info = $this->base64_info($base64Content)) {
                // Decode the base64 content
                $decodedContent = $info->decodedContent;

                // Generate a unique filename
                $filename = uniqid() . time() . ".{$info->extension}";

                // Specify the full path to the target file
                $uploadPath = "{$uploadDir}/{$filename}";

                // Save the decoded content to the file
                file_put_contents($uploadPath, $decodedContent);
                if (is_file($uploadPath)) {
                    $this->response[] = [
                        "file_name" => $filename,
                        "full_path" => $uploadPath,
                        "type" => $info->type,
                        "extension" => $info->extension,
                        "fileSize" => $info->fileSize,
                    ];
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function base64_info($base64Content)
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
}

/* End of file Mfile.php */
/* Location: ./application/libraries/Mfile.php */
