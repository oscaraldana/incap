<?php
namespace Home\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class TipoIncapacidadTable
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

    public function getTipoIncapacidad($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id_tipoincapacidad' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
                ));
        }

        return $row;
    }

    public function saveTipoIncapacidad(TipoIncapacidad $tipoincapacidad)
    {
        $data = [
            'tipoincapacidad' => $tipoincapacidad->tipoincapacidad
        ];

        $id = (int) $tipoincapacidad->id_tipoincapacidad;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (! $this->getTipoIncapacidad($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update tipoincap with identifier %d; does not exist',
                $id
                ));
        }

        $this->tableGateway->update($data, ['id_tipoincapacidad' => $id]);
    }

    public function deleteTipoIncapacidad($id)
    {
        $this->tableGateway->delete(['id_tipoincapacidad' => (int) $id]);
    }
}