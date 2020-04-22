

<?php
// This is the middleware
// It will add the Access-Control-Allow-Methods header to every request
//https://www.slimframework.com/docs/v3/cookbook/enable-cors.html



$app->add(function ($request, $response, $next) {
    $route = $request->getAttribute("route");

    $methods = [];

    if (!empty($route)) {
        $pattern = $route->getPattern();

        foreach ($this->router->getRoutes() as $route) {
            if ($pattern === $route->getPattern()) {
                $methods = array_merge_recursive($methods, $route->getMethods());
            }
        }
        //Methods holds all of the HTTP Verbs that a particular route handles.
    } else {
        $methods[] = $request->getMethod();
    }

    /*  if ($request->getAttribute('auth-token') == "") {
        $response->getBody()->write("No autorizado!");
    } */

    //$request = $request->withAttribute('foo'," {idToken: 'algo', token:'123456'}");

    //Ejecutar la llamada a el consumo del API
    /*  $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "https://jsonplaceholder.typicode.com/posts");
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charset=UTF-8',
     ));
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_decode("{
        title: 'foo',
        body: 'bar',
        userId: 1
      }"));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    $response->getBody()->write($result);
    curl_close($curl);  */
    //******************** 

    //realizar  el consumo de la API pasandole los datos 


    $utiles = new util();
    $result = new stdClass();
    //$result = ($utiles->callAPI('GET', 'https://jsonplaceholder.typicode.com/posts/1', false, false));
    //print_r($request->getAttribute('auth-token'));
    //$result = $utiles->callAPI('GET', 'http://localhost:3000/api/auth/verify', false, $request->getAttribute('auth-token'));
    //$result = ($utiles->callAPI('POST', 'http://localhost:3000/api/solped', false, false));
    //print_r($result);
  /*   if (!$result || property_exists($result, "err")) {
        $response->getBody()->write("No autorizado");
        return $response; 
    } */



    $response = $next($request, $response);
    return $response->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        //->withHeader("Access-Control-Allow-Methods", implode(",", $methods));
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    /* ->withHeader('x-access-token', '123456'); */
});
