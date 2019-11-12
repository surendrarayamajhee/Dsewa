<?php
/**
 * Created by PhpStorm.
 * User: Prime IT Sewa
 * Date: 3/13/2019
 * Time: 1:34 PM
 */
namespace App\Repositories;

interface BookInterface{
    public  function all();
    public  function getsection();
    public function create(array $data);
    public function update(array  $data, $id);
    public  function delete($id);
    public function find($id);
    public  function related($id);
    public function show($id);
    public function search($s);
    public function findbycategory($id);
    public  function paginate($perpage =10, $column= array('*'));

}
