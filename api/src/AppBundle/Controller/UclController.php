<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Ucl;
use AppBundle\Entity\ActividadClave;
use AppBundle\Entity\UclActividadClave;
use AppBundle\Entity\Criterio;
use AppBundle\Entity\ActividadClaveCriterio;

class UclController extends FOSRestController {

    /**
     * @Rest\Get("/ucl")
     */
    public function listarAction() {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:Ucl')->findAll();
        if ($restresult === null) {
            return new View("no hay nada", Response::HTTP_NOT_FOUND);
        }
        return $restresult;
    }

    /**
     * @Rest\Get("/ucl/{id}")
     */
    public function buscarIdAction($id) {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:Ucl')->find($id);
        if ($singleresult === null) {
            return new View("UCL no encontrado", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }

    /**
     * @Rest\Get("/ucl/perfil/{perfilId}")
     */
    public function listarPorPerfilAction($perfilId) {
        $resultado = $this->getDoctrine()->getRepository('AppBundle:PerfilUcl')->findBy(array('perfilId' => $perfilId));
        if ($resultado === null) {
            return new View("no hay UCL con ese perfil", Response::HTTP_NOT_FOUND);
        }
        return $resultado;
    }

    /**
     * @Rest\Get("/ucl/nombre/{nombre}")
     */
    public function buscarNombreAction($nombre) {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:Ucl')->findBy(array('nombre' => $nombre));
        if ($singleresult === null) {
            return new View("UCL no encontrado", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }

    /**
     * @Rest\Post("/ucl")
     */
     public function crearAction(Request $request) {

        $em = $this->getDoctrine()->getManager();

        // 1. Creamos la UCL

        $nombre = $request->get('nombre');
        $definicion = $request->get('definicion');

        $ucl = new Ucl;
        $ucl->setNombre($nombre);
        $ucl->setDefinicion($definicion);
        $em->persist($ucl);
        $em->flush();

        $uclId = $ucl->getId();

        // 2. Creamos las actividades clave y las asignamos a la UCL recien creada

        $actividadesClave = $request->get('actividadesClave');
        foreach ($actividadesClave as $actividad) {

            // Creamos la actividad clave

            $actividadClave = new ActividadClave();
            $actividadClave->setNombre($actividad);
            $em->persist($actividadClave);
            $em->flush();

            $actividadClaveId = $actividadClave->getId();

            // Asignamos la actividad clave recien creada

            $uclActividadClave = new UclActividadClave();
            $uclActividadClave->setUclId($uclId);
            $uclActividadClave->setActividadClaveId($actividadClaveId);
            $em->persist($uclActividadClave);
            $em->flush();

            // Al parecer FOSRestBundle no puede registrar nietos (nested json)
            /*
            $criterios = $request->get('criterios');
            foreach ($criterios as $crit) {

                // Creamos el criterio

                $criterio = new Criterio();
                $criterio->setNombre($crit);
                $em->persist($criterio);
                $em->flush();

                $criterioId = $criterio->getId();

                // Asignamos el criterio recien creado

                $actividadClaveCriterio = new ActividadClaveCriterio();
                $actividadClaveCriterio->setActividadClaveId($actividadClaveId);
                $actividadClaveCriterio->setCriterioId($criterioId);
                $em->persist($actividadClaveCriterio);
                $em->flush();

            }
            */

        }

        return new View("La UCL y sus actividades clave fueron creadas", Response::HTTP_OK);

    }

    /**
     * @Rest\Post("/ucl/{id}")
     */
     public function asignarActividadClaveAction($id, Request $request) {

        $actividadesClave = $request->get('actividadesClave');
        foreach ($actividadesClave as $actividad) {

            // Creamos la actividad clave

            $actividadClave = new ActividadClave();
            $actividadClave->setNombre($actividad);

            $em = $this->getDoctrine()->getManager();
            $em->persist($actividadClave);
            $em->flush();

            $actividadClaveId = $actividadClave->getId();

            // Asignamos la actividad clave recien creada

            $uclActividadClave = new UclActividadClave();
            $uclActividadClave->setUclId($id);
            $uclActividadClave->setActividadClaveId($actividadClaveId);
            $em->persist($uclActividadClave);
            $em->flush();

        }
        return new View("Las actividades clave fueron creadas y asignadas a su UCL", Response::HTTP_OK);

    }

    /**
     * @Rest\Put("/ucl/{id}")
     */
    public function actualizarAction($id, Request $request) {

        $em = $this->getDoctrine()->getManager();

        $nombre = $request->get('nombre');
        $definicion = $request->get('definicion');

        $ucl = $this->getDoctrine()->getRepository('AppBundle:Ucl')->find($id);
        if (!empty($ucl)) {

            // Actualizamos la UCL

            $ucl->setNombre($nombre);
            $ucl->setDefinicion($definicion);
            $em->flush();

            // Eliminamos las actividades clave relacionadas originalmente

            $idAct = array();
            $actividades = $this->getDoctrine()->getRepository('AppBundle:UclActividadClave')->findBy(array('uclId' => $id));
            if (!empty($actividades)) {
                foreach ($actividades as $actividad) {
                    array_push($idAct, $actividad->getActividadClaveId());
                    $em->remove($actividad);
                    $em->flush();
                }
            }

            foreach ($idAct as $actividadId) {
                $actividad = $this->getDoctrine()->getRepository('AppBundle:ActividadClave')->find($actividadId);
                if (!empty($actividad)) {
                    $em->remove($actividad);
                    $em->flush();
                }
            }

            // Registramos y asignamos las actividades clave

            $actividadesClave = $request->get('actividadesClave');
            foreach ($actividadesClave as $actividad) {

                // Creamos la actividad clave

                $actividadClave = new ActividadClave();
                $actividadClave->setNombre($actividad);
                $em->persist($actividadClave);
                $em->flush();

                $actividadClaveId = $actividadClave->getId();

                // Asignamos la actividad clave recien creada

                $uclActividadClave = new UclActividadClave();
                $uclActividadClave->setUclId($id);
                $uclActividadClave->setActividadClaveId($actividadClaveId);
                $em->persist($uclActividadClave);
                $em->flush();

            }
            return new View("Modificación de UCL y Actividades Clave realizada", Response::HTTP_OK);

        }

    }

    /**
     * @Rest\Delete("/ucl/{id}")
     */
    public function borrarAction($id) {
        $data = new Ucl;
        $sn = $this->getDoctrine()->getManager();
        $ucl = $this->getDoctrine()->getRepository('AppBundle:Ucl')->find($id);
        if (empty($ucl)) {
            return new View("No se encontro el UCL", Response::HTTP_NOT_FOUND);
        } else {
            $sn->remove($ucl);
            $sn->flush();
        }
        return new View("UCL borrado Exitosamente!", Response::HTTP_OK);
    }

    /**
     * @Rest\Delete("/ucl/actividadClave/{id}")
     */
    public function borrarConAsignadoAction($id, Request $request) {

        $em = $this->getDoctrine()->getManager();

        $ucl = $this->getDoctrine()->getRepository('AppBundle:Ucl')->find($id);
        if (!empty($ucl)) {

            // Eliminamos las actividades clave relacionadas originalmente

            $idAct = array();
            $actividades = $this->getDoctrine()->getRepository('AppBundle:UclActividadClave')->findBy(array('uclId' => $id));
            if (!empty($actividades)) {
                foreach ($actividades as $actividad) {
                    array_push($idAct, $actividad->getActividadClaveId());
                    $em->remove($actividad);
                    $em->flush();
                }
            }

            foreach ($idAct as $actividadId) {
                $actividad = $this->getDoctrine()->getRepository('AppBundle:ActividadClave')->find($actividadId);
                if (!empty($actividad)) {
                    $em->remove($actividad);
                    $em->flush();
                }
            }

            //eliminamos la UCL
              $ucl = new Criterio;
              $ucl = $this->getDoctrine()->getRepository('AppBundle:UCL')->find($id);

              $em->remove($ucl);
              $em->flush();


            }
            return new View("UCL borrada junto con sus asignaciones", Response::HTTP_OK);

        }
}
