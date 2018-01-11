<?php
namespace Home\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class SucursalTable
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

    public function getSucursal($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id_sucursal' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
                ));
        }

        return $row;
    }

    public function saveSucursal(Sucursal $sucursal)
    {
        $data = [
            'sucursal' => $sucursal->sucursal
        ];

        $id = (int) $sucursal->id_sucursal;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (! $this->getSucursal($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update tipoincap with identifier %d; does not exist',
                $id
                ));
        }

        $this->tableGateway->update($data, ['id_sucursal' => $id]);
    }

    public function deleteSucursal($id)
    {
        $this->tableGateway->delete(['id_sucursal' => (int) $id]);
    }
}