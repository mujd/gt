<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Conducta;
use AppBundle\Entity\ConductaIndicador;
use AppBundle\Entity\Indicador;

class ConductaController extends FOSRestController {

    /**
     * @Rest\Get("/conducta")
     */
    public function listarAction() {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:Conducta')->findAll();
        if ($restresult === null) {
            return new View("no hay nada", Response::HTTP_NOT_FOUND);
        }
        return $restresult;
    }

    /**
     * @Rest\Get("/conducta/{id}")
     */
    public function buscarIdAction($id) {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:Conducta')->find($id);
        if ($singleresult === null) {
            return new View("Conducta no encontrado", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }

    /**
     * @Rest\Get("/conducta/perfil/{perfilId}")
     */
    public function listarAsignadasAction($perfilId) {
        $resultado = $this->getDoctrine()->getRepository('AppBundle:PerfilConducta')->findBy(array('perfilId' => $perfilId));
        if ($resultado === null) {
            return new View("no encontrado", Response::HTTP_NOT_FOUND);
        }
        return $resultado;
    }

    /**
     * @Rest\Get("/conducta/nombre/{nombre}")
     */
    public function buscarNombreAction($nombre) {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:Conducta')->findBy(array('nombre' => $nombre));
        if ($singleresult === null) {
            return new View("Conducta no encontrado", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }

    /**
     * @Rest\Post("/conducta")
     */
    public function crearAction(Request $request) {

        $em = $this->getDoctrine()->getManager();

        // 1. Creamos la Conducta

        $nombre = $request->get('nombre');
        $definicion = $request->get('definicion');

        $conducta = new Conducta;
        $conducta->setNombre($nombre);
        $conducta->setDefinicion($definicion);
        $em->persist($conducta);
        $em->flush();

        $conductaId = $conducta->getId();

        // 2. Creamos los indicadores y los asignamos a la Conducta recien creada

        $indicadores = $request->get('indicadores');
        foreach ($indicadores as $indica) {

            //creamos el indicador

            $indicador = new Indicador();
            $indicador->setNombre($indica);
            $em->persist($indicador);
            $em->flush();

            $indicadorId = $indicador->getId();

            // Asignamos el indicador recien creado

            $conductaIndicador = new ConductaIndicador();
            $conductaIndicador->setConductaId($conductaId);
            $conductaIndicador->setIndicadorId($indicadorId);
            $em->persist($conductaIndicador);
            $em->flush();
        }
        return new View("La Conducta y sus indicadores fueron creados", Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/conducta/{id}")
     */
    public function actualizarAction($id, Request $request) {

        $em = $this->getDoctrine()->getManager();

        $nombre = $request->get('nombre');
        $definicion = $request->get('definicion');

        $conducta = $this->getDoctrine()->getRepository('AppBundle:Conducta')->find($id);
        if (!empty($conducta)) {

            // Actualizamos la Conducta

            $conducta->setNombre($nombre);
            $conducta->setDefinicion($definicion);
            $em->flush();

            // Eliminamos los indicadores relacionados originalmente

            $idConductual = array();
            $indi = $this->getDoctrine()->getRepository('AppBundle:ConductaIndicador')->findBy(array('conductaId' => $id));
            if (!empty($indi)) {
                foreach ($indi as $indica) {
                    array_push($idConductual, $indica->getIndicadorId());
                    $em->remove($indica);
                    $em->flush();
                }
            }

            foreach ($idConductual as $indicadorId) {
                $indica = $this->getDoctrine()->getRepository('AppBundle:Indicador')->find($indicadorId);
                if (!empty($indica)) {
                    $em->remove($indica);
                    $em->flush();
                }
            }

            // Registramos y asignamos los indicadores

            $indicadores = $request->get('indicadores');
            foreach ($indicadores as $indica) {

                // Creamos el indicador

                $indicador = new Indicador();
                $indicador->setNombre($indica);
                $em->persist($indicador);
                $em->flush();

                $indicadorId = $indicador->getId();

                // Asignamos el indicador recien creado

                $conductaIndicador = new ConductaIndicador();
                $conductaIndicador->setConductaId($id);
                $conductaIndicador->setIndicadorId($indicadorId);
                $em->persist($conductaIndicador);
                $em->flush();

            }
            return new View("Modificación de Conducta e indicadores realizada", Response::HTTP_OK);

        }

    }

    /**
     * @Rest\Delete("/conducta/{id}")
     */
    public function borrarAction($id) {
        $data = new Conducta;
        $sn = $this->getDoctrine()->getManager();
        $conducta = $this->getDoctrine()->getRepository('AppBundle:Conducta')->find($id);
        if (empty($conducta)) {
            return new View("No se encontro la Conducta", Response::HTTP_NOT_FOUND);
        } else {
            $sn->remove($conducta);
            $sn->flush();
        }
        return new View("Conducta borrada Exitosamente!", Response::HTTP_OK);
    }
    /**
     * @Rest\Delete("/conducta/indicador/{id}")
     */
    public function borrarConAsignadoAction($id, Request $request) {

        $em = $this->getDoctrine()->getManager();

        $conducta = $this->getDoctrine()->getRepository('AppBundle:Conducta')->find($id);
        if (!empty($conducta)) {

            // Eliminamos los indicadores relacionados originalmente

            $idConductual = array();
            $indi = $this->getDoctrine()->getRepository('AppBundle:ConductaIndicador')->findBy(array('conductaId' => $id));
            if (!empty($indi)) {
                foreach ($indi as $indica) {
                    array_push($idConductual, $indica->getIndicadorId());
                    $em->remove($indica);
                    $em->flush();
                }
            }

            foreach ($idConductual as $indicadorId) {
                $indica = $this->getDoctrine()->getRepository('AppBundle:Indicador')->find($indicadorId);
                if (!empty($indica)) {
                    $em->remove($indica);
                    $em->flush();
                }
            }

            //eliminamos la Conducta
              $conducta = new Conducta;
              $conducta = $this->getDoctrine()->getRepository('AppBundle:Conducta')->find($id);

              $em->remove($conducta);
              $em->flush();

            return new View("Borrado de Conducta y sus asignaciones realiado con exito", Response::HTTP_OK);

        }

    }

}
