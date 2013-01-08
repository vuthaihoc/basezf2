<?php
namespace AdminCP\Model;
//use Hello\Form\Validate\FormNews;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
class News extends AbstractTableGateway
{

    protected $table ='g_news';
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        //$this->resultSetPrototype->setArrayObjectPrototype(new Album());
        $this->initialize();
    }
    public function listItem()
    {
        $resultSet = $this->select();
        return $resultSet;
    }
    public function getItem($id)
    {
        $id = (int) $id;
        $rowset = $this->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    public function saveItem($param)
    {
        $data = array(
            'name'       => $param['news_name'],
            'content'    => $param['news_content'],
        );
        $id = (int)$param['id'];
        if ($id == 0) {
            $this->insert($data);
        } else {
            if ($this->getItem($id)) {
            $this->update($data, array('id' => $id));
            } else {
            throw new \Exception('Form id does not exist');
            }
        }
    }
        public function deleteItem($id)
    {
        return $this->delete(array('id' => $id));
    }

}   