<?php
class util
{

    private $data;

    public function setData($data)
    {
        $this->data = $data;
    }

    public function obtenerNodosPadres()
    {

        $padres = array();

        foreach ($this->data as $padre) {
            if ($padre->idSegMenuPadre == 0) {
                $padres[] = $padre;
            }
        }
        return  $padres;
    }



    public function obtenerNodosHijos($idPadre)
    {

        $hijos = array();

        foreach ($this->data as $hijo) {
            if ($hijo->idSegMenuPadre == $idPadre) {

                $hijos[] = $hijo;
            }
        }
        return $hijos;
    }


    function obtenerNodo($elements, $id)
    {

        foreach ($elements as $element) {

            if ($element->idSegMenu == $id) {
                return $element;
            }
        }
        return null;
    }


    function obtenerBreakCrumb($elements, $id)
    {

        $salida = array();
        $salida = $this->recursiveBreakCrumb($elements, $id);
        return $salida;
    }

    function recursiveBreakCrumb($elements, $id)
    {

        $branch = array();
        $node = $this->obtenerNodo($elements, $id);
        if ($node) {
            $branch = $this->recursiveBreakCrumb($elements, $node->idSegMenuPadre);
            array_push($branch, $node);
        }
        return $branch;
    }



    public function crearNodoData($nodo)
    {

        $nodoData = array(
            "label" =>  $nodo->titulo,
            "expandedIcon" => is_null($nodo->expandedIcon) ? 'Sin asignar' : 'fa ' . $nodo->expandedIcon,
            "collapsedIcon" => is_null($nodo->collapsedIcon) ? 'Sin asignar' : 'fa ' . $nodo->collapsedIcon,
            "routeLink" => $nodo->routeLink,
            "nivel" => $nodo->nivel,
            "idSegMenu" => $nodo->idSegMenu,
            "idSegMenuPadre" => $nodo->idSegMenuPadre,
            "ordenVisualizacion" => $nodo->ordenVisualizacion
        );
        return $nodoData;
    }





    public function obtenerPadres()
    {

        $padres = array();

        foreach ($this->data as $row) {
            if ($row->idSegMenuPadre == 0) {

                $padres[] = array(
                    'idSegMenu' => $row->idSegMenu,
                    "idSegMenuPadre" =>  $row->idSegMenuPadre,
                    "titulo" => $row->titulo,
                    "routeLink" => $row->routeLink,
                    "nivel" => $row->nivel,
                    "ordenVisualizacion" => $row->ordenVisualizacion,
                    "expandedIcon" => $row->expandedIcon,
                    "collapsedIcon" => $row->collapsedIcon
                );
            }
        }
        return  $padres;
    }

    public function obtenerHijos($padre)
    {

        $hijos = array();

        foreach ($this->data as $row) {
            if ($padre == $row->idSegMenuPadre) {

                $hijos[] = array(
                    'idSegMenu' => $row->idSegMenu,
                    "idSegMenuPadre" =>  $row->idSegMenuPadre,
                    "titulo" => $row->titulo,
                    "routeLink" => $row->routeLink,
                    "nivel" => $row->nivel,
                    "ordenVisualizacion" => $row->ordenVisualizacion,
                    "expandedIcon" => $row->expandedIcon,
                    "collapsedIcon" => $row->collapsedIcon
                );
            }
        }
        return $hijos;
    }

    public function generarMenu($tipo)
    {
        $arbol = array();
        $padre = array();

        $padres = $this->obtenerPadres();

        foreach ($padres as $value) {

            $data['label'] = $value['titulo'];
            $data['expandedIcon'] = is_null($value['expandedIcon']) ? 'Sin asignar' : $value['expandedIcon'];
            $data['collapsedIcon'] = is_null($value['collapsedIcon']) ? 'Sin asignar' : $value['collapsedIcon'];
            $data['routeLink'] = $value['routeLink'];
            $data['nivel'] = $value['nivel'];
            $data['idSegMenu'] = $value['idSegMenu'];
            $data['idSegMenuPadre'] = $value['idSegMenuPadre'];
            $data['ordenVisualizacion'] = $value['ordenVisualizacion'];

            if ($tipo == 0)
                $arbol[] = array(
                    "label" =>  $value['titulo'],
                    "expandedIcon" => is_null($value['expandedIcon']) ? 'Sin asignar' : 'fa ' . $value['expandedIcon'],
                    "collapsedIcon" => is_null($value['collapsedIcon']) ? 'Sin asignar' : 'fa ' . $value['collapsedIcon'],
                    "routeLink" => $value['routeLink'],
                    "nivel" => $value['nivel'],
                    "idSegMenu" => $value['idSegMenu'],
                    "idSegMenuPadre" => $value['idSegMenuPadre'],
                    "ordenVisualizacion" => $value['ordenVisualizacion'],
                    "children" => $this->generarMenuItems($value['idSegMenu'], $tipo)
                );
            else
                $arbol[] = array('data' => $data, 'children' => $this->generarMenuItems($value['idSegMenu'], $tipo));
        }

        return $arbol;
    }

    public function generarMenuItems($item, $tipo)
    {
        $arbol = array();
        $hijo = array();

        //$data = array();

        $hijos = $this->obtenerHijos($item);


        foreach ($hijos as $value) {

            $data['label'] = $value['titulo'];
            $data['expandedIcon'] = is_null($value['expandedIcon']) ? 'Sin asignar' : $value['expandedIcon'];
            $data['collapsedIcon'] = is_null($value['collapsedIcon']) ? 'Sin asignar' : $value['collapsedIcon'];
            $data['routeLink'] = $value['routeLink'];
            $data['nivel'] = $value['nivel'];
            $data['idSegMenu'] = $value['idSegMenu'];
            $data['idSegMenuPadre'] = $value['idSegMenuPadre'];
            $data['ordenVisualizacion'] = $value['ordenVisualizacion'];

            if ($tipo == 0)
                $arbol[] = array(
                    "label" =>  $value['titulo'],
                    "expandedIcon" => is_null($value['expandedIcon']) ? 'Sin asignar' : 'fa ' . $value['expandedIcon'],
                    "collapsedIcon" => is_null($value['collapsedIcon']) ? 'Sin asignar' : 'fa ' . $value['collapsedIcon'],
                    "routeLink" => $value['routeLink'],
                    "nivel" => $value['nivel'],
                    "idSegMenu" => $value['idSegMenu'],
                    "idSegMenuPadre" => $value['idSegMenuPadre'],
                    "ordenVisualizacion" => $value['ordenVisualizacion'],
                    "children" => $this->generarMenuItems($value['idSegMenu'], $tipo)
                );
            else
                $arbol[] = array('data' => $data, 'children' => $this->generarMenuItems($value['idSegMenu'], $tipo));
        }
        return $arbol;
    }

    function callAPI($method, $url, $data, $headers)
    {
        $curl = curl_init();
        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }
        
        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        if (!$headers) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'APIKEY: 111111111111111111111',
                'Content-Type: application/json',
            ));
        } else {
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'APIKEY: 111111111111111111111',
                'Content-Type: application/json',
                $headers
            ));
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        // EXECUTE:
        $result = curl_exec($curl);
        if (!$result) {
            die("Connection Failure");
        }
        curl_close($curl);
        return $result;
    }
}
