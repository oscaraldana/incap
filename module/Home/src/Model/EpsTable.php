<?php
namespace Home\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class EpsTable
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

    public function getEps($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id_eps' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
                ));
        }

        return $row;
    }

    public function saveEps(Eps $eps)
    {
        $data = [
            'nombre_eps' => $eps->nombre_eps,
            'direccion_eps' => $eps->direccion_eps,
            'telefono_eps' => $eps->telefono_eps,
        ];

        $id = (int) $eps->id_eps;

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

        $this->tableGateway->update($data, ['id_eps' => $id]);
    }

    public function deleteEps($id)
    {
        $this->tableGateway->delete(['id_eps' => (int) $id]);
    }
}