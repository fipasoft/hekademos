<?php
Kumbia :: import('lib.kumbia.utils');

class Alumnosconarticulo extends ActiveRecord{
    protected $cursos_id;
    protected $clave;
    protected $materia;


    public function articulosAlumno($alumno_id){
        $ciclo_id = Session :: get_data('prof.ciclo.id');
        $articulos=new Alumnosconarticulo();
        $articulos=$articulos->find_all_by_sql(
                "SELECT cursos.id AS cursos_id,articulos.clave,materias.nombre AS materia,alumnosconarticulo.*
                    FROM
                    cursos
                    INNER JOIN grupos ON cursos.grupos_id=grupos.id
                    INNER JOIN materias ON materias.id=cursos.materias_id
                    INNER JOIN alumnoscursos ON cursos.id=alumnoscursos.cursos_id
                    INNER JOIN alumnosconarticulo ON alumnosconarticulo.alumnoscursos_id=alumnoscursos.id
                    INNER JOIN articulos ON alumnosconarticulo.articulos_id=articulos.id
                    WHERE grupos.ciclos_id=".$ciclo_id." AND alumnos_id=".$alumno_id);
        if(!is_array($articulos))
                return array();

                return $articulos;

    }

    public function articuloCurso($alumnoscursos_id){
        $articulos=new Alumnosconarticulo();
        $articulos=$articulos->find_first("alumnoscursos_id=".$alumnoscursos_id);

        $articulo=new Articulos();
        $articulo=$articulo->find($articulos->articulos_id);
        return $articulo;
    }

}
?>
