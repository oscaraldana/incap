<?php
namespace Home\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\Sql;
use Zend\Db\Sql\Predicate;

class DiagnosticoTable
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

    public function getDiagnostico($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id_diagnostico' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
                ));
        }

        return $row;
    }

    public function searchDiagnosticos($param)
    {
        
        $name   = new Predicate\Like( 'nombre_diagnostico', "%{$param}%" );
        $code   = new Predicate\Like( 'codigo_diagnostico', "%{$param}%" );
        //$namecode   = new Predicate\Like( "nombre||' '||codigo", "%{$param}%" );
        //$codename   = new Predicate\Like( "codigo||' '||nombre", "%{$param}%" );
        
        $name->setSpecification('UPPER(%1$s) LIKE UPPER(%2$s)');
        $code->setSpecification('UPPER(%1$s) LIKE UPPER(%2$s)');
        //$namecode->setSpecification('%1$s ILIKE %2$s');
        //$codename->setSpecification('%1$s ILIKE %2$s');
        
        $where = new \Zend\Db\Sql\Where;
        
        $where->addPredicate(
                     new Predicate\PredicateSet(
                            array(
                                    $name,
                                    $code,
                                    //$namecode,
                                    //$codename,
                            ),
                            Predicate\PredicateSet::COMBINED_BY_OR
                    )
                );
        //$where->OR->ilike( 'codigo', "%$param%" );
        
        $rowset = $this->tableGateway->select( $where );
        //$row = $rowset->current();
        
        return $rowset;
        
    }
    
    public function saveDiagnostico(Diagnostico $diagnostico)
    {
        $data = [
            'nombre_diagnostico' => $diagnostico->nombre_diagnostico,
            'codigo_diagnostico' => $diagnostico->codigo_diagnostico,
        ];

        $id = (int) $diagnostico->id_diagnostico;

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

        $this->tableGateway->update($data, ['id_diagnostico' => $id]);
    }

    public function deleteDiagnostico($id)
    {
        $this->tableGateway->delete(['id_diagnostico' => (int) $id]);
    }
}