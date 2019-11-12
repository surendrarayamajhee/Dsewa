<?php
namespace App\Repositories;
abstract class Repository{

    public function all()
    {
        return $this->model->all();
    }
    public  function paginate($perpage =10, $column= array('*'))
    {
        return $this->model::orderBy('id', 'desc')->paginate($perpage,$column);
    }

    public function create(array $data)
    {

        try {

            return $this->model->create($data);

        }
        catch(Exception $e) {
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    public function  find($id)
    {

        try {

            return $this->model->find($id);

        }
        catch(Exception $e) {
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    public function show($id)
    {
        return $this->model->findorfail($id);
    }

    public  function delete($id)
    {

        try {

            $result= $this->model->find($id);
            return $result::destroy($id);
        }


        catch(Exception $e) {
            return redirect()->back()->with('error',$e->getMessage());
        }

    }
    public function update(array $data,$id)
    {
        try {
            $result= $this->model->find($id);
            return $result->update($data);
        }


        catch(Exception $e) {
            return redirect()->back()->with('error',$e->getMessage());
        }


    }
}

?>

