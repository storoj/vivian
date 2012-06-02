<?php
/**
 * Created by JetBrains PhpStorm.
 * User: maximum
 * Date: 18.05.12
 * Time: 16:12
 * To change this template use File | Settings | File Templates.
 */
class SessionHelper
{
    private $savePath;

    function open($savePath, $sessionName)
    {
        $this->savePath = $savePath;
        if (!is_dir($this->savePath)) {
            mkdir($this->savePath, 0777);
        }

        return true;
    }

    function close()
    {
        return true;
    }

    function read($id)
    {
        return (string)@file_get_contents("$this->savePath/sess0_$id");
    }

    function write($id, $data)
    {
        return file_put_contents("$this->savePath/sess0_$id", $data) === false ? false : true;
    }

    function destroy($id)
    {
        $file = "$this->savePath/sess0_$id";
        if (file_exists($file)) {
            unlink($file);
        }

        return true;
    }

    function gc($maxlifetime)
    {
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/qwerty.txt', 'GHJK', FILE_APPEND);
        foreach (glob("$this->savePath/sess0_*") as $file) {
            if (filemtime($file) + $maxlifetime < time() && file_exists($file)) {
                //$sessionData = unserialize(file_get_contents($file));
                //$id = $sessionData['id'];

                /*$user = new UserItem($id);
                $user->online = 0;
                $user->Save();*/

                //mysql_query("UPDATE `tc_users` SET `online` = '0' WHERE `id` = '".$id."'");

                file_put_contents($_SERVER['DOCUMENT_ROOT'].'/qwerty.txt', $file, FILE_APPEND);

                unlink($file);
            }
        }

        return true;
    }
}
