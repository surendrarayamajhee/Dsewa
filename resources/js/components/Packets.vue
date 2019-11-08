<template>
    <div class="content-body">
            <div class="container-fluid mt-3">
                <div class="row justify-content-center">
                            <div class="container" style="width:auto; min-height:700px;">
                                <div class="card-body">
                                    <div class="card-header">
                                     <h2 class="card-title text-black" align="center" >Create New Packets</h2>
                                    </div>
                            
                            <div class="card-body">
                             <form>
                                  <div class="row">
                                     <div class="form-group col-md-6">
                                         <label class="mr-sm-2">Phone-Number</label>
                                             <div dir="auto" class="v-select vs--single vs--searchable">
                                                 <div class="vs__dropdown-toggle">
                                                     <div class="vs__selected-options">
                                                         <input placeholder="select phone no" aria-label="search for option" role="combobox" type="serach" autocomplete="off" class="vs__search">
                                                     </div>
                                                     <div class="vs__actions">
                                                         <button type="button" title="clear selection" class="vs__clear" style="display:none;">
                                                             <svg></svg>
                                                         </button>
                                                         <div class="vs__spinner" style="display:none;">Loading.......</div>
                                                     </div>
                                                </div>
                                                <!--  -->
                                                </div>
                                                <!-- -->
                                                <div class="modal-footer">
                                                        <button class="btn btn-primary" style="border:1px solid black;">
                                                            New Customer
                                                        </button>
                                                    </div>
                                            </div>
                                     <hr>
                                     <h6></h6>
                                     <div class="form-group col-md-6">
                                     <label class="mr-sm-2">Products Discription</label>
                                          <div class="col-lg-6">
                                                <textarea class="form-control h-150px" rows="6" id="comment" placeholder="Discription write here"></textarea>
                                           </div>
                                     </div>
                                     <div class="form-group col-md-6">
                                         <label for="municipality">Handling</label>
                                         <select name="handling" class="form-control" >
                                             <option value disabled="desable">Select</option>
                                                    <option value="NON_FRAGILE" selected="selected">Non-Fragile</option>
                                                    <option value="FRAGILE">Fragile</option>
                                                    

                                         </select>
                                     </div>
                                     <div class="form-group col-md-6">
                                         <label for="mobile2">COD value</label>
                                         <input type="number" placeholder="COD Value" name="cod" min="0" class="form-control">
                                     </div>
                                     <div class="form-group col-md-6">
                                         <label class="mobile2">Unit/Weight (In Kg)</label>
                                         <input type="number" placeholder="Unit/Weight (In Kg)" name="Weight" min="1" class="form-control">
                                     </div>
                                     <div class="form-group col-md-6">
                                         <label class="mobile2">Customer's Expected Delivery Date</label>
                                         <input type="date" class="form-control" name="expected_date">
                                     </div>
                                     <div class="form-group col-md-6">
                                        <label for="mobile2">Product type</label>
                                            <select multiple="multiple" class="form-control" id="sel2">
                                                <option value disabled="desable">Select Products</option>
                                                    <option value="test">test</option>
                                                    <option value="show">show</option>
                                                    <option value="T-Shirt">T-Shirt</option>
                                                    <option value="glass">glass</option>
                                                    <option value="shoes">shoes</option>
                                                    <option value="Document">Document</option>
                                            </select>
                                     </div>
                                     <div class="form-group col-md-6">
                                         <label for="mobile2">Vendor Order ID</label>
                                         <input type="text" placeholder="Vendor Order id" class="form-control">
                                     </div>
                                 </div>
                                 <button class="btn btn-primary btn-load" style="border:1px solid black;">
                                     <span class>Submit</span>
                                 </button>
                            </form>
                            </div>
                            </div> 
                            <!-- <div class="model2">
                                <form>
                                    <div id="new_picup" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" class="modal fade bd-example-modal-lg" style="display:none;" aria-hidden="true">
                                        <div role="document" class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                <h5 id="exampleModalScrollableTitle" class="modal-title">Add  New Customer</h5>
                                                <button type="button" data-dismiss="modal" aria-label="Close" class="close">
                                                    <span aria-hidden="true">*</span>
                                                </button>
                                                </div>    
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="name">First Name</label>
                                                                <input type="text" placeholder="First Name" name="First_Name" class="form-control">
                                                               
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="name">Last Name</label>
                                                                <input type="text" placeholder="Last Name" name="last_Name" class="form-control">
                                                            
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>State</label>
                                                                <select class="form-control">
                                                                    <option value selected="selected" disabled="disabled">Select</option>
                                                                    <option value="532">Gandaki</option>
                                                                    <option >Karnali</option>
                                                                    <option >Province1</option>
                                                                    <option >Province2</option>
                                                                    <option >Province3</option>
                                                                    <option >Province5</option>
                                                                    <option >Sudur Paschim</option>
                                                                </select>
                                                              
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="district">District</label>
                                                                <select id="district" name="district" class="form-control">
                                                                    <option>Select</option>
                                                                    <option >Gandaki</option>
                                                                    <option >Karnali</option>
                                                                    <option >Province1</option>
                                                                    <option >Province2</option>
                                                                    <option >Province3</option>
                                                                    <option >Province5</option>
                                                                    <option >Sudur Paschim</option>
                                                                </select>
                                                               
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="municipality">municipality</label>
                                                                <select id="municipality" name="municipality" class="form-control">
                                                                    <option>Select</option>
                                                                    
                                                                </select>
                                                               
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="municipality">Ward_No</label>
                                                                <select id="tole" name="Ward-No" class="form-control">
                                                                    <option>Select</option>
                                                                    
                                                                </select>
                                                               
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="area">Area</label>
                                                                <div>
                                                                <select id="tole" name="area" class="form-control">
                                                                    <option>Select</option>
                                                                    
                                                                </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="discription">Discription</label>
                                                                <div>
                                                                <input type="text" placeholder="Discription/state" name="discription" class="form-control">
                                                              
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="mobile">Mobile Number</label>
                                                                <div>
                                                                <input type="number" placeholder="Mobile Number" name="phone1" class="form-control">
                                                               
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                 <label for="mobile2">Mobile Number 2</label>
                                                                <div>
                                                                <input type="number" placeholder="Mobile Number 2" name="phone2" class="form-control">
                                                              
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
                                                        <button class="btn btn-primary">
                                                            "Submit"
                                                        </button>
                                                    </div>   
                                                  </div>
                                                </div>
                                            </div>
                                        </div>
                                     </form> -->
                    </div>
                </div>
            </div>
        </div>
    </template>
<style> 
.justify-content-center {
    justify-content: center !important;
    width: auto;
    min-height: 700px;   
}
 .v-select , .v-select *{
     box-sizing: border-box;
     position: relative;
     font-family: inherit;
 }
 .vs__dropdown-toggle {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    display: flex;
    padding: 0 0 4px;
    background: none;
    border: 1px solid rgba(60,60,60,.26);
    border-radius: 4px;
    white-space: normal;
}
 .vs--searchable .vs__dropdown-toggle{
cursor:text;
 }
 .v-select, .v-select * {
    box-sizing: border-box;
}

 .vs__search, .vs__search:focus {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    line-height: 1.4;
    font-size: 1em;
    border: 1px solid transparent;
    border-left: none;
    outline: none;
    margin: 4px 0 0;
    padding: 0 7px;
    background: none;
    box-shadow: none;
    width: 0;
    max-width: 100%;
    flex-grow: 1;
}
.v-btn:not(.v-btn--round).v-size--large {
    height: 44px;
    min-width: 78px;
    padding: 0 19.5555555556px;
}
.v-application .elevation-8 {
    box-shadow: 0px 5px 5px -3px rgba(0, 0, 0, 0.2), 0px 8px 10px 1px rgba(0, 0, 0, 0.14), 0px 3px 14px 2px rgba(0, 0, 0, 0.12) !important;
}
.v-btn:not(.v-btn--disabled) {
    will-change: box-shadow;
}
.v-btn.v-size--large {
    font-size: 0.875rem;
}
.v-btn__content {
    align-items: center;
    color: inherit;
    display: flex;
    flex: 1 0 auto;
    justify-content: inherit;
    line-height: normal;
    position: relative;
}
 [type="search"]{
     outline-offset: -2px;
 }
 .vs__selected-options {
    display: flex;
    flex-basis: 100%;
    flex-grow: 1;
    flex-wrap: wrap;
    padding: 0 2px;
    position: relative;
}
 .col-lg-6{
     position: relative;
     width: 100%;
     padding-right: 15px;
     padding-left: 15px;
     flex: 0 0 50%;
     max-width: 50%;
 }
 .v-select, .v-select * {
    box-sizing: border-box;
}
.v-select {
    position: relative;
    font-family: inherit;
}
.btn-primary:not(:disabled):not(.disabled):active, .btn-primary:not(:disabled):not(.disabled).active, .show > .btn-primary.dropdown-toggle {
    color: #fff;
    background-color: #0062cc;
    border-color: #005cbf;
}
.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(0, 0, 0, 0.125);
    border-radius: 0.25rem;
}
.vs__actions {
    display: flex;
    align-items: center;
    padding: 4px 6px 0 3px;
}
.vs__open-indicator {
    fill: rgba(60,60,60,.5);
    transform: scale(1);
    transition: transform .15s cubic-bezier(1,-.115,.975,.855);
    transition-timing-function: cubic-bezier(1,-.115,.975,.855);
}
svg {
    overflow: hidden;
    vertical-align: middle;
}
button, input {
    overflow: visible;
}
input {
    text-rendering: auto;
    color: initial;
    letter-spacing: normal;
    word-spacing: normal;
    text-transform: none;
    text-indent: 0px;
    text-shadow: none;
    display: inline-block;
    text-align: start;
    -webkit-appearance: textfield;
    background-color: white;
    -webkit-rtl-ordering: logical;
    cursor: text;
    margin: 0em;
    font: 400 13.3333px Arial;
    padding: 1px 0px;
    border-width: 2px;
    border-style: inset;
    border-color: initial;
    border-image: initial;
}
:root {
    --blue: #007bff;
    --indigo: #6610f2;
    --purple: #6f42c1;
    --pink: #e83e8c;
    --red: #dc3545;
    --orange: #fd7e14;
    --yellow: #ffc107;
    --green: #28a745;
    --teal: #20c997;
    --cyan: #17a2b8;
    --white: #fff;
    --gray: #6c757d;
    --gray-dark: #343a40;
    --primary: #007bff;
    --secondary: #6c757d;
    --success: #28a745;
    --info: #17a2b8;
    --warning: #ffc107;
    --danger: #dc3545;
    --light: #f8f9fa;
    --dark: #343a40;
    --breakpoint-xs: 0;
    --breakpoint-sm: 576px;
    --breakpoint-md: 768px;
    --breakpoint-lg: 992px;
    --breakpoint-xl: 1200px;
    --font-family-sans-serif: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
    --font-family-monospace: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
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
    border: 1px solid black;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: 0.25rem;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
</style>