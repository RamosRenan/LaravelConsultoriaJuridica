<?php

namespace App\Http\Controllers\Validate;
use App\Http\Controllers\Controller;

class pregmatch extends Controller
{
    static function bPregmatch (string $text){
 
        $stringSplit=preg_split('/\s/', $text);

        if(count($stringSplit)>0){
            preg_match('/[@%+=&|%]|update|delete|select|like|where=/i',  $text, $match);
            if(count($match)>0){
                return false;
            }else{
                return true;
            }
        }else{
            $tot=preg_grep('/update|delete|select|like|where=|[+-_*&%]/i', $stringSplit);
            if(count($tot)>0){
                return false;
             }else{
                return true;
            }
        }//else
    }//bPregmatch()
}//pregmatch
