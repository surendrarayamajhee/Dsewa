<template>
    <div class="content-body">
        <div class="container-fluid mt-3">
        <div class=" row justufy-content-center">
            <div class=" container">
                <div class="card">
                    <!--  for header  -->
                    <div class="card-header">
                        <h3 class="order-inline">Profile</h3>
                    </div>
                    <!-- for body parts -->
                    <div class="card-body">
                        <form>
                            <div class="form-group-row">
                                <div class="col-md-6">
                                    <label for="name">Name</label>
                                    <input type="text"  class="form-control">
                                </div>
                            </div>
                            <div class="form-group-row">
                                <div class="col-md-6">
                                    <label for="email">Company email</label>
                                    <input type="email" name="email" class="form-control">
                            </div>
                            </div>
                            <div class="form-group-row">
                                <div class="col-md-6">
                                    <label for="Website">company Website</label>
                                    <input type="text" name="email" class="form-control">
                            </div>
                            </div>
                            <div class="form-group-row">
                                <div class="col-md-6">
                                    <label for="phone1">phone number 1</label>
                                    <input type="number" name="phone1" class="form-control">
                            </div>
                            </div>
                            <div class="form-group-row">
                                <div class="col-md-6">
                                    <label for="phone2">phone number 2</label>
                                    <input type="number" name="phone2" class="form-control">
                            </div>
                            </div>
                            <button class="btn btn-success">
                                "Submit
                                        "
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>       
    </div>
    </div>
</template>
<style>
body {
    margin: 0;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    text-align: left;
    background-color: #fff;
}
.justify-content-center {
    justify-content: center !important;
}
.row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
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
.card-header:first-child {
    border-radius: calc(0.25rem - 1px) calc(0.25rem - 1px) 0 0;
}
.card-header {
    padding: 0.75rem 1.25rem;
    margin-bottom: 0;
    background-color: rgba(0, 0, 0, 0.03);
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}
.order-inline {
    display: inline-block;
}
h3, .h3 {
    font-size: 1.75rem;
}
.card-body {
    flex: 1 1 auto;
    padding: 1.25rem;
}
.form-group {
    margin-bottom: 1rem;
}
.col-md-6 {
    flex: 0 0 50%;
    max-width: 50%;
}
.btn-success {
    color: #fff;
    background-color: #28a745;
    border-color: #28a745;
}
</style>