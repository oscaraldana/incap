<?php
namespace Home\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class UsuariosTable
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

    public function validateUsuario($login, $passwd)
    {
        $rowset = $this->tableGateway->select(['login' => $login, 'passwd' => $passwd, 'estado' => true]);
        $row = $rowset->current();
        if (! $row) {
            return false;
        }

        return $row;
    }

    public function getUsuario($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id_usuario' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
                ));
        }

        return $row;
    }

    public function saveUsuario(Usuarios $usuario)
    {
        $data = [
            'login' => $usuario->login,
            'passwd' => $usuario->passwd,
            'estado' => $usuario->estado
        ];

        $id = (int) $usuario->id_usuario;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (! $this->getUsuario($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update tipoincap with identifier %d; does not exist',
                $id
                ));
        }

        $this->tableGateway->update($data, ['id_usuario' => $id]);
    }

    public function deleteUsuario($id)
    {
        $this->tableGateway->delete(['id_usuario' => (int) $id]);
    }
}