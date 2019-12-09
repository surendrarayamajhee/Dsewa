<template>
<div class="content-body" style="min-height:788px;">
    <div class="container-fluid mt-3">
    <div class="row judtify-content-center">
        <div class="container">
            <div class="card-body">
                <div class="card-header">
                    <center>
                        <h3>Status change Log</h3>
                    </center>
                </div>  
                <!-- <div class="col-md-12">
                    <div class="card-body" align="center">
                        <table class="table table-bordered col-md-6 table-style-vendor-status">
                            <thead>
                                <tr>
                                    <th class="__web-inspecttor-hide-shortcut__"> S.N </th>
                                    <th>Order Id</th>
                                    <th>product</th>
                                    <th>Status</th>
                                    <th>Comment</th>
                                    <th>Action</th>
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
                </div> -->
                <br>
                <br>
                <div class="row">
                     <div class="col-sm-12">
                        <table class="table table-striped table-bordered zero-configuration">
                            <thead>
                                  <tr>
                                     <th>SN</th>
                                     <th style="width:20%">Order Id</th>
                                     <th style="width:30%">Product</th>
                                     <th>Status</th>
                                     <th>Comment</th>
                                     <th>Action</th>
                                    </tr>
                             </thead>
                                 <tbody>
                                      <tr v-for="(request,i) in request_status" :key="request.id">
                                            <td>
                                            {{ ++ i}}
                                            <div v-if="is_admin || is_hub">
                                                <input
                                                v-if="request.request_status"
                                                type="checkbox"
                                                v-model="check.checkbox"
                                                :value="request.id"
                                                number
                                                />
                                            </div>
                                            </td>
                                            <td>{{ request.order_id}}</td>
                                            <td>
                                            <ol>
                                                <li v-for="order in request.product_type" :key="order">{{ order }}</li>
                                            </ol>
                                            </td>

                                            <td>{{ request.status }}</td>
                                            <td>{{ request.comment }}</td>
                                            <td>
                                            <!-- <div v-if="is_admin || is_hub   ">
                                                <div v-if="request.request_status==false ">
                                                <a class="pointer" @click="editorderrequest(request)">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                </div>
                                            </div>-->
                                            <a class="pointer" @click="orderDelete(request.id)">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            </td>
                                        </tr>                        
                           </tbody>
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
                     </div>
                        <div
                            class="modal fade bd-example-modal-lg"
                            id="status-update"
                            tabindex="-1"
                            role="dialog"
                            aria-labelledby="exampleModalScrollableTitle"
                            aria-hidden="true"
                            >
                        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <form form @submit.prevent="changeStatus">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalScrollableTitle">Modal title</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <div class="modal-body">
                  <div class="col-md-10">
                    <div class="form-group">
                      <label for="order_id">Orders</label>
                      <input class="form-control" name="order_id" v-model="status.order_id" />

                      <span
                        class="help is-danger"
                        v-if="status.errors.has('order_id' )"
                        v-text="status.errors.get('order_id' )"
                      ></span>
                    </div>
                  </div>
                  <div class="col-md-10">
                    <div class="form-group">
                      <label for="status_id">Status</label>
                      <select class="form-control" name="status_id" v-model="status.status_id">
                        <option value disabled>Select</option>
                        <option
                          v-for="status in OrderStatus"
                          :key="status.id"
                          :value="status.id"
                        >{{ status.name }}</option>
                      </select>
                      <span
                        class="help is-danger"
                        v-if="status.errors.has('status_id' )"
                        v-text="status.errors.get('status_id' )"
                      ></span>
                    </div>
                  </div>
                  <div class="col-md-10">
                    <div class="form-group">
                      <label for="comment_id">Comment</label>
                      <select class="form-control" name="comment_id" v-model="status.comment_id">
                        <option value disabled>Select</option>
                        <option
                          v-for="comment in comments"
                          :key="comment.id"
                          :value="comment.id"
                        >{{ comment.name }}</option>
                      </select>
                      <!-- <v-select
                    :options="comments"
                    v-model="status.comment_id"
                    label="name"
                    :reducer="comment => comment.id"
                    placeholder="select comment"
                      ></v-select>-->

                      <span
                        class="help is-danger"
                        v-if="status.errors.has('comment_id' )"
                        v-text="status.errors.get('comment_id' )"
                      ></span>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button class="btn btn-secondary">Submit</button>
                </div>
              </form>
            </div>
          </div>    
                        </div>
                  </div>
                </div>
            </div>    
        </div>            
    </div>
</div>
</template>
<style scoped>
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
      status: new Form({
        order_id: [],
        status_id: "",
        comment_id: ""
      }),
      orders: [],
      comments: [],
      request_status: [],
      OrderStatus: {},
      order_id: [],
      check: new Form({
        checkbox: []
      }),
      pagination: [],
      is_admin: "",
      is_hub: "",
      sdb: "dvfd",
      currentpage: 1
    };
  },
  created() {
    this.getorderstatuschange();
    this.isadmin();
  },
  computed: {
    selectAll: {
      get: function() {
        return this.request_status
          ? this.check.checkbox.length == this.request_status.length
          : false;
      },
      set: function(value) {
        var checkbox = [];
        if (value) {
          this.request_status.forEach(function(request) {
            if (request.request_status == 0) {
              checkbox.push(request.id);
            }
          });
        }
        this.check.checkbox = checkbox;
      }
    }
  },
  methods: {
    fetchall() {
      this.getOrder();
      this.getOrderStatus();
      this.getComment();
    },
    getOrder() {
      axios.get("/api/getorder").then(({ data }) => {
        this.orders = data;
      });
    },

    getComment() {
      axios.get("/api/getcomment").then(({ data }) => {
        this.comments = data;
      });
    },
    getOrderStatus() {
      axios.get("/api/get-order-ststus").then(({ data }) => {
        this.OrderStatus = data;
      });
    },
    getorderstatuschange(page) {
      if (typeof page == undefined) {
        page = 1;
      }
    //   this.currentpage = page;

      axios
        .get("/api/get-vendor-change-status-order", {
          params: { page: page, search: this.search }
        })
        .then(res => {
          this.request_status = res.data.data;
          //   console.log(res.data.orderrequest.data)

          this.pagination = res.data;
        });
    },
    orderDelete(id) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then(result => {
        if (result.value) {
          axios
            .delete("/api/request-order-drop/" + id)
            .then(res => {
              if (res.status == 200) {
                Swal.fire("Deleted!", res.status.success, "success");
                this.getorderstatuschange();
              }
              if (res.data.error) {
                this.error(res.data.error);
              }
            })
            .catch(err => {
              this.error(res);
            });
        }
      });
    },
    editorderrequest(request) {
      $("#status-update").modal("show");
      this.fetchall();
      this.status.fill(request);
      this.order_id = request.text;
    },
    changeStatus() {
      this.status
        .post("/api/changestatus")
        .then(res => {
          if (res.status == 200) {
            this.success(res.data.success);
            this.status.reset();
            this.status.order_id = null;
            // this.getOrder();
          }
          if (res.data.error) {
            this.error(res.data.error);
          }
        })
        .catch(err => {
          this.error(res);
        });
    },
    newReturnedOrder() {
      this.check
        .post("/api/new-returned-order")
        .then(res => {
          if (res.status == 200) {
            this.success(res.data.success);
            this.check.reset();
            this.getorderstatuschange();
          }
          if (res.data.error) {
            this.error(res.data.error);
          }
        })
        .catch(err => {
          this.error(res);
        });
    },
    isadmin() {
      axios.get("api/get-is-admin").then(res => {
        this.is_admin = res.data.is_admin;
        this.is_hub = res.data.is_hub;
      });
    },
    success(success) {
      Toast.fire({
        type: "success",
        title: success
      });
    },
    error(error) {
      Swal.fire({
        position: "center",
        type: "error",
        title: error,
        showConfirmButton: false,
        timer: 1500
      });
    }
  }
};
</script>