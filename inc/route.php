<?php
/*
* Route Created By [ Mohammad abd almoneam ]
*/
class Route 
{

    function addroute($name, $path)
    {
        
        $routes = [$name => $path];
        return $routes;
    }

    function startrouting($route, $requesturl)
    {

        $rs         = null;
        $requesturl = explode('/' , $requesturl )[2];     
        foreach ($route as $key => $value) 
        {
            if ($key == $requesturl) 
            {
                $rs = ['key'=>$key,'path' => $value];
                return $rs;
            } 
        }return $rs;
       
    }

}
?>