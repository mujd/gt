<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\ActividadClave;
use AppBundle\Entity\UclActividadClave;
use AppBundle\Entity\Criterio;
use AppBundle\Entity\ActividadClaveCriterio;

class ActividadClaveController extends FOSRestController {
  /**
  * @Rest\Get("/actividadClave")
  */
 public function listarAction(){
   $restresult = $this->getDoctrine()->getRepository('AppBundle:ActividadClave')->findAll();
     if ($restresult === null) {
       return new View("no hay nada", Response::HTTP_NOT_FOUND);
  }
     return $restresult;
 }

 /**
  * @Rest\Get("/actividadClave/{id}")
  */
 public function buscarIdAction($id) {
     $singleresult = $this->getDoctrine()->getRepository('AppBundle:ActividadClave')->find($id);
     if ($singleresult === null) {
         return new View("actividad clave no encontrada", Response::HTTP_NOT_FOUND);
     }
     return $singleresult;
 }

 /**
  * @Rest\Get("/actividadClave/ucl/{uclId}")
  */
 public function listarPorActividadAction($uclId) {
    $retorno = array();
    $actividadesClave = $this->getDoctrine()->getRepository('AppBundle:UclActividadClave')->findBy(array('uclId' => $uclId));
    if ($actividadesClave !== null) {
        foreach ($actividadesClave as $actividad) {
            $actividadClave = $this->getDoctrine()->getRepository('AppBundle:ActividadClave')->find($actividad->getActividadClaveId());
            if ($actividadClave != null) {
                array_push($retorno, $actividadClave);
            }
        }
        return $retorno;
    }
 }


 /**
  * @Rest\Post("/actividadClave")
  */
 public function crearAsignarAction(Request $request) {

     $actividadClave = new ActividadClave;
     $uclActividadClave = new UclActividadClave;

     $uclId = $request->get('uclId');
     $nombre = $request->get('nombre');

     $actividadClave->setNombre($nombre);

     $em = $this->getDoctrine()->getManager();
     $em->persist($actividadClave);
     $em->flush();
     $actividadClaveId = $actividadClave->getId();

     $uclActividadClave->setActividadClaveId($actividadClaveId);
     $uclActividadClave->setUclId($uclId);

     $em->persist($uclActividadClave);
     $em->flush();

     return new View("Actividad Clave creada y asignada a UCL correctamente", Response::HTTP_OK);

 }

 /**
   * @Rest\Post("/actividadClave/{id}")
   */

   //Asigna criterio singular a Actividad Clave

   public function asignarCriterioAction($id, Request $request){
       $actividadClaveCriterio = new ActividadClaveCriterio();
       $criterioId = $request->get('criterioId');

       $actividadClaveCriterio->setCriterioId($criterioId);
       $actividadClaveCriterio->setActividadClaveId($id);

       $em = $this->getDoctrine()->getManager();
       $em->persist($actividadClaveCriterio);
       $em->flush();
       return new View("criterio asignado a Actividad Clave Correctamente", Response::HTTP_OK);
   }

  /**
   * @Rest\Put("/actividadClave/{id}")
   */
  public function actualizarAction($id, Request $request) {
      $data = new ActividadClave;
      $nombre = $request->get('nombre');
      $sn = $this->getDoctrine()->getManager();
      $actividadClave = $this->getDoctrine()->getRepository('AppBundle:ActividadClave')->find($id);
      if (empty($actividadClave)) {
          return new View("actividadClave not found", Response::HTTP_NOT_FOUND);
      } elseif (!empty($nombre)) {
          $actividadClave->setNombre($nombre);
          $sn->flush();
          return new View("actividadClave Actualizada Correctamente", Response::HTTP_OK);
      } else
          return new View("el nombre de la ACTIVIDAD CLAVE no puede estar vacio!", Response::HTTP_NOT_ACCEPTABLE);
  }

  /**
    * @Rest\Put("/actividadClave/criterio/{id}")
    */
    public function actualizarCriterioAction($id, Request $request) {
      $em = $this->getDoctrine()->getManager();

      $actividadClave = $this->getDoctrine()->getRepository('AppBundle:ActividadClave')->find($id);
        if (!empty($actividadClave)) {

            // Eliminamos criterios asignados originalmente
            $idCrit = array();
            $criterios = $this->getDoctrine()->getRepository('AppBundle:ActividadClaveCriterio')->findBy(array('actividadClaveId' => $id));
            if (!empty($criterios)) {
                foreach ($criterios as $crite) {
                    array_push($idCrit, $crite->getCriterioId());
                    $em->remove($crite);
                    $em->flush();
                }
            }
            foreach ($idCrit as $criterioId) {
                $crite = $this->getDoctrine()->getRepository('AppBundle:Criterio')->find($criterioId);
                if (!empty($crite)) {
                    $em->remove($crite);
                    $em->flush();
                }
            }

           $actividadClaveCriterio = new ActividadClaveCriterio();
           $criterioId = $request->get('criterioId');

           $actividadClaveCriterio->setCriterioId($criterioId);
           $actividadClaveCriterio->setActividadClaveId($id);

           $em->persist($actividadClaveCriterio);
           $em->flush();

           return new View("Modificación criterio asignado realizado", Response::HTTP_OK);
        }
    }

    /**
      * @Rest\Delete("/actividadClave/criterio/{id}")
      */
      public function borrarCriterioAsignadoAction($id) {

          $em = $this->getDoctrine()->getManager();

          $actividadClave = $this->getDoctrine()->getRepository('AppBundle:ActividadClave')->find($id);
          if (!empty($actividadClave)) {

              // Eliminamos criterios asignados originalmente
              $idCrit = array();
              $criterios = $this->getDoctrine()->getRepository('AppBundle:ActividadClaveCriterio')->findBy(array('actividadClaveId' => $id));
              if (!empty($criterios)) {
                  foreach ($criterios as $crite) {
                      array_push($idCrit, $crite->getCriterioId());
                      $em->remove($crite);
                      $em->flush();
                  }
              }
              foreach ($idCrit as $criterioId) {
                  $crite = $this->getDoctrine()->getRepository('AppBundle:Criterio')->find($criterioId);
                  if (!empty($crite)) {
                      $em->remove($crite);
                      $em->flush();
                  }
              }

              //eliminamos el criterio
              $criterio = new Criterio;
              $criterio = $this->getDoctrine()->getRepository('AppBundle:Criterio')->find($id);

              $em->remove($criterio);
              $em->flush();

             return new View("criterio asignado eliminado", Response::HTTP_OK);
          }
      }

  /**
   * @Rest\Delete("/actividadClave/{id}")
   */
  public function borrarAction($id) {
      $data = new ActividadClave;
      $sn = $this->getDoctrine()->getManager();
      $actividadClave = $this->getDoctrine()->getRepository('AppBundle:ActividadClave')->find($id);
      if (empty($actividadClave)) {
          return new View("No se encontro la ActividadClave", Response::HTTP_NOT_FOUND);
      } else {
          $sn->remove($actividadClave);
          $sn->flush();
      }
      return new View("ActividadClave borrada Exitosamente!", Response::HTTP_OK);
  }


     /*

    UCL --> Actividad Clave N°1
        --> Actividad Clave N°2 --+--> Criterio N°2.1
                                  |
                                  +--> Criterio N°2.2
                                  |
                                  +--> Criterio N°2.3
                                  |
                                  +--> Criterio N°2.4
        --> Actividad Clave N°3
        --> Actividad Clave N°4
        --> Actividad Clave N°5

     */


}
