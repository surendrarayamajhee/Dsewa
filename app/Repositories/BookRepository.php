<?php
/**
 * Created by PhpStorm.
 * User: Prime IT Sewa
 * Date: 3/13/2019
 * Time: 1:40 PM
 */
namespace App\Repositories;
use App\Book;
use App\homesection;
use DB;
use App\Tag;
use Illuminate\Support\Facades\File;

class BookRepository extends Repository implements BookInterface
{
    protected $model,$tag;
    public function __construct(Book $model,Tag $tag)
    {
        $this->model = $model;
        $this->tag=$tag;
    }
    public  function delete($id)
    {

        try {

            $result= $this->model->find($id);
            $file = public_path("storage/file/$result->file");

            if (File::exists($file))
            {
                File::delete($file);
            }
            $ifile = public_path("storage/image/$result->Image");
            if (File::exists($ifile))
            {
                File::delete($ifile);
            }
            return $result::destroy($id);
        }


        catch(Exception $e) {
            return redirect()->back()->with('error',$e->getMessage());
        }

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
    public function findbycategory($id)
    {

        try {

            return $this->model->where('main_id','=',$id)->get();
        }

        catch(Exception $e) {
            return redirect()->back()->with('error',$e->getMessage());
        }

    }
    public function search($s)
    {
        return $this->model->Where('Title','like','%' .$s. '%')->orwhere('tag','like','%' .$s. '%')
            ->get();
    }
    public  function getsection()
    {

        $sections = DB::table('homesections')
            ->join('book_homesections', 'book_homesections.homesection_id', '=', 'homesections.id')
            ->get();
        $sections->transform(function ($section, $key) {
            $section->book_id = json_decode($section->book_id);
            return $section;
        });
        return $sections;

    }
    public  function related($id)
    {

        try {

            $book=$this->model->find($id);
            $s_id=$book->subcategory->id;
            // $mi_id=$book->minicategory->id;
            return DB::select('SELECT * FROM books WHERE (sub_id = ? ) and NOT (id = ?)',[$s_id,$id]);
        }

        catch(Exception $e) {
            return redirect()->back();
        }


    }
      public  function conformed($id)
    {
        try {
            $main = $this->model->find($id);
            if($main->active == 0)
            {
                return $main->update([
                    'active' => 1,
                ]);

            }
            else
            {

                return  $main->update([
                    'active'=> 0,
                ]);
            }

        }

        catch(Exception $e) {
            return redirect()->back()->with('error',$e->getMessage());
        }



        }

}
