<template>
    <div class="model2">
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
                                     </form>
</template>