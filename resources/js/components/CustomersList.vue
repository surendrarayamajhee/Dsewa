<template>
    <div class="content-body">
        <div class="container-fluid mt-3">
          
                <div class="container">
                    <div class="card-body">
                        <div class="card-header">
                            <button type="button" data-toggle="modal2" class="btn btn-primary new-user-btn btn-sm" style="float:left; margin-top:0px;">New Customer</button>
                            <h3 class="user-inline">Customer List</h3>
                            <button type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" class="btn btn-primary user-search-btn" style="float: right; margin-top: -38px;">Search</button>
                            <div id="collapseExample" class="collapse">
                                <ul class="nav address-nav">
                                    <li class="nav-item">
                                        <div class="form-group">
                                            <label for="district">District</label>
                                            <select name="district" class="form-control">
                                                <option value selected="selected" disabled="disabled">select</option>
                                                <option value="981">Achham</option>
                                                <option value="669">Arghakhachi</option>
                                                <option value="620">Baglung</option>
                                                <option value="952">Baitadi</option>
                                                <option value="929">Bajhang</option>
                                                <option value="919">Bhajura</option>
                                                <option value="732">Banke</option>
                                                <option value="325">Bara</option>
                                                <option value="741">Bardiya</option>
                                                <option value="465">Bhaktapur</option>
                                                <option value="84">bhojpur</option>
                                                <option value="523">Chitwan</option>

                                            </select>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <div class="form-group">
                                        <label for="municipality">
                                            <span class></span>
                                            "Municipality"
                                            
                                        </label>
                                        <select id="municipality" name="municipality" class="form-control" style="width;19px;">
                                            <option value selected="selected" disabled="disabled">select</option>
                                        </select>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <div class="form-group">
                                            <label for="municipality">ward No.</label>
                                            <select id="tole" name="ward_no" class="form-control">
                                                <option value>select</option>
                                            </select>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <div class="form-group">
                                         <label for="area">Area</label>
                                         <div>
                                         <select id="tole" name="area" class="form-control">
                                             <option value>select</option>
                                         </select>    
                                        </div>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <div class="form-group">
                                            <label for="paginate">paginate</label>
                                            <select type="number" placeholder="pagination" name="paginate" class="form-control">
                                                <option value selected="selected" disabled="disabled">paginate</option>
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                
                                            </select>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <div class="form-group">
                                            <label for="phone">Phone Number</label>
                                            <input type="number" name="phone" class="form-control"> 
                                        </div>

                                    </li>
                                    <li class="nav-item">
                                        <button class="btn btn-parimary search-btn">filter</button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="btn btn-primary reset-btn">Reset</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row">
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>First_name</th>
                                            <th>Last_name</th>
                                            <th>State</th>
                                            <th>District</th>
                                            <th>Municipality</th>
                                            <th>Ward_No</th>
                                            <th>Area</th>
                                            <th>mobile1</th>
                                            <th>mobile2</th>
                                            <th>Discription</th>
                                            <th style="width: 8%;">Edit</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                <ul role="menubar" aria-disabled="false" aria-label="pagination" class=" pagination mt-4 b-pagination justify-content-center" pages>
                      <li role="none presentation" aria-hidden="true" class="page-item disabled">
                          <span role="menubar" aria-label="Go to first page" aria-disabled="true" class="page-link">First-page</span>
                      </li>
                      <li role="none presentation" aria-hidden="true" class="page-item disabled">
                          <span role="menubar" aria-label="Go to previous page" aria-disabled="true" class="page-link">prev</span>
                      </li>
                      <li role="none presentation" class="page-item">
                          <span role="menubar" aria-label="Go to page 1" aria-checked="false" aria-posinset="1" aria-setsize="1" tabindex="0" target="_self" href="#" class="page-link">page no.1</span>
                      </li>
                       <li role="none presentation" aria-hidden="true" class="page-item disabled">
                          <span role="menubar" aria-label="Go to next page" aria-disabled="true" class="page-link">next</span>
                      </li>
                       <li role="none presentation" aria-hidden="true" class="page-item disabled">
                          <span role="menubar" aria-label="Go to last page" aria-disabled="true" class="page-link">last-page</span>
                      </li>
                  </ul>
                  <div id="pickuporder" tabindex="1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-label="true" class="modal fade bd-example-modal-lg">
                      
                  </div>
                 </div>
                 <div class="mode12">
                     <form>
                         <div id="useraddress" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true" class="modal fade bd-example-modal lg">
                             <div role="document" class="modal-dialog modal-lg">
                                 <div class="modal-content">
                                     <div class="modal-header">
                                         <h5 id="exampleModalScrollableTitle" class="modal-tilte">Add customer</h5>
                                         <button type="button" data-dismiss="modal" aria-label="Close" class="close">
                                             <span aria-hidden="true">*</span>
                                         </button>
                                     </div>
                                     <div class="modal-body">
                                         <div class="row">
                                             <div class="col-md-6">
                                                 <div class="form-control">
                                                    <label for="name">fisrt Name</label>
                                                    <input type="text" placeholder="first_name" class="form-control">
                                                    <!-- -->
                                                 </div>
                                             </div>
                                             <div class="col-md-6">
                                                 <div class="form-control">
                                                    <label for="name">second Name</label>
                                                    <input type="text" placeholder="second_name" class="form-control">
                                                    <!-- -->
                                                 </div>
                                             </div>
                                             <div class="form-group">
                                                <label for="district">District</label>
                                                <select id="district" name="district" class="form-control">
                                                    <option value>select</option>
                                                    <option value="981">Achham</option>
                                                    <option value="669">Arghakhachi</option>
                                                <option value="620">Baglung</option>
                                                <option value="952">Baitadi</option>
                                                <option value="929">Bajhang</option>
                                                <option value="919">Bhajura</option>
                                                <option value="732">Banke</option>
                                                <option value="325">Bara</option>
                                                <option value="741">Bardiya</option>
                                                <option value="465">Bhaktapur</option>
                                                <option value="84">bhojpur</option>
                                                <option value="523">Chitwan</option>
                                                </select>
                                             </div>
                                             <div class="col-md-4"></div>
                                             <div class="col-md-4">
                                                 <div class="form-group">
                                                     <label for="municipalirty">Municipality</label>
                                                     <select id="municipality" name="municipality" class="form-control">
                                                        <option value>select</option>
                                                     </select>
                                                 </div>
                                             </div>
                                             <div class="col-md-4">
                                                 <div class="form-group">
                                                     <label for="municipality">Ward_No</label>
                                                     <select id="tole" name="ward_no" class="form-control">
                                                         <option disabled="disabled" value>select</option>
                                                     </select>
                                                 </div>
                                             </div>
                                             <div class="col-md-6">
                                                 <div class="form-group">
                                                     <label for="area">Area</label>
                                                     <div>
                                                         <select id="tole" name="area" class="form-control">
                                                             <option value>select</option>
                                                         </select>
                                                     </div>
                                                 </div>
                                             </div>
                                             <div class="col-md-6">
                                                 <div class="form-group">
                                                     <label for="description">Description</label>
                                                 <div>
                                                     <input type="text" placeholder="Description/Street" name="description" class="forom-control">
                                                     <!--  -->
                                                 </div>
                                                 </div>
                                             </div>
                                             <div class="col-md-6">
                                                 <div class="form-group">
                                                     <label for="mobile2">Mobile Number2</label>
                                                     <div>
                                                         <input type="number" placeholder="mobile number2" name="phone2" class="form-control">
                                                     </div>
                                                 </div>
                                             </div>
                                         </div>
                                         <div class="modal-footer">
                                             <button type="button" data-dismiss="modal" class="btn btn-secondary">close</button>
                                             <button class="btn btn-primary">
                                                 <span class></span>
                                                 "
                                                 submit
                                                           "
                                             </button>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </form>
                 </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</template>
<style scoped>
.card-header:first-child{
    border-radius: calc(0.25rem-1px) 
    calc(0.25rem-1px) 0 0p;
}
.card-header{
    padding: 0.75rem;
    margin-bottom: 0;
    background-color:rgba(0,0,0,0.03);
    border-bottom: 1px solid rgba(0,0,0,0.125);
}
.btn-sm{
padding: 0.25rem 0.5rem;
font-size: 0.875rem;
line-height: 1.5;
border-radius: 0.2rem;
}
.btn-primary{
color:#fff;
background-color:#007bff;
border-color: #007bff;
}
.btn {
    display: inline-block;
    font-weight: 400;
    color: #f8f9fa;
    
    text-align: center;
    vertical-align: middle;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-color: transparent;
    border: 1px solid transparent;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: 0.25rem;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
.btn[data-v-174508b8] {
    display: inline-block;
    font-weight: 400;
    color: #f8f9fa;
    background-color: #0062cc;
    text-align: center;
    vertical-align: middle;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-color: #076ede;
    border: 1px solid transparent;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: 0.25rem;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
</style>