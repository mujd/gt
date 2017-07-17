<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Conocimiento;
use AppBundle\Entity\ConocimientoAprendizaje;
use AppBundle\Entity\Aprendizaje;

class ConocimientoController extends FOSRestController {

    /**
     * @Rest\Get("/conocimiento")
     */
    public function listarAction() {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:Conocimiento')->findAll();
        if ($restresult === null) {
            return new View("no hay nada", Response::HTTP_NOT_FOUND);
        }
        return $restresult;
    }

    /**
     * @Rest\Get("/conocimiento/{id}")
     */
    public function buscarIdAction($id) {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:Conocimiento')->find($id);
        if ($singleresult === null) {
            return new View("Conocimiento no encontrado", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }

    /**
     * @Rest\Get("/conocimiento/perfil/{perfilId}")
     */
    public function listarAsignadasAction($perfilId) {
        $resultado = $this->getDoctrine()->getRepository('AppBundle:PerfilConocimiento')->findBy(array('perfilId' => $perfilId));
        if ($resultado === null) {
            return new View("no encontrado", Response::HTTP_NOT_FOUND);
        }
        return $resultado;
    }

    /**
     * @Rest\Get("/conocimiento/nombre/{nombre}")
     */
    public function buscarNombreAction($nombre) {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:Conocimiento')->findBy(array('nombre' => $nombre));
        if ($singleresult === null) {
            return new View("Conocimiento no encontrado", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }

    /**
     * @Rest\Post("/conocimiento")
     */
    public function crearAction(Request $request) {

        $em = $this->getDoctrine()->getManager();

        // 1. Creamos El conocimiento

        $nombre = $request->get('nombre');
        $definicion = $request->get('definicion');

        $conocimiento = new Conocimiento;
        $conocimiento->setNombre($nombre);
        $conocimiento->setDefinicion($definicion);
        $em->persist($conocimiento);
        $em->flush();

        $conocimientoId = $conocimiento->getId();

        // 2. Creamos los aprendizajes y los asignamos a El conocimiento recien creada

        $aprendizajes = $request->get('aprendizajes');
        foreach ($aprendizajes as $aprend) {

            //creamos el aprendizaje

            $aprendizaje = new Aprendizaje();
            $aprendizaje->setNombre($aprend);
            $em->persist($aprendizaje);
            $em->flush();

            $aprendizajeId = $aprendizaje->getId();

            // Asignamos aprendizaje recien creado

            $conocimientoAprendizaje = new ConocimientoAprendizaje();
            $conocimientoAprendizaje->setConocimientoId($conocimientoId);
            $conocimientoAprendizaje->setAprendizajeId($aprendizajeId);
            $em->persist($conocimientoAprendizaje);
            $em->flush();
        }
        return new View("El conocimiento y sus aprendizajes fueron creados", Response::HTTP_OK);
    }

    /**
 * @Rest\Put("/conocimiento/{id}")
 */
public function actualizarAction($id, Request $request) {

    $em = $this->getDoctrine()->getManager();

    $nombre = $request->get('nombre');
    $definicion = $request->get('definicion');

    $conocimiento = $this->getDoctrine()->getRepository('AppBundle:Conocimiento')->find($id);
    if (!empty($conocimiento)) {

        // Actualizamos El conocimiento

        $conocimiento->setNombre($nombre);
        $conocimiento->setDefinicion($definicion);
        $em->flush();

        // Eliminamos los aprendizajes relacionados originalmente

        $idConocimiento = array();
        $apren = $this->getDoctrine()->getRepository('AppBundle:ConocimientoAprendizaje')->findBy(array('conocimientoId' => $id));
        if (!empty($apren)) {
            foreach ($apren as $aprendi) {
                array_push($idConocimiento, $aprendi->getAprendizajeId());
                $em->remove($aprendi);
                $em->flush();
            }
        }

        foreach ($idConocimiento as $aprendizajeId) {
            $aprendi = $this->getDoctrine()->getRepository('AppBundle:Aprendizaje')->find($aprendizajeId);
            if (!empty($aprendi)) {
                $em->remove($aprendi);
                $em->flush();
            }
        }

        // Registramos y asignamos los aprendizajes

        $aprendizajes = $request->get('aprendizajes');
        foreach ($aprendizajes as $aprendi) {

            //creamos el aprendizaje

            $aprendizaje = new Aprendizaje();
            $aprendizaje->setNombre($aprendi);
            $em->persist($aprendizaje);
            $em->flush();

            $aprendizajeId = $aprendizaje->getId();

            // Asignamos aprendizaje recien creado

            $conocimientoAprendizaje = new ConocimientoAprendizaje();
            $conocimientoAprendizaje->setConocimientoId($id);
            $conocimientoAprendizaje->setAprendizajeId($aprendizajeId);
            $em->persist($conocimientoAprendizaje);
            $em->flush();

        }
        return new View("Modificación de Conocimiento y aprendizajes realizada", Response::HTTP_OK);

    }

}

    /**
     * @Rest\Delete("/conocimiento/{id}")
     */
    public function borrarAction($id) {
        $data = new Conocimiento;
        $sn = $this->getDoctrine()->getManager();
        $conocimiento = $this->getDoctrine()->getRepository('AppBundle:Conocimiento')->find($id);
        if (empty($conocimiento)) {
            return new View("No se encontro la Conocimiento", Response::HTTP_NOT_FOUND);
        } else {
            $sn->remove($conocimiento);
            $sn->flush();
        }
        return new View("Conocimiento borrada Exitosamente!", Response::HTTP_OK);
    }
    /**
     * @Rest\Delete("/conocimiento/aprendizaje/{id}")
     */
    public function borrarConAsignadoAction($id, Request $request) {

        $em = $this->getDoctrine()->getManager();

        $conocimiento = $this->getDoctrine()->getRepository('AppBundle:Conocimiento')->find($id);
        if (!empty($conocimiento)) {

            // Eliminamos los indicadores relacionados originalmente

            $idConocimiento = array();
            $apren = $this->getDoctrine()->getRepository('AppBundle:ConocimientoAprendizaje')->findBy(array('conocimientoId' => $id));
            if (!empty($apren)) {
                foreach ($apren as $aprendi) {
                    array_push($idConocimiento, $aprendi->getAprendizajeId());
                    $em->remove($aprendi);
                    $em->flush();
                }
            }

            foreach ($idConocimiento as $aprendizajeId) {
                $aprendi = $this->getDoctrine()->getRepository('AppBundle:Aprendizaje')->find($aprendizajeId);
                if (!empty($aprendi)) {
                    $em->remove($aprendi);
                    $em->flush();
                }
            }

            //eliminamos El conocimiento
              $conocimiento = new Conocimiento;
              $conocimiento = $this->getDoctrine()->getRepository('AppBundle:Conocimiento')->find($id);

              $em->remove($conocimiento);
              $em->flush();

            return new View("Borrado de Conocimiento y sus asignaciones realizado con exito", Response::HTTP_OK);

        }

    }

}
