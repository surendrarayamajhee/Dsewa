<template>
    <div class="content-body">
        <div class="container-fluid mt-3">
            <div class="row justify-content-center">
                <div class="container">
                    <div class="card">
                        <div class="card-header">
                         <h3 class="pay-inline">Payment List</h3>
                         <div id="filterpayment" class="collapse">
                             <ul class="nav order-nav">
                                 <li class="nav-item">
                                     <label for="">select Account</label>
                                     <select class="form-control">
                                         <option value disabled="disabled">Select</option>
                                         <!--  -->
                                         <!--  -->
                                         <!--  -->
                                         <!--  -->
                                         <!--  -->
                                         <!--  -->
                                         <!--  -->
                                     </select>
                                 </li>
                                 <li class="nav-item">
                                     <label for="">Select Users</label>
                                     <select class="form-control">
                                         <option value disabled="disabled">Select</option>
                                         <!--  -->
                                         <!--  -->
                                         <!--  -->
                                         <!--  -->
                                         <!--  -->
                                         <!--  -->
                                         <!--  -->
                                     </select>
                                 </li>
                                 <li>
                                     <label for="">Search by Sender or Receiver:</label>
                                     <select class="form-control">
                                         <option value disabled="disabled">search</option>
                                         <option value="Sender">By Sender</option>
                                         <option value="Receiver">By Reeciver</option>
                                     </select>
                                 </li>
                                 <li id="btn" class="nav-item">
                                     <button class="btn btn-primary">filter</button>
                                 </li>
                                  <li class="nav-item">
                                     <button class="btn btn-primary">Reset</button>
                                 </li>
                             </ul>
                         </div>
                        </div>
                        <div id="paymentmodel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade bd-example-modal-lg">
                            <div role="document"  class="modal-dialog modal-lg">
                                 <form>
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 id="exampleModalLable" class="modal-title">payment</h5>
                                            <button type="button" data-dismiss="modal" aria-label="Close" class="Close">
                                                <span aria-hidden="true">*</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <label for="">select Account:</label>
                                                                <select class="form-control">
                                                                    <option value disabled="disabled">select</option>
                                                                    <!-- -->
                                                                    <!-- -->
                                                                    <!-- -->
                                                                    <!-- -->
                                                                    <!-- -->
                                                                    <!-- -->
                                                                    <!-- -->
                                                                    <!-- -->
                                                                    <!-- -->
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label for="">select Account:</label>
                                                                <select class="form-control">
                                                                    <option value disabled="disabled">select</option>
                                                                    <!-- -->
                                                                    <!-- -->
                                                                    <!-- -->
                                                                    <!-- -->
                                                                    <!-- -->
                                                                    <!-- -->
                                                                    <!-- -->
                                                                    <!-- -->
                                                                    <!-- -->
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="jtitle">Payment Type</label>
                                                        <br>
                                                        <label class="radio-inline">
                                                            <input type="radio" value="cash" name="optradio" checked="checked">
                                                            "cash
                                                            
                                                                   "
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" value="Bank Transfer">
                                                            "Bank Transfer
                                                            
                                                                   "
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="citizen">Description</label>
                                                        <input type="text" id="citizen" class="form-control">
                                                        <!--  -->
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="citizen">Sender Name</label>
                                                        <input type="text" id="citizen" class="form-control">
                                                        <!--  -->
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="citizen">Receiver Name</label>
                                                        <input type="text" id="citizen" class="form-control">
                                                        <!--  -->
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="damt">payment Amount:</label>
                                                        <input type="number" id="damt" class="form-control">
                                                        <!--  -->
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for>Orders:</label>
                                                        <select multiple="multiple" id="phone-number" name="order_id" class="form-control">
                                                            <option value disabled="disabled">select</option>
                                                        
                                                        <!--  -->
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="udv">Upload Payment Voucher:</label>
                                                        <input type="file" id="udv" class="form-control">
                                                        <!--  -->
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for>Outstanding Payment:</label>
                                                        <input type="number" disabled="disabled" class="form-control">
                                                        <!--  -->
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="date">Date</label>
                                                        <input type="date" id="date" class="form-control">
                                                        <!--  -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>                                  
                                   </div>
                                 </form>
                            </div>
                        </div>
                        <div class="container">
                            <table class="table table-bordered">
                                <thead style="text-align: center;">
                                    <tr>
                                        <th>S.N</th>
                                        <th>Date</th>
                                        <th>Paid By</th>
                                        <th>Payment Method</th>
                                        <th>Receipt#</th>
                                        <th>Received By</th>
                                        <th>Order</th>
                                        <th>Amount</th>
                                        <th>Report</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                           <ul role="menubar" aria-disabled="false" aria-label="Pagination" class="pagination mt-4 b-pagination justify-content-center" pages="">
                            <li role="presentation" aria-hidden="true" class="page-item disabled">
                                <span role="menuitem" aria-label="Go to first page" aria-disabled="true" class="page-link">⏮</span>
                            </li>
                            <li role="presentation" aria-hidden="true" class="page-item disabled">
                                <span role="menuitem" aria-label="Go to previous page" aria-disabled="true" class="page-link">⏪</span>
                            </li>
                            <li role="presentation" class="page-item active">
                                <a role="menuitemradio" aria-label="Go to page 1" aria-checked="true" aria-posinset="1" aria-setsize="1" tabindex="0" target="_self" href="#" class="page-link">1</a>
                                </li>
                            <li role="presentation" aria-hidden="true" class="page-item disabled">
                                <span role="menuitem" aria-label="Go to next page" aria-disabled="true" class="page-link">⏩</span>
                            </li>
                            <li role="presentation" aria-hidden="true" class="page-item disabled">
                                <span role="menuitem" aria-label="Go to last page" aria-disabled="true" class="page-link">⏭</span>
                            </li>
                        </ul>
                  <div id="pickuporder" tabindex="1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-label="true" class="modal fade bd-example-modal-lg">
                      
                  </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </div>
</template>
<style>
.table-bordered{
    border: 1px solid #dee2e6;
}
.table{
    width: 100%;
    margin-bottom: 1rem;
    color:#212529;
}
table{
     border-collapse: collapse;
}
.pay-inline{
    display: inline-block;
    margin-left: 40%;
}
.pagination {
    display: -webkit-box;
    display: flex;
    padding-left: 0;
    list-style: none;
    border-radius: 0.25rem;
    margin: 10px 213px;
}
.justify-content-center {
    -webkit-box-pack: center !important;
    justify-content: center !important;
}
.mt-4, .my-4 {
    margin-top: 1.5rem !important;
}
</style>