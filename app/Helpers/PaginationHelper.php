<?php
namespace App\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

trait PaginationHelper {
    public function paginateHelper( $collection, $perpage = 10 ) {
        //Get current page form url e.g. &page=6
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        //Create a new Laravel collection from the array data
        $collection = new Collection( $collection );

        //Define how many items we want to be visible in each page
        $perPage = $perpage;

        //Slice the collection to get the items to display in current page
        $currentPageSearchResults = $collection->slice( ( $currentPage - 1 ) * $perPage, $perPage )->all();

        //Create our paginator and pass it to the view
        $paginatedSearchResults = new LengthAwarePaginator( $currentPageSearchResults, count( $collection ), $perPage );

        return $paginatedSearchResults;
    }
}
