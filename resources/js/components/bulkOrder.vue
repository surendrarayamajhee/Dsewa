<template>
<div class="content-body" style="min-height:788px;">
    <div class="container-fluid mt-3">
    <div class="row judtify-content-center">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <center>
                        <h3>Bulk Order Upload</h3>
                    </center>
                </div>  
                
                <div class="card-body">
                    <div class="modal fade" id="myModal" role="dialog">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <form @submit.prevent="postBulkOrders" enctype="multipart/form-data">
                                    <div class="modal-header">
                                    <h4 class="modal-title">Customer Bulk Orders</h4>
                                    </div>
                                    <div class="modal-body">
                                    <div class="form-group">
                                        <label>Select File</label>
                                        <input
                                        accept=".xlsx"
                                        type="file"
                                        name="orders"
                                        class="form-control"
                                        required
                                        @change="onImageChange"
                                        />
                                    </div>
                                    </div>
                                    <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary btn-sm" :disabled="loading">
                                        <span :class="{ 'spinner-border spinner-border-sm': loading }"></span>Submit
                                    </button>
                                    <button type="reset" class="btn btn-warning btn-sm" @click="reset">Reset</button>
                                    </div>
                                </form>
                                </div>
                        </div>
                    </div>
                </div>
                
                   <button class="btn btn-success" style="width:11%;margin-left:1%;margin-top:-3%;" @click="show">Upload File</button>
                    <table class="table table-bordered" style="margin-top:1%;width:98%;margin-left:1%;">
                    <thead>
                        <tr>
                        <th>Code</th>
                        <th>Date</th>
                        <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(blukOrder,i) in bulkOrders" :key="blukOrder.id">
                        <td>{{ blukOrder.code }}</td>
                        <td>{{ blukOrder.created_at }}</td>
                        <td>
                            {{ blukOrder.status }}
                            <button
                            class="btn btn-primary"
                            v-if="blukOrder.status == 'PENDING'"
                            @click="store(blukOrder.id)"
                            >Add</button>
                            <a class="fas fa-trash-alt pointer" @click="dropBulkFile(blukOrder.id,i)"></a>
                        </td>
                        </tr>
                    </tbody>
                    </table>

                </div>
         </div>    
        </div> 
        <pagination :data="pagination" @pagination-change-page="listBulkOrder"></pagination>           
    </div>
</div>
</template>
<style scoped>
.con
.col-md-12{
flex: 0 0 100%;
max-width: 100%;
}
.container{
    max-width: 95%;
}
.table-bordered{
    border: 1px solid #dee2e6;
}
.table{
    width: 100%;
    margin-bottom: 1rem;
    color: #212529;
}
.col-md-6{
    flex: 0 0 50%;
    width: 50%;
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

<script>
export default {
  data() {
    return {
      image: {},
      bulkOrders: [],
      user_id: "",
      pagination: {},
      paginate: null,
      loading: false
    };
  },
  mounted() {
    this.listBulkOrder();
  },

  methods: {
    onImageChange(e) {
      this.image = e.target.files[0];
    },
    show() {
      $("#myModal").modal("show");
    },

    postBulkOrders() {
      this.loading = true;

      let formData = new FormData();
      formData.append("image", this.image);
      axios
        .post("/api/vendor/bulkOrder/store", formData)
        .then(res => {
          this.onSuccess.bind(this);
          this.listBulkOrder();
          this.image = {};
          this.loading = false;
          $("#myModal").modal("hide");
        })
        .catch(error => {
          this.loading = false;
        });
    },
    reset() {
      this.image = {};
    },
    listBulkOrder(page) {
      if (typeof page === undefined) {
        page = 1;
      }
      axios
        .get("/api/vendor/bulkOrder/list/", {
          params: { page: page }
        })
        .then(response => {
          this.bulkOrders = response.data.data;
          this.pagination = response.data;
        })
        .catch(response => {});
    },
    store(id) {
      axios
        .post("/api/vendor/bulkOrder/create", {
          id: id
        })
        .then(res => {
          if (res.status == 200) {
            this.success(res.data.success);
            this.$router.push({ name: "bulk-order-list" });
          }
        })
        .catch(error => {
          this.error(
            "error !!! There Might Be Some Problem with Your Excel File, Please Check and Reupload"
          );
        });
    },

    dropBulkFile(id, i) {
      swalWithBootstrapButtons
        .fire({
          title: "Are you sure?",
          text: "You won't be able to revert this!",
          type: "warning",
          showCancelButton: true,
          confirmButtonText: "Yes, delete it!",
          cancelButtonText: "No, cancel!",
          reverseButtons: true
        })
        .then(result => {
          if (result.value) {
            axios
              .delete("api/bulk/delete/" + id)
              .then(res => {
                if (res.status == 200) {
                  this.$delete(this.bulkOrders, i);
                }
                if (res.data.error) {
                  this.error(res.data.error);
                }
              })
              .catch(res => {
                this.error(res.data.error);
              });
          } else if (
            // Read more about handling dismissals
            result.dismiss === Swal.DismissReason.cancel
          ) {
            swalWithBootstrapButtons.fire(
              "Cancelled",
              "Your imaginary file is safe :)",
              "error"
            );
          }
        });
    },
    error(error) {
      Swal.fire({
        position: "center",
        type: "error",
        title: error,
        showConfirmButton: true,
        timer: 5000
      });
    },
    success(success) {
      Toast.fire({
        type: "success",
        title: success
      });
      // Try me!
    },
    onSuccess(response) {
      $("#myModal").modal("hide");
      this.image = {}; //Clear input fields.
      this.listBulkOrder();
    }
  }
};
</script>


