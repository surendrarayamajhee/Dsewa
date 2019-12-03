<template>
    <div class="content-body" style="min-height:788px;">
        <div class="container-fluid mt-3">
      <div class="row justify-content-center">
          <div class="container">
              <form>
                  <div class="form-group">
                      <label for="name">Name</label>
                      <input type="text" name="name" id="name" placeholder="name" autocomplete="name" class="form-control">
                      <!-- -->
                  </div>
                  <div class="form-group">
                      <label for="email">Email</label>
                      <input type="email" name="email"  placeholder="suren@gmail.com" autocomplete="email" class="form-control">
                      <!-- -->
                  </div>
                  <div class="form-group">
                      <label for="password">Password</label>
                      <input type="password" name="password"  placeholder="**********" autocomplete="password" class="form-control">
                      <!-- -->
                  </div>
                  <div class="form-group">
                      <label for="password_confirmation">Re-type-Password</label>
                      <input type="password" name="password_confirmation"   autocomplete="password" class="form-control">
                      <!-- -->
                  </div>
                  <button class="btn btn-primary">Submit</button>
              </form>
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
.form-control {
    display: block;
    width: 100%;
    height: calc(1.5em + 0.75rem + 2px);
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
.form-group {
    margin-bottom: 1rem;
}
</style>