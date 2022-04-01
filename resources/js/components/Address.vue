<template>
    <div class="content-body" style="min-height:788px;">
        <div class="row justify-content-center">
            <div class="container" style="margin-top:4%;">
                <button type="button" data-toggle="modal" class="btn btn-primary vendor-address-btn" style="margin-left:1%;">Add Address</button>
                <div id="pickupaddress" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true" class="modal fade bd-example-modal-lg">
                   <div role="document" class="modal-dialog modal-lg">
                       <div class="modal-content">
                           <div class="modal-header">
                               <h5 id="exampleModalScrollableTitle" class="modal-title">Modal Title</h5>
                               <button type="button" data-dismiss="modal" aria-label="Close" class="close">
                                   <span aria-hidden="true">*</span>
                               </button>
                           </div>
                           <form>
                               <div class="modal-body">
                                   <div class="row">
                                       <div class="col-md-6">
                                           <div class="form-group">
                                               <label for="state">State</label>
                                               <select name="state_id" class="form-control">
                                                   <option value selected="selected" disabled="disabled">Select</option>
                                                   <option value="532">Gandaki</option>
                                                   <option value="7">Karnali</option>
                                                   <option value="2">Province1</option>
                                                   <option value="3">Province2</option>
                                                   <option value="4">Province3</option>
                                                   <option value="6">Province5</option>
                                                   <option value="8">Sudur Paschim</option>
                                               </select>
                                               <!-- -->
                                           </div>
                                       </div>
                                       <div class="col-md-6">
                                           <div class="form-group">
                                               <label for="district">District</label>
                                               <select name="district_id" class="form-control">
                                                <option value selected="selected">Select</option>      
                                               </select>
                                           </div>
                                       </div>
                                       <div class="col-md-6">
                                           <div class="form-group">
                                               <label for="municipality">Municipality</label>
                                               <select name="municipality_id" class="form-control">
                                                <option value selected="selected">Select</option>      
                                               </select>
                                           </div>
                                       </div>
                                       <div class="col-md-6">
                                           <div class="form-group">
                                               <label for="municipality">No</label>
                                               <select name="ward_id" class="form-control">
                                                <option value selected="selected">Select</option>      
                                               </select>
                                           </div>
                                       </div>
                                       <div class="col-md-6">
                                           <div class="form-group">
                                               <label for="area">Area</label>
                                               <div>
                                                   <select name="area_id" class="form-control">
                                                <option value selected="selected">Select</option>      
                                               </select>
                                               </div>
                                           </div>
                                       </div>
                                       <div class="col-md-6">
                                           <div class="form-group">
                                               <label for>Description</label>
                                               <textarea type="text" placeholder="Description" class="form-control"></textarea>
                                               <!--  -->
                                           </div>
                                       </div>
                                   </div>
                                   <br>
                               </div>
                               <div class="modal-footer">
                                   <button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
                                   <button class="btn btn-primary">
                                       <span class>
                                           "Submit"
                                       </span>
                                   </button>
                               </div>
                           </form>
                       </div>
                   </div>
                </div>
                <div class="container">
                    <h2>Your Address</h2>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                <th>State</th>
                                <th>District</th>
                                <th>Municipality</th>
                                <th>Ward</th>
                                <th>Area</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    "1
                                    "
                                    <input type="checkbox" value="16">
                                </td>
                                <td>Province3</td>
                                <td>Kathmandu</td>
                                <td>Kathmandu</td>
                                <td>Ward #29</td>
                                <td></td>
                                <td>swapnita sapkota</td>
                                <td>
                                    <a>
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    "2
                                    "
                                    <input type="checkbox" value="16">
                                </td>
                                <td>Province3</td>
                                <td>Kathmandu</td>
                                <td>Kathmandu</td>
                                <td>Ward #3</td>
                                <td></td>
                                <td>swapnita sapkota</td>
                                <td>
                                    <a>
                                        <i class="fas fa-pencil-alt">
                                            
                                        </i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.justify-content-center {
    justify-content: center !important;
}
.row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
}
.container {
    max-width: 95%;
}
.btn-primary {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}
.btn {
    display: inline-block;
    font-weight: 400;
    color: #212529;
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
.fade:not(.show) {
    opacity: 0;
}
.modal {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1050;
    display: none;
    width: 100%;
    height: 100%;
    overflow: hidden;
    outline: 0;
}
.fade {
    transition: opacity 0.15s linear;
}
.modal.fade .modal-dialog {
    transition: transform 0.3s ease-out;
    transform: translate(0, -50px);
}
.modal-lg {
    max-width: 1200px;
}
.modal-content {
    position: relative;
    display: flex;
    flex-direction: column;
    width: 100%;
    pointer-events: auto;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid rgba(0, 0, 0, 0.2);
    border-radius: 0.3rem;
    outline: 0;
}
.modal-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    padding: 1rem 1rem;
    border-bottom: 1px solid #dee2e6;
    border-top-left-radius: 0.3rem;
    border-top-right-radius: 0.3rem;
}
.modal-title {
    margin-bottom: 0;
    line-height: 1.5;
}
.modal-header .close {
    padding: 1rem 1rem;
    margin: -1rem -1rem -1rem auto;
}
.modal-body {
    position: relative;
    flex: 1 1 auto;
    padding: 1rem;
}
.row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
}
.col-md-6 {
    flex: 0 0 50%;
    max-width: 50%;
}
.form-group {
    margin-bottom: 1rem;
}
.modal-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding: 1rem;
    border-top: 1px solid #dee2e6;
    border-bottom-right-radius: 0.3rem;
    border-bottom-left-radius: 0.3rem;
}
.modal-footer > :not(:last-child) {
    margin-right: .25rem;
}
.container {
    max-width: 95%;
}
.table-bordered {
    border: 1px solid #dee2e6;
}
.table {
    width: 100%;
    margin-bottom: 1rem;
    color: #212529;
}
.fa, .fas {
    font-weight: 900;
}
.fa, .far, .fas {
    font-family: "Font Awesome 5 Free";
}
.fa, .fab, .fal, .far, .fas {
    -moz-osx-font-smoothing: grayscale;
    -webkit-font-smoothing: antialiased;
    display: inline-block;
    font-style: normal;
    font-variant: normal;
    text-rendering: auto;
    line-height: 1;
}
</style>