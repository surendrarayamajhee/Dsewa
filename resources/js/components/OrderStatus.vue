<template>
    <div class="content-body">
        <div class="container-fluid mt-3">
            <div class="row justify-content-center">
                <div class="container">
                    <div class="card-body">
                        <div class="card-header">
                            <center>
                                <h3>Request Order Status change</h3>
                            </center>
                        </div>
                        <div class="col">
                            <div class="card-body">
                                <form form>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="order-id">Orders</label>
                                            <input name="order_id" class="form-control">
                                            <div></div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="status_id">Status</label>
                                            <select name="status_id" class="form-control">
                                            <option value disabled="disabled">select</option>
                                            </select>
                                        </div>
                                    </div>
                                
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="status_id">product</label>
                                            <select multiple="multiple" name="product_type" class="form-control">
                                                <option value disabled="disabled">Select</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="status_id">Comment</label>
                                            <input type="text" list="comment_id" placeholder="Add comment" class="form-control" style="width:76%;">
                                            <datalist id="comment_id"></datalist>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <button class=" btn btn-success">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </div>
</template>
<style >
.form-group{
    margin-bottom: 1rem;
}
label {
    display: inline-block;
    margin-bottom: 0.5rem;
}
</style>