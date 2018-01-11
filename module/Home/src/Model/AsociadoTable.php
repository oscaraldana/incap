<?php
namespace Home\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\Sql;
use Zend\Db\Sql\Predicate;

class AsociadoTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function getAsociado($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id_asociado' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
                ));
        }

        return $row;
    }

    public function searchAsociados($param)
    {
        
        $name   = new Predicate\Like( 'nombre_asociado', "%{$param}%" );
        $apellido1   = new Predicate\Like( 'apellido1_asociado', "%{$param}%" );
        $apellido2   = new Predicate\Like( 'apellido2_asociado', "%{$param}%" );
        $cedula   = new Predicate\Like( 'identificacion', "%{$param}%" );
        $na1 = new Predicate\Like( new Predicate\Expression("Concat(UPPER(nombre_asociado),' ',UPPER(apellido1_asociado))"), "%{$param}%" );
        $na2 = new Predicate\Like( new Predicate\Expression("Concat(UPPER(apellido1_asociado),' ',UPPER(apellido2_asociado))"), "%{$param}%" );
        $na3 = new Predicate\Like( new Predicate\Expression("Concat(UPPER(nombre_asociado),' ',UPPER(apellido1_asociado),' ',UPPER(apellido2_asociado))"), "%{$param}%" );
        
        $name->setSpecification('%1$s LIKE %2$s');
        $apellido1->setSpecification('%1$s LIKE %2$s');
        $apellido2->setSpecification('%1$s LIKE %2$s');
        $cedula->setSpecification('%1$s LIKE %2$s');
        $na1->setSpecification('%1$s LIKE %2$s');
        $na2->setSpecification('%1$s LIKE %2$s');
        $na3->setSpecification('%1$s LIKE %2$s');
        
        $where = new \Zend\Db\Sql\Where;
        
        $where->addPredicate(
                     new Predicate\PredicateSet(
                            array(
                                    $name,
                                    $apellido1,
                                    $apellido2,
                                    $cedula,
                                    $na1,
                                    $na2,
                                    $na3,
                            ),
                            Predicate\PredicateSet::COMBINED_BY_OR
                    )
                );
        
        $rowset = $this->tableGateway->select( $where );
        
        return $rowset;
        
    }
    
    public function saveAsociado(Asociado $asociado)
    {
        $data = [
            'nombre_asociado' => $asociado->nombre_asociado,
            'apellido1_asociado' => $asociado->apellido1_asociado,
            'apellido2_asociado' => $asociado->apellido2_asociado,
            'identificacion' => $asociado->identificacion,
        ];

        $id = (int) $asociado->id_asociado;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (! $this->getE($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update tipoincap with identifier %d; does not exist',
                $id
                ));
        }

        $this->tableGateway->update($data, ['id_asociado' => $id]);
    }

    public function deleteAsociado($id)
    {
        $this->tableGateway->delete(['id_asociado' => (int) $id]);
    }
}