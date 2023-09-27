<?php
 namespace Libraries;

 class Check
 {
     private $string;
     
     public static function checarNome($string)
     {
        if(preg_match('',$string)){
            return true;
        }else{
            return false;
        }
     }
     public static function checarEmail($string)
     {
        if(filter_var($string,FILTER_VALIDATE_EMAIL)){
            return true;
        }else{
            return false;
        }
     }
     public static function checarString($string)
     {
        $string = strip_tags(trim($string));
        $string = htmlentities($string);
        return $string;
     }
     public function codificarSenha($string)
    {
        $codificada  = password_hash($string, PASSWORD_DEFAULT);
        return $codificada;
    }
     public function getString()
     {
          return $this->string;
     }
     public function setString($string)
     {
          $this->string = $string;

          return $this;
     }
    
 }