<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Perfil;
use AppBundle\Entity\Conducta;
use AppBundle\Entity\Ucl;
use AppBundle\Entity\PerfilConducta;
use AppBundle\Entity\PerfilUcl;
use AppBundle\Entity\Conocimiento;
use AppBundle\Entity\PerfilConocimiento;

class PerfilController extends FOSRestController {

    /**
     * @Rest\Get("/perfil")
     */
    public function listarAction() {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:Perfil')->findAll();
        if ($restresult === null) {
            return new View("no hay nada", Response::HTTP_NOT_FOUND);
        }
        return $restresult;
    }

    /**
     * @Rest\Get("/perfil/{id}")
     */
    public function buscarIdAction($id) {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:Perfil')->find($id);
        if ($singleresult === null) {
            return new View("perfil no encontrado", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }

    /**
     * @Rest\Get("/perfil/competencias/{perfilId}")
     */
    public function listarPorCompetenciasAction($perfilId) {
       $retorno = array();
       $ucls = $this->getDoctrine()->getRepository('AppBundle:PerfilUcl')->findBy(array('perfilId' => $perfilId));
       $competencias = $this->getDoctrine()->getRepository('AppBundle:PerfilConducta')->findBy(array('perfilId' => $perfilId));
       $conocimientos = $this->getDoctrine()->getRepository('AppBundle:PerfilConocimiento')->findBy(array('perfilId' => $perfilId));
       if ($ucls !== null && $competencias !== null) {
           foreach ($ucls as $uc) {
               $ucl = $this->getDoctrine()->getRepository('AppBundle:Ucl')->find($uc->getUclId());
               if ($ucl != null) {
                   array_push($retorno, $ucl);
               }
           }

           foreach ($competencias as $comp) {
               $competencia = $this->getDoctrine()->getRepository('AppBundle:Conducta')->find($comp->getConductaId());
               if ($competencia != null) {
                   array_push($retorno, $competencia);
               }
           }

           foreach ($conocimientos as $cono) {
               $conocimiento = $this->getDoctrine()->getRepository('AppBundle:Conocimiento')->find($cono->getConocimientoId());
               if ($conocimiento != null) {
                   array_push($retorno, $conocimiento);
               }
           }
           return $retorno;
       }
    }

    /**
     * @Rest\Get("/perfil/nombre/{nombre}")
     */
    public function buscarNombreAction($nombre) {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:Perfil')->findBy(array('nombre' => $nombre));
        if ($singleresult === null) {
            return new View("perfil no encontrado", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }

    /**
   * @Rest\Post("/perfil/crear")
   */
   public function postAction(Request $request){
      $perfil = new Perfil;

      $nombre = $request->get('nombre');
      $objetivo = $request->get('objetivo');
      $reporta = $request->get('reporta');
      $tarea = $request->get('tareas');
      $evaluacionTipoId = $request->get('evaluacionTipoId');
      $perfilFamiliaId = $request->get('perfilFamiliaId');

      $perfil->setNombre($nombre);
      $perfil->setObjetivo($objetivo);
      $perfil->setReporta($reporta);
      $perfil->setTareas($tarea);
      $perfil->setEvaluacionTipoId($evaluacionTipoId);
      $perfil->setPerfilFamiliaId($perfilFamiliaId);

      $em = $this->getDoctrine()->getManager();
      $em->persist($perfil);
      $em->flush();
      return new View("perfil agregada correctamente", Response::HTTP_OK);
  }


    /**
     * @Rest\Post("/perfil")
     */
    public function crearAction(Request $request) {
        $perfil = new Perfil;

        $nombre = $request->get('nombre');
        $objetivo = $request->get('objetivo');
        $reporta = $request->get('reporta');
        $tarea = $request->get('tareas');
        $evaluacionTipoId = $request->get('evaluacionTipoId');
        $perfilFamiliaId = $request->get('perfilFamiliaId');

        $perfil->setNombre($nombre);
        $perfil->setObjetivo($objetivo);
        $perfil->setReporta($reporta);
        $perfil->setTareas($tarea);
        $perfil->setEvaluacionTipoId($evaluacionTipoId);
        $perfil->setPerfilFamiliaId($perfilFamiliaId);

        $em = $this->getDoctrine()->getManager();
        $em->persist($perfil);
        $em->flush();

        $id = $perfil->getId();

        $perfilUcl = new PerfilUcl();
        $em = $this->getDoctrine()->getManager();
        $ucls = $this->getDoctrine()->getRepository('AppBundle:PerfilUcl')->findBy(array('perfilId' => $id));
        if (!empty($ucls)) {
            foreach ($ucls as $ucl) {
                $em->remove($ucl);
                $em->flush();
            }
        }

        $ucls = $request->get('ucls');
        foreach ($ucls as $ucl) {
            $perfilUcl = new PerfilUcl();
            $em = $this->getDoctrine()->getManager();
            $perfilUcl->setUclId($ucl);
            $perfilUcl->setPerfilId($id);
            $em->persist($perfilUcl);
            $em->flush();
        }

		$perfilConducta = new PerfilConducta();
        $em = $this->getDoctrine()->getManager();
        $competencias = $this->getDoctrine()->getRepository('AppBundle:PerfilConducta')->findBy(array('perfilId' => $id));
        if (!empty($competencias)) {
            foreach ($competencias as $competencia) {
                $em->remove($competencia);
                $em->flush();
            }
        }

        $competencias = $request->get('competencias');
        foreach ($competencias as $competencia) {
            $perfilConducta = new PerfilConducta();
            $em = $this->getDoctrine()->getManager();
            $perfilConducta->setConductaId($competencia);
            $perfilConducta->setPerfilId($id);
            $em->persist($perfilConducta);
            $em->flush();
        }
        return new View("perfil Agregado y asignado correctamente", Response::HTTP_OK);
    }


    /**
     * @Rest\Post("/perfil/ucl/{id}")
     */
     public function asignarUclAction($id, Request $request) {

        $ucls = $request->get('ucls');
        foreach ($ucls as $compLab) {

            // Creamos Ucl

            $ucl = new Ucl();
            $ucl->setNombre($compLab["nombre"]);
            $ucl->setDefinicion($compLab["definicion"]);

            $em = $this->getDoctrine()->getManager();
            $em->persist($ucl);
            $em->flush();

            $uclId = $ucl->getId();

            // Asignamos el ucl

            $perfilUcl = new PerfilUcl();
            $perfilUcl->setUclId($uclId);
            $perfilUcl->setPerfilId($id);
            $em->persist($perfilUcl);
            $em->flush();

        }
        return new View("Las Ucls fueron creadas y asignadas a Perfil", Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/perfil/conducta/{id}")
     */
     public function asignarConductualAction($id, Request $request) {

        $competenciasConductuales = $request->get('competenciasConductuales');
        foreach ($competenciasConductuales as $compConductual) {

            // Creamos Competencia Conductual

            $conducta = new Conducta();
            $conducta->setNombre($compConductual["nombre"]);
            $conducta->setDefinicion($compConductual["definicion"]);

            $em = $this->getDoctrine()->getManager();
            $em->persist($conducta);
            $em->flush();

            $conductaId = $conducta->getId();

            // Asignamos la Competencia Conductual

            $perfilConducta = new PerfilConducta();
            $perfilConducta->setConductaId($conductaId);
            $perfilConducta->setPerfilId($id);
            $em->persist($perfilConducta);
            $em->flush();
        }
        return new View("Las Competencias Conductuales fueron creadas y asignadas a Perfil", Response::HTTP_OK);
    }
    /**
     * @Rest\Post("/perfil/conocimiento/{id}")
     */
     public function asignarConocimientoAction($id, Request $request) {

        $competenciasConocimiento = $request->get('competenciasConocimiento');
        foreach ($competenciasConocimiento as $compConocimiento) {

            // Creamos Competencia Conocimiento

            $conocimiento = new Conocimiento();
            $conocimiento->setNombre($compConocimiento["nombre"]);
            $conocimiento->setDefinicion($compConocimiento["definicion"]);

            $em = $this->getDoctrine()->getManager();
            $em->persist($conocimiento);
            $em->flush();

            $conocimientoId = $conocimiento->getId();

            // Asignamos la Competencia Conocimiento

            $perfilConocimiento = new PerfilConocimiento();
            $perfilConocimiento->setConocimientoId($conocimientoId);
            $perfilConocimiento->setPerfilId($id);
            $em->persist($perfilConocimiento);
            $em->flush();
        }
        return new View("Las Competencias conocimiento fueron creadas y asignadas a Perfil", Response::HTTP_OK);
    }

    /**
         * @Rest\Put("/perfil/{id}")
         */
        public function actualizarAction($id, Request $request) {

            $nombre = $request->get('nombre');
            $objetivo = $request->get('objetivo');
            $reporta = $request->get('reporta');
            $tareas = $request->get('tareas');
            $evaluacionTipoId = $request->get('evaluacionTipoId');
            $perfilFamiliaId = $request->get('perfilFamiliaId');

            $em = $this->getDoctrine()->getManager();
            $perfil = $this->getDoctrine()->getRepository('AppBundle:Perfil')->find($id);
            if (!empty($perfil)) {

                $perfil->setNombre($nombre);
                $perfil->setObjetivo($objetivo);
                $perfil->setReporta($reporta);
                $perfil->setTareas($tareas);
                $perfil->setEvaluacionTipoId($evaluacionTipoId);
                $perfil->setPerfilFamiliaId($perfilFamiliaId);
                $em->flush();

                $perfilUcl = new PerfilUcl();
                $em = $this->getDoctrine()->getManager();
                $ucls = $this->getDoctrine()->getRepository('AppBundle:PerfilUcl')->findBy(array('perfilId' => $id));
                if (!empty($ucls)) {
                    foreach ($ucls as $ucl) {
                        $em->remove($ucl);
                        $em->flush();
                    }
                }

                $ucls = $request->get('ucls');
                foreach ($ucls as $ucl) {
                    $perfilUcl = new PerfilUcl();
                    $em = $this->getDoctrine()->getManager();
                    $perfilUcl->setUclId($ucl);
                    $perfilUcl->setPerfilId($id);
                    $em->persist($perfilUcl);
                    $em->flush();
                }

    			      $perfilConducta = new PerfilConducta();
                $em = $this->getDoctrine()->getManager();
                $competencias = $this->getDoctrine()->getRepository('AppBundle:PerfilConducta')->findBy(array('perfilId' => $id));
                if (!empty($competencias)) {
                    foreach ($competencias as $competencia) {
                        $em->remove($competencia);
                        $em->flush();
                    }
                }

                $competencias = $request->get('competencias');
                foreach ($competencias as $competencia) {
                    $perfilConducta = new PerfilConducta();
                    $em = $this->getDoctrine()->getManager();
                    $perfilConducta->setConductaId($competencia);
                    $perfilConducta->setPerfilId($id);
                    $em->persist($perfilConducta);
                    $em->flush();
                }

                $perfilCononcimiento = new PerfilConocimiento();
                $em = $this->getDoctrine()->getManager();
                $conocimientos = $this->getDoctrine()->getRepository('AppBundle:PerfilConocimiento')->findBy(array('perfilId' => $id));
                if (!empty($conocimientos)) {
                    foreach ($conocimientos as $conocimiento) {
                        $em->remove($conocimiento);
                        $em->flush();
                    }
                }

                $conocimientos = $request->get('conocimientos');
                foreach ($conocimientos as $conocimiento) {
                    $perfilConocimiento = new PerfilConocimiento();
                    $em = $this->getDoctrine()->getManager();
                    $perfilConocimiento->setConnocimientoId($conocimiento);
                    $perfilConocimiento->setPerfilId($id);
                    $em->persist($perfilConocimiento);
                    $em->flush();
                }
                return new View("Perfil Actualizada Correctamente", Response::HTTP_OK);

            } else {
                return new View("Perfil no Encontrada!", Response::HTTP_NOT_FOUND);
            }

        }

    /**
     * @Rest\Delete("/perfil/{id}")
     */
    public function borrarAction($id) {
        $data = new Perfil;
        $sn = $this->getDoctrine()->getManager();
        $perfil = $this->getDoctrine()->getRepository('AppBundle:Perfil')->find($id);
        if (empty($perfil)) {
            return new View("No se encontro el perfil", Response::HTTP_NOT_FOUND);
        } else {
            $sn->remove($perfil);
            $sn->flush();
        }
        return new View("perfil borrado Exitosamente!", Response::HTTP_OK);
    }

}
