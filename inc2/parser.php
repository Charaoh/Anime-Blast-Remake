<?php

/*
 * Project Name: KaiBB - http://www.kaibb.co.uk
 * Author: Christopher Shaw
 * This file belongs to KaiBB, it may be freely modified but this notice, and all copyright marks must be left
 * intact. See COPYING.txt
 */
class style
{

    private $files = array(
        "CSS" => array(
            "/tpl/default/css/fontawesome/css/fontawesome.css" => false,
            "/tpl/default/css/fontawesome/css/brands.css" => false,
            "/tpl/default/css/fontawesome/css/solid.css" => false,
        ),
        "JAVA" => array(
            "/tpl/default/css/fontawesome/js/all.js" => false
        )
    );


    function open($tpl)
    {
        global $root, $template, $account, $system;

        if (file_exists("$root/tpl/$template/$tpl")) {

            $t = file_get_contents("$root/tpl/$template/$tpl");
        } else {

            $t = file_get_contents("$root/tpl/" . $system->data('template') . "/$tpl");
        }
        return $t;
    }


    function getcode($tag, $string)
    {
        $begin = '<!-- BEGIN ' . $tag . ' -->';
        $end = '<!-- END ' . $tag . ' -->';
        $pos1 = stripos($string, $begin);
        $pos2 = stripos($string, $end);
        $count = strlen($string);
        $count = $count - $pos2;
        $content = substr($string, $pos1, -$count);
        $content = $content . $end;
        return $content;
    }


    function tags($Temp, $ParseTags)
    {
        global $template, $siteaddress;
        $globaltags = array("URL" => $siteaddress, "TPL" => $template);
        $Parse = array_merge((array)$ParseTags, (array)$globaltags);
        $T = $Temp;
        foreach ($Parse as $UnParsed => $Parsed) {
            $T = str_replace("{" . $UnParsed . "}", $Parsed, $T);
        }

        return $T;
    }


    function close()
    {
        $t = '';
        $content = '';
        $T = '';
    }

    function __get($name)
    {

        switch ($name) {
            case 'output':
                return $this->output;
                break;
            case 'title':
                return $this->title;
                break;
            case 'metas':
                return $this->metas;
                break;
            case 'files':
                return $this->files;
                break;
            default:
                return 'undefined';
                break;
        }
    }

    function __set($name, $value)
    {

        switch ($name) {
            case 'output':
                $this->output = $value;
                break;
            case 'title':
                $this->title = $value;
                break;
            case 'metas':
                $this->metas = $value;
                break;
            case 'files':
                $this->files = $value;
                break;
            default:
                return 'undefined';
                break;
        }
    }

    function __add($name, $type = '', $key = '', $value)
    {
        global $root, $template, $system, $siteaddress;
        switch ($name) {
            case 'output':
                $this->output .= $value;
                break;
            case 'title':
                $this->title .= $value;
                break;
            case 'metas':
                if (is_array($value)) {
                    $this->metas = array_merge($metas, $value);
                } else {
                    if (empty($key)) {
                        $key = 'undefined';
                    }
                    $this->metas[$key] = $value;
                }
                break;
            case 'files':
                if (!empty($type)) {
                    switch ($type) {
                        case 'CSS':
                            if (!file_exists($root . "/tpl/" . $template . $value))
                                $value = "/tpl/" . $system->data('template') . $value;
                            else
                                $value = "/tpl/" . $template . $value;
                            $file = $root . $value;
                            $lastModified = rand(0, 999);
                            if (is_readable($file))
                                $lastModified = filemtime($root . $value);
                            $value = $siteaddress . $value . "?v=" . $lastModified;
                            $this->files['CSS'][$value] = false;
                            break;
                        case 'JAVA':
                            if (!file_exists($root . "/tpl/" . $template . $value))
                                $value = "/tpl/" . $system->data('template') . $value;
                            else
                                $value = "/tpl/" . $template . $value;
                            $file = $root . $value;
                            $lastModified = rand(0, 999);
                            if (is_readable($file))
                                $lastModified = filemtime($root . $value);
                            $value = $siteaddress . $value . "?v=" . $lastModified;
                            $this->files['JAVA'][$value] = $key;
                            break;
                        default:
                            return 'undefined type';
                            break;
                    }
                }
                break;
            default:
                return 'undefined';
                break;
        }
    }
}