<?php
namespace App\Utils;

class Autoloader{
    /**
     * 
     */
    static function register(){
        spl_autoload_register([__CLASS__, "autoloadClasses"]);
    }

    /**
     * 
     */
    static function autoloadClasses($class){
        $classPath = str_replace("App"."\\","",$class);
        $classPath = str_replace("\\","/",$classPath). ".php";
        // echo $classPath." trouvée";
        // echo "<br>";
        if (file_exists(__DIR__."/../".$classPath)) {
            require __DIR__."/../".$classPath;
        } else {
            echo "Classe " . $classPath . " non trouvée";
        }
    }
}