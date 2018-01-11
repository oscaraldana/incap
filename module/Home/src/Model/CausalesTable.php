<?php
namespace Home\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class CausalesTable
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

    public function getCausal($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id_causal' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
                ));
        }

        return $row;
    }

    public function saveCausal(Causales $causal)
    {
        $data = [
            'causal' => $causal->causal,
        ];

        $id = (int) $causal->id_causal;

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

        $this->tableGateway->update($data, ['id_causal' => $id]);
    }

    public function deleteCausal($id)
    {
        $this->tableGateway->delete(['id_causal' => (int) $id]);
    }
}