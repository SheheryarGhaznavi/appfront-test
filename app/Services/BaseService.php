<?php

namespace App\Service;

use Error;
use Exception;
use Illuminate\Support\Facades\Log;


class BaseService
{
    /**
     * You call other function through this function.
     * it handles errors
     * required 2 parameters
     * parameter 1 : function_name (name of the function that you want to call)
     * parameter 2 : parameter (all parameters in array format that you want to pass to that function)
     * 
     * return array with minimum two index
     * index 1 : error (value : 0 means no error, 1 means error)
     * index 2 : message (value : text its value varies)
     *
     * @return array
     */

    public function callFunction(string $function_name, array $parameters, $call_from_controller = true)
    {
        try {
            return $this->$function_name($parameters);

        } catch (Exception $e) {
            
            $error = 1;
            $message = $e->getMessage();
        
        } catch (Error $e) { 
            
            $error = 1;
            $message = $e->getMessage();
        }

        throw_if($error && !$call_from_controller, $message);
        return compact('error', 'message');
    }
}
