<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Autocode extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('skeleton');
        $this->load->model(['user_project_model']);
    }

    public function generate_sql($projectId)
    {
        try {
            $u = $this->http->auth('post', 'ADMIN');
            $project_data = $this->user_project_model->get($projectId, $u->id);

            if (!$project_data) {
                return $this->http->response->create(203, "Project not found");
            }

            $json_table_arr = json_decode(file_get_contents(FCPATH . "documents/upload/user_{$u->id}/json_file/" . $project_data->json_file), true);


            // FILE WILL CREATE HERE
            $generate_sql_path = "auto_generate/user_{$u->id}/project_{$projectId}/sql/";
            if (is_dir(FCPATH . $generate_sql_path)) {
                delete_files(FCPATH . $generate_sql_path, true);
            }
            
            $dateTimeStr = date('M d, Y') . " at " . date("h:i:s A");

            //  * * * SQL HEADER * * * * 

            $sqlText = <<< SQL_HEADER
            -- phpMyAdmin SQL Dump
            -- version 5.2.1
            -- https://www.phpmyadmin.net/
            --
            -- Host: 127.0.0.1
            -- Generation Time: $dateTimeStr
            -- Server version: 10.4.28-MariaDB
            -- PHP Version: 8.0.28

            SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
            START TRANSACTION;
            SET time_zone = "+00:00";


            /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
            /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
            /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
            /*!40101 SET NAMES utf8mb4 */;

            --
            -- Database: `{$json_table_arr['database']}`
            --

            SQL_HEADER;


            // * * * SQL BODY * * * 

            foreach ($json_table_arr['data'] as $table_dtls) {

                // for EACH TABLE * * * 

                $tableName = $table_dtls['table_name'];

                $sqlText .= <<<TABLE_DEFINATION
                -- --------------------------------------------------------
                --
                -- Table structure for table `$tableName`
                --

                TABLE_DEFINATION;

                $sqlText .= "CREATE TABLE IF NOT EXISTS `$tableName` (\n";

                // ADD FIELD DEFINATION
                $sqlText .= rtrim(array_reduce($table_dtls['fields'], function ($carry, $field_dtls) use ($table_dtls) {
                    $field_datatype = strtoupper($field_dtls['data_type']);


                    switch ($field_datatype) {
                        case 'DATETIME':
                            $field_length_str = '';
                            break;
                        case 'INT':
                            $field_length_str = '(11)';
                            break;

                        default:
                            $field_length_str = "({$field_dtls['data_size']})";
                            break;
                    }

                    $default = $field_dtls['is_null'] == true ? "NULL" : "NOT NULL";
                    $field_auto_inc = "";
                    if (isset($field_dtls['auto_increment']) && $field_dtls['auto_increment']) $field_auto_inc = "AUTO_INCREMENT";

                    $carry .= "`{$field_dtls['field_name']}` {$field_datatype}{$field_length_str} $default $field_auto_inc,\n";
                    if ($field_dtls['is_primary']) $carry .= "PRIMARY KEY (`{$field_dtls['field_name']}`),\n";

                    return $carry;
                }), "\n,");

                // * *  CLOSE BACKET HERE * * 
                $sqlText .= "\n);\n";
            }


            //  * * * *  SET SQL FOOTER * * * 
            $sqlText .= <<<SQL_FOOTER
                
            /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
            /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
            /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

            SQL_FOOTER;

            $this->mfile->make_dir(FCPATH . $generate_sql_path);
            if (file_put_contents(FCPATH . "{$generate_sql_path}{$json_table_arr['database']}.sql", $sqlText, FILE_APPEND | LOCK_EX)) {
                return $this->http->response->create(200, "Sql query created successfully");
            } else {
                return $this->http->response->create(203, "Sql query created failed");
            }
        } catch (\Throwable $th) {
            return $this->http->response->serverError($th->getMessage());
        }
    }

    public function generate_postman_collection($projectId)
    {
        try {
            $u = $this->http->auth('post', 'ADMIN');
            $project_data = $this->user_project_model->get($projectId, $u->id);

            if (!$project_data) {
                return $this->http->response->create(203, "Project not found");
            }

            $json_table_arr = json_decode(file_get_contents(FCPATH . "documents/upload/user_{$u->id}/json_file/" . $project_data->json_file), true);

            $generate_postman_path = "auto_generate/user_{$u->id}/project_{$projectId}/postman_collection/";
            if (is_dir(FCPATH . $generate_postman_path)) {
                delete_files(FCPATH . $generate_postman_path, true);
            }

            $item_template = file_get_contents(FCPATH . "codebase_skeleton/postman_collection/item.txt");
            $parent_template = file_get_contents(FCPATH . "codebase_skeleton/postman_collection/parent.txt");
            $dynamic_data["ALL_VARIABLE"] = [
                [
                    'key' => 'api-key',
                    'value' => '',
                    'type' => 'string',
                ],
                [
                    'key' => 'site-url',
                    'value' => 'https://autocode.websofttechs.com/backend/',
                    'type' => 'string',
                ]
            ];

            $dynamic_data['PROJECT_NAME'] = $json_table_arr['database'];
            // create item and variable
            $dynamic_data["ALL_ITEM"] = rtrim(array_reduce($json_table_arr['data'], function ($carry, $tbl_dtls) use ($item_template, &$dynamic_data) {
                $dynamic_data["ALL_VARIABLE"][] = [
                    'key' => $tbl_dtls['table_name'] . "-id",
                    'value' => '',
                    'type' => 'string',
                ];
                // replace item template
                $replace_arr = [
                    'TABLE_NAME' => $tbl_dtls['table_name'],
                    'PAYLOAD' => make_json_str_payload($tbl_dtls['fields'])
                ];
                foreach ($replace_arr as $key => $replace) {
                    $item_template = str_replace("[$key]", $replace, $item_template);
                }
                $carry .= $item_template . ",\n";
                return $carry;
            }, "[\n"), "\n,") . ",\n" . file_get_contents(FCPATH . "codebase_skeleton/postman_collection/login.txt") . "]\n";

            foreach ($dynamic_data as $key => $value) {
                if ($key == 'ALL_VARIABLE') $value = json_encode($value);
                $parent_template = str_replace("[$key]", $value, $parent_template);
            }

            $this->mfile->make_dir(FCPATH . $generate_postman_path);
            if (file_put_contents(FCPATH . "{$generate_postman_path}{$project_data->project_name}.postman_collection.json", $parent_template)) {
                return $this->http->response->create(200, "Postman Collection created successfully");
            } else {
                return $this->http->response->create(203, "Postman Collection created failed");
            }
        } catch (\Throwable $th) {
            return $this->http->response->serverError($th->getMessage());
        }
    }

    public function generate_backend($projectId)
    {
        try {
            $u = $this->http->auth('post', 'ADMIN');
            $project_data = $this->user_project_model->get($projectId, $u->id);

            if (!$project_data) {
                return $this->http->response->create(203, "Project not found");
            }

            $json_table_arr = json_decode(file_get_contents(FCPATH . "documents/upload/user_{$u->id}/json_file/" . $project_data->json_file), true);

            $generate_codebase_path = "auto_generate/user_{$u->id}/project_{$projectId}/backend/";

            if (is_dir(FCPATH . $generate_codebase_path)) {
                delete_files(FCPATH . $generate_codebase_path, true);
            }

            // * * * * * * *  generatea CONSTANT skeleton * * * * *

            fullCopy(FCPATH . 'codebase_skeleton/backend/php/codeignitor/constant_codebase/', FCPATH . $generate_codebase_path);

            // set BASE_URL in codeigniter application
            $configPageStr = file_get_contents(FCPATH . "codebase_skeleton/backend/php/codeignitor/constant_codebase/application/config/config.php");
            $configPageStr = str_replace('[BASE_URL]', "http://localhost/autocode/{$generate_codebase_path}", $configPageStr);
            file_put_contents(FCPATH . $generate_codebase_path . "application/config/config.php", $configPageStr);

            $constPageStr = file_get_contents(FCPATH . "codebase_skeleton/backend/php/codeignitor/constant_codebase/application/config/constants.php");
            $replaceKey  = [
                'HF_TITLE' => $project_data->project_name,
                'LOGO_PATH' => $project_data->project_name
            ];
            foreach ($replaceKey as $key => $value) {
                $constPageStr = str_replace("[{$key}]", $value, $constPageStr);
            }
            file_put_contents(FCPATH . $generate_codebase_path . "application/config/constants.php", $constPageStr);

            // * * * * * * create CONTROLLERS and ROUTES and Model dynamically * * * * * * 

            $routs_string = '';

            $this->load->library('autocode/backend/ci');
            foreach ($json_table_arr['data'] as $key => $tbl_dtls) {
                if ($this->ci->init($tbl_dtls)) {

                    // create routes string
                    $routs_string .= $this->ci->get_routes();

                    // create Controller
                    $template = file_get_contents(FCPATH . 'codebase_skeleton/backend/php/codeignitor/dynamic_codebase/controller.txt');
                    $destination = FCPATH . $generate_codebase_path . "application/controllers/";
                    if (!$this->ci->createController($template, $destination)) {
                        return $this->http->response->create(203, $this->ci->get_errors(true, "<br>"));
                    }

                    // create Model
                    $template = file_get_contents(FCPATH . 'codebase_skeleton/backend/php/codeignitor/dynamic_codebase/model.txt');
                    $destination = FCPATH . $generate_codebase_path . "application/models/";
                    if (!$this->ci->createModel($template, $destination)) {
                        return $this->http->response->create(203, $this->ci->get_errors(true, "<br>"));
                    }
                } else {
                    return $this->http->response->create(203, $this->ci->get_errors(true, "<br>"));
                }
            }

            // create ROUTES here 
            $routingPageStr = file_get_contents(FCPATH . $generate_codebase_path . "application/config/routes.php");
            $routingPageStr = str_replace('[API_ROUTES]', $routs_string, $routingPageStr);
            file_put_contents(FCPATH . $generate_codebase_path . "application/config/routes.php", $routingPageStr);
            return $this->http->response->create(200, "Back-end created successfully");
        } catch (\Throwable $th) {
            pp($th);
            return $this->http->response->serverError($th->getMessage());
        }
    }

    public function generate_frontend($projectId)
    {
        try {
            $u = $this->http->auth('post', 'ADMIN');
            // return $this->http->response->create(200, "Front-end generate successfully");
            $project_data = $this->user_project_model->get($projectId, $u->id);

            if (!$project_data) {
                return $this->http->response->create(203, "Project not found");
            }

            $json_table_arr = json_decode(file_get_contents(FCPATH . "documents/upload/user_{$u->id}/json_file/{$project_data->json_file}"), true);



            switch (strtolower($project_data->frontend_framework)) {
                case 'angular':
                    $skeletonPath = "codebase_skeleton/front-end/angular/";
                    $generate_codebase_path = "auto_generate/user_{$u->id}/project_{$projectId}/front-end/";

                    if (is_dir(FCPATH . $generate_codebase_path)) {
                        delete_files(FCPATH . $generate_codebase_path, true);
                    }

                    // * * * * * * *  generatea CONSTANT skeleton * * * * *
                    fullCopy(FCPATH . "{$skeletonPath}constant_codebase/", FCPATH . $generate_codebase_path);

                    // * * * * * * *  generatea DYNAMIC skeleton * * * * *
                    $this->load->library('autocode/frontend/angular');

                    foreach ($json_table_arr['data'] as $key => $tbl_dtls) {
                        if ($this->angular->init($tbl_dtls)) {
                            // create CRUD MODULE
                            $this->angular->createCrudModule(FCPATH . $skeletonPath . "dynamic_codebase/pages/", FCPATH . $generate_codebase_path . "src/app/dashboard/pages/");
                        } else {
                            return $this->http->response->create(203, $this->angular->get_errors(true, "<br>"));
                        }
                    }

                    // create REQUEST MAPPER ROUTES
                    $requesMapperString = file_get_contents(FCPATH . $skeletonPath . "dynamic_codebase/request-mapper.txt");
                    $backendBaseUrl = path("http://localhost/autocode/auto_generate/user_{$u->id}/project_{$projectId}/backend/api/");
                    $replaceArr = [
                        'BASE_API_URL' => $backendBaseUrl,
                        'PRODUCT_BASE_API_URL' => $backendBaseUrl,
                        'TABLE_API' => $this->angular->getRequestMapperApis()
                    ];
                    foreach ($replaceArr as $key => $value) {
                        $requesMapperString  = str_replace("[$key]", $value, $requesMapperString);
                    }
                    file_put_contents(FCPATH . $generate_codebase_path . "src/app/request-mapper.ts", $requesMapperString);

                    // create DASHBOARD ROUTING 
                    $dashboardRoutingStr = file_get_contents(FCPATH . $skeletonPath . "dynamic_codebase/dashboard-routing.module.txt");
                    $dashboardRoutingStr = str_replace('[DASHBOARD_ROUTING]', $this->angular->getDashboardRouting(), $dashboardRoutingStr);
                    file_put_contents(FCPATH . $generate_codebase_path . "src/app/dashboard/dashboard-routing.module.ts", $dashboardRoutingStr);

                    // create NAVITEMS
                    $sidebarHtmlStr = file_get_contents(FCPATH . $skeletonPath . "dynamic_codebase/sideber.component.html");
                    $sidebarHtmlStr = str_replace('[NAV_ITEM]', $this->angular->getNavItems(), $sidebarHtmlStr);
                    file_put_contents(FCPATH . $generate_codebase_path . "src/app/dashboard/include/sideber/sideber.component.html", $sidebarHtmlStr);
                    // * * * * * * *  generatea DYNAMIC skeleton END * * * * *
                    return $this->http->response->create(200, "Front-end generate successfully");



                case 'codeigniter':
                    $skeletonPath = "codebase_skeleton/front-end/codeigniter/";
                    $generate_codebase_path = "auto_generate/user_{$u->id}/project_{$projectId}/backend/application/";

                    // * * * * * * *  generatea DYNAMIC skeleton * * * * *
                    $this->load->library('autocode/frontend/codeigniter');

                    foreach ($json_table_arr['data'] as $key => $tbl_dtls) {
                        // load table 
                        if ($this->codeigniter->init($tbl_dtls)) {
                            $indexPage = file_get_contents(FCPATH . $skeletonPath . "index.php");

                            $replaceArr = [
                                'DISPLAY_TABLE_NAME' => $this->codeigniter->getFolderName(true),
                                'TABLE_NAME' => $this->codeigniter->tbl_name,
                                'AUTO_FIELD_NAME' => $this->codeigniter->auto_field,
                                'DATATABLE_HEADER' => $this->codeigniter->getDataTableHeader(),
                                'FORM_GROUP_ELEMENT_INSERT' => $this->codeigniter->getFormGroupElement('INSERT'),
                                'FORM_GROUP_ELEMENT_UPDATE' => $this->codeigniter->getFormGroupElement('UPDATE'),
                                'DATASRC_FIELD' => $this->codeigniter->getDataSrcField(),
                                'BASE64_HANDLER_INSERT' => $this->codeigniter->base64Handler('INSERT'),
                                'BASE64_HANDLER_UPDATE' => $this->codeigniter->base64Handler('UPDATE'),
                                'INSERT_VALIDATION' => $this->codeigniter->field(true, 'insert'),
                                'UPDATE_VALIDATION' => $this->codeigniter->field(true, 'update'),
                                'SET_FIELD_VALUE' => $this->codeigniter->field()
                            ];
                            foreach ($replaceArr as $key => $value) {
                                $indexPage = str_replace("[$key]", $value, $indexPage);
                            }

                            // creates files
                            $addi_path = "views/" . strtolower($this->codeigniter->tbl_name);
                            if (!is_dir($generate_codebase_path . $addi_path)) {
                                mkdir($generate_codebase_path . $addi_path, 777, true);
                            }
                            file_put_contents(FCPATH . path($generate_codebase_path . $addi_path) . "index.php", $indexPage);
                        } else {
                            return $this->http->response->create(203, $this->codeigniter->get_errors(true, "<br>"));
                        }
                    }

                    // writes routes
                    file_put_contents(FCPATH . $generate_codebase_path . "config/routes.php", $this->codeigniter->routes, FILE_APPEND | LOCK_EX);

                    // sidebar's navelement set up
                    $headerPagePath = FCPATH . $generate_codebase_path . "views/layout/header.php";
                    $headerPageStr = file_get_contents($headerPagePath);
                    $headerPageStr = str_replace('[NAV_ELEMENT]', $this->codeigniter->navElement, $headerPageStr);
                    file_put_contents($headerPagePath, $headerPageStr);

                    return $this->http->response->create(200, "Front-end generate successfully");
            }
        } catch (\Throwable $th) {
            pp($th);
            return $this->http->response->serverError($th->getMessage());
        }
    }
}
