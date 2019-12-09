<template>
<div class="content-body" style="min-height:788px;">
    <div class="row justify-content-center">
        <div class="row" style="width: 97%;">
                <div class="card" style="width: 100%;">
                    <div class="card-header">
                       <h3 class="card-inline">Order List</h3>
                          <div class="table-responsive">
                             <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                                <div class="row">
                                   <div class="col-sm-12 col-md-6">
                                      <div class="datatables_tables-0_Length">
                                         <label>
                                          Show 
                                            <select name="datatables_tables_0_Length" aria-controls="datatables_tables_0" class="form-control form-control-sm">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            </select> 
                                         </label>
                                        </div>
                                    </div>

                                      <button
                                          class="btn btn-primary search-btn"
                                          type="button"
                                          data-toggle="collapse"
                                          data-target="#collapseExample"
                                          aria-expanded="false"
                                          style="float: right;margin-top: 0px;margin-left:42%;height:1%;"
                                          aria-controls="collapseExample"
                                        >Search</button>
                                        <div class="collapse" id="collapseExample">
                                            <form @submit.prevent="filter">
                                              <ul class="nav order-nav">
                                                <li class="nav-item">
                                                  <label for="order_description">Order Id</label>
                                                  <input
                                                    class="form-control"
                                                    autofocus
                                                    type="text"
                                                    placeholder="Order Id"
                                                    v-model="search"
                                                  />
                                                </li>
                                                <li class="nav-item">
                                                  <label for="order_description">Status</label>
                                                  <select class="form-control" name="status_id" v-model="status">
                                                    <option value disabled>Select</option>
                                                    <option
                                                      v-for="status in OrderStatus"
                                                      :key="status.id"
                                                      :value="status.id"
                                                    >{{ status.name }}</option>
                                                  </select>
                                                </li>
                                                <li class="nav-item" v-if="role=='admin'|| role== 'hub'">
                                            <label for="order_description">Vendor</label>

                                            <select class="form-control" name="status_id" v-model="vendor">
                                              <option value disabled>Select</option>
                                              <option
                                                v-for="vendor in vendors"
                                                :key="vendor.id"
                                                :value="vendor.id"
                                              >{{ vendor.name }}</option>
                                            </select>
                                          </li>
                                          <li class="nav-item" v-if="role=='admin'|| role== 'hub'">
                                            <label for="order_description">Hub</label>
                                            <select class="form-control" name="status_id" v-model="hub">
                                              <option value disabled>Select</option>
                                              <option v-for="hub in hubs" :key="hub.id" :value="hub.id">{{ hub.name }}</option>
                                            </select>
                                          </li>
                                          <li class="nav-item">
                                            <label for="order_description">Customer</label>
                                            <input class="form-control" type="text" placeholder="Customer" v-model="customer" />
                                          </li>

                                          <li class="nav-item">
                                            <label for="order_description">From Date</label>
                                            <input class="form-control" type="date" placeholder="To Date" v-model="to" />
                                          </li>
                                          <li class="nav-item">
                                            <label for="order_description">To Date</label>
                                            <input class="form-control" type="date" placeholder="From Date" v-model="from" />
                                          </li>
                                          <li class="nav-item">
                                            <label for>Phone No</label>
                                            <input class="form-control" type="number" placeholder="Phone No" v-model="phone" />
                                          </li>
                                          <li class="nav-item" id="btn">
                                            <button class="btn btn-primary" @click.self.prevent="filter">filter</button>
                                          </li>
                                          <li class="nav-item" id="btn">
                                            <button class="btn btn-primary" @click="reset">Reset</button>
                                          </li>
                                        </ul>
                                      </form>
                                    </div>

                                </div>
                            </div>
                         </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive-md order-table">
                            <table class="table table-bordered" style="margin-left:-2%;">
                                <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th>Order Id</th>
                                        <th style="width: 10%;" v-if="role=='vendor'">Created Date</th>

                                        <th >Sender</th>
                                        <th >Pickup Hub</th>
                                        <th >Delivery Charge</th>
                                        <th >Delivery Hub</th>
                                        <th>Receiver</th>
                                        <th>COD</th>
                                        <th >Delivery Charge</th>
                                        <th>Status</th>
                                        <th >Expected Date</th>
                                        <th>Last Comment</th>
                                        <th >Edit</th>
                                    </tr>
                                </thead>  

                                <tbody>
                                        <tr
                                            v-for="(order,i) in orders"
                                            :key="order.id"
                                            :class="{'table-danger': order.order_status ==5, 'table-warning': order.order_status == 0, 'table-success': order.order_status == 6, 'table-info': order.order_status == 8} "
                                        >
                                            <td>
                                            {{ ++i }}
                                            <div v-if="order.comment_count != 0">
                                                <span class="badge badge-dark">{{ order.comment_count }}</span>
                                            </div>
                                            </td>
                                            <!-- <td>{{ order.orderdate }}</td> -->
                                            <td>
                                            {{ order.order_id }}
                                            <a @click="view_order_detail(order.id)" class="pointer">
                                                <i class="far fa-eye"></i>
                                            </a>
                                            <span v-if="order.order_created_as != 'NEW'" v-html="order.created_as "></span>
                                            </td>
                                            <td v-if="role=='vendor'">{{ order.orderdate }}</td>

                                            <td v-if="role=='admin'|| role== 'hub'">
                                            <div v-if="order.order_created_as != 'RETURN'">{{ order.vendor_name }}</div>
                                            <div v-if="order.order_created_as == 'RETURN'">
                                                {{ order.useraddress.first_name }} {{ order.useraddress.last_name }}
                                                <br />
                                                {{ order.useraddress.phone1 }}
                                                <br />
                                                {{ order.useraddress.description }}
                                                <div v-if="order.role=='admin'|| order.role== 'hub'">
                                                <a @click="edituseraddress(order.useraddress,order.id)">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                </div>
                                            </div>
                                            </td>
                                            <td v-if="order.role=='admin'|| order.role== 'hub'">{{ order.pickupHub }}</td>
                                            <td v-if="role=='admin'|| role== 'hub'">{{ order.shipment_charge }}</td>
                                            <td v-if="order.role=='admin'|| order.role== 'hub'">{{ order.deliverHub }}</td>

                                            <td>
                                            <div v-if="order.order_created_as == 'RETURN'">
                                                <div class="user_name">{{ order.vendor_name }}</div>
                                            </div>
                                            <div v-if="order.order_created_as != 'RETURN'">
                                                <div
                                                class="user_name"
                                                >{{ order.useraddress.first_name }} {{ order.useraddress.last_name }}</div>
                                                {{ order.useraddress.phone1 }}
                                                <br />
                                                {{ order.useraddress.description }}
                                                <div v-if="order.role=='admin'|| order.role== 'hub'">
                                                <a @click="edituseraddress(order.useraddress,order.id)">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                </div>
                                            </div>
                                            </td>
                                            <td>{{ order.cod }}</td>
                                            <td v-if="role=='vendor'">{{ order.deliverHub.replace(/ .*/, '') }}</td>
                                            <td v-if="role=='vendor'">{{ order.shipment_charge }}</td>

                                            <td>
                                            <span :class=" order.statusclass" v-html="order.statuss"></span>
                                            </td>
                                            <td v-if="role=='admin'|| role== 'hub'">
                                            {{ order.expected_date }}
                                            <br />
                                            <div class="expecteddate">{{ order.expected }}</div>
                                            </td>
                                            <td>
                                            {{ order.comment }}
                                            <br />
                                            {{ order.comment_date }}
                                            </td>
                                            <td v-if="order.role=='admin'|| order.role== 'hub'">
                                            <div>
                                                <a @click="editorder(order)">
                                                <i class="far fa-edit"></i>
                                                </a>
                                            </div>
                                            </td>
                                        </tr>
                                 </tbody>
                            </table>
                            <b-pagination
                                :pages="orders"
                                v-model="currentpage"
                                :total-rows=" pagination.total"
                                :per-page="pagination.per_page"
                                @change="getOrder"
                                first-text="⏮"
                                prev-text="⏪"
                                next-text="⏩"
                                last-text="⏭"
                                class="mt-4"
                                align="center"
                            ></b-pagination>
                        </div>
                    </div>



                 </div>
            </div>
        </div>
    </div>                                    
   
</template>


<script>
// import order_detail from "./order-detail.vue";

export default {
  data() {
    return {
      orders: [],
      pagination: {},
      order: new Form({
        tracking_id: "",
        order_created_as: "",
        handling: "",
        payment_type: "",
        receiver_id: "",
        sender_id: "",
        order_description: "",
        product_type: [],
        expected_date: "",
        cod: "",
        instruction: "",
        order_date: "",
        shipment_charge: "",
        order_id: "",
        order_pickup_point: "",
        weight: "1"
      }),
      options: {
        format: "YYYY-MM-DD h:mm:ss",
        useCurrent: true,
        showClear: true,
        showClose: true
      },
      from: null,
      to: null,
      search: null,
      phone: null,
      orderId: "",
      submit_loading: false,
      vendors: {},
      useraddress: {},
      products: {},
      address: new Form({
        first_name: "",
        last_name: "",
        district: "",
        municipality: "",
        area: "",
        ward_no: "",
        description: "",
        phone1: "",
        phone2: ""
      }),
      useraddressId: "",
      districts: {},
      municipalitys: {},
      wards: {},
      areas: {},
      detail: {},
      comments: {},
      //   change here now
      comment: new Form({
        comment: ""
      }),
      logs: {},
      add: false,
      edit: false,
      pickupaddresses: {},
      comment_list: {},
      OrderStatus: {},
      status: null,
      hub: null,
      orderId: "",
      hubs: {},
      role: "",
      vendor: null,
      hub: null,
      customers: {},
      customer: null,
      isLoading: false,
      currentpage: 1,
      rows: "",
      vendor_name: "",
      customer_name: "",
      is_admin: false,
      is_delivery_hub: false,
      is_pickup_hub: false,
      you_are: ""
    };
  },
  computed: {
    isDisabled() {
      if (this.is_delivery_hub) {
        return !(!this.is_pickup_hub && !this.is_admin && this.is_delivery_hub);
      }
      if (this.is_pickup_hub) {
        return !this.is_pickup_hub && !this.is_delivery_hub && this.is_admin;
      }
      if (this.is_admin) {
        return !this.is_pickup_hub && !this.is_delivery_hub && this.is_admin;
      }
    },
    isshipping() {
      if (this.is_delivery_hub) {
        return !(!this.is_pickup_hub && !this.is_admin && this.is_delivery_hub);
      }
      if (this.is_pickup_hub) {
        return !(this.is_pickup_hub && !this.is_delivery_hub && this.is_admin);
      }
      if (this.is_admin) {
        return !(!this.is_pickup_hub && !this.is_delivery_hub && this.is_admin);
      }
    },
    isunit() {
      if (this.is_delivery_hub) {
        return !(this.is_pickup_hub && !this.is_admin && this.is_delivery_hub);
      }
      if (this.is_pickup_hub) {
        return !this.is_pickup_hub && !this.is_delivery_hub && this.is_admin;
      }
      if (this.is_admin) {
        return !(!this.is_pickup_hub && !this.is_delivery_hub && this.is_admin);
      }
    },
    ispayment() {
      if (this.is_delivery_hub) {
        return this.is_pickup_hub && !this.is_admin && this.is_delivery_hub;
      }
      if (this.is_pickup_hub) {
        return this.is_pickup_hub && !this.is_delivery_hub && !this.is_admin;
      }
      if (this.is_admin) {
        return !this.is_pickup_hub && !this.is_delivery_hub && this.is_admin;
      }
    },
    selectAll: {
      get: function() {
        return this.check.checkbox.length === this.orders.length;
      },
      set: function(value) {
        var checkbox = [];
        if (value) {
          $.each(this.orders, function(key, value) {
            checkbox.push(value.order_id);
          });
        }
        this.check.checkbox = checkbox;
      }
    }
  },

  created() {
    this.isadmin();
    this.fetchall();
    this.getuseraddress_name_id();
    this.producttypebyvendor();
    this.getOrderStatus();
    this.gethubs();
  },
  methods: {
    fetchall() {
      this.getOrder();
      this.getvendors();
    },
    filter() {
      this.getOrder();
    },

    Neworder() {
      this.order.reset();
      this.TrackingId();
      $("#order").modal("show");
      this.add = true;
      this.edit = true;
      this.order.errors.clear();
      this.getVendorpicupaddress();
    },
    getOrderStatus() {
      axios.get("/api/get-all-status").then(({ data }) => {
        this.OrderStatus = data;
      });
    },
    gethubs() {
      axios.get("/api/get-hub").then(({ data }) => {
        this.hubs = data;
      });
    },
    TrackingId() {
      axios
        .get("/api/trackingId")
        .then(data => (this.order.tracking_id = data.data));
    },
    getVendorpicupaddress() {
      axios
        .get("api/get-default-vendoraddress")
        .then(res => {
          this.pickupaddresses = res.data;
        })
        .catch(res => {});
    },
    getShipmentPrice() {
      axios
        .get("/api/getshipmentprice", {
          params: {
            reciver: this.order.receiver_id,
            handling: this.order.handling
          }
        })
        .then(res => {
          this.totalshoppingcharge(res.data);
        })
        .catch(res => {
          this.error(res.data.error);
        });
    },
    totalshoppingcharge(charge) {
      this.order.shipment_charge = charge * this.order.weight;
    },

    getComment() {
      axios.get("/api/getcomment").then(({ data }) => {
        this.comment_list = data;
      });
    },
    isadmin() {
      axios.get("api/get-is-admin").then(res => {
        if (res.data.is_admin) {
          this.role = "admin";
        }
        if (res.data.is_hub) {
          this.role = "hub";
        }
        if (res.data.is_vendor) {
          this.role = "vendor";
        }
      });
    },
    getOrder(page) {
      if (typeof page === undefined) {
        page = 1;
      }
      //   this.currentpage = page;
      axios
        .get("/api/getorderbydesc", {
          params: {
            page: page,
            from: this.from,
            to: this.to,
            orderid: this.search,
            hub: this.hub,
            vendor: this.vendor,
            hub: this.hub,
            status: this.status,
            phone: this.phone,
            customer: this.customer
          }
        })

        .then(res => {
          this.orders = res.data.data;
          this.pagination = res.data;

          //   this.role = this.orders[0].role;
        })
        .catch(err => {
          console.log(err);
        });
    },

    view_order_detail(id) {
      axios
        .get("/api/getorderbyid/" + id)
        .then(res => {
          this.detail = res.data;
          this.getOrderComment(id);
          this.getOrderlog(id);
          this.getComment();

          $("#order-detail").modal("show");
        })
        .catch(res => {
          this.error(res.data.error);
        });
    },
    editorder(order) {
      $("#order").modal("show");
      //   this.getvendors();
      this.add = false;
      this.edit = false;
      this.getVendorpicupaddress();
      //   this.getuseraddress_name_id();
      this.producttypebyvendor();
      this.order.fill(order);
      this.vendor_name = order.vendor_name;
      this.customer_name =
        order.useraddress.first_name + " " + order.useraddress.last_name;
      this.is_admin = order.is_admin;
      this.is_delivery_hub = order.is_delivery_hub;
      this.is_pickup_hub = order.is_pickup_hub;

      this.orderId = order.id;
    },
    openparentORchild(order) {
      $("#order-detail").modal("hide");
      this.view_order_detail(order);
    },
    getOrderComment(id) {
      axios
        .get("/api/get-order-comment/" + id)
        .then(res => {
          this.comments = res.data;
        })
        .catch(res => {});
    },
    getOrderlog(id) {
      axios
        .get("/api/get-order-log/" + id)
        .then(res => {
          this.logs = res.data;
        })
        .catch(res => {});
    },
    storeOrderComment(id) {
      this.comment
        .post("/api/order-comment-store/" + id)
        .then(res => {
          if (res.status == 200) {
            this.comment.reset();
            this.getOrderComment(id);
            this.success(res.data.success);
          }
          if (res.data.error) {
            this.error(res.data.error);
          }
        })
        .catch(err => {});
    },
    orderdelete(id) {
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
              .get("/api/droporder/" + id)
              .then(res => {
                if (res.status == 200) {
                  this.fetchall();
                }
                if (res.data.error) {
                  this.error(res.data.error);
                }
              })
              .catch(res => {
                this.error(res.data.error);
              });
            swalWithBootstrapButtons.fire(
              "Deleted!",
              "Your file has been deleted.",
              "success"
            );
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
    getvendors() {
      axios
        .get("/api/getvendor")
        .then(res => {
          this.vendors = res.data;
          if (res.data.error) {
            this.error(res.data.error);
          }
        })
        .catch(res => {
          this.error(res.data.error);
        });
    },

    getuseraddress_name_id() {
      axios
        .get("/api/getusers_name_id")
        .then(res => {
          this.useraddress = res.data;
          if (res.data.error) {
            this.error(res.data.error);
          }
        })
        .catch(res => {
          this.error(res.data.error);
        });
    },
    reset() {
      this.search = null;
      this.from = null;
      this.to = null;
      this.hub = null;
      this.status = null;
      this.phone = null;
      this.vendor = null;
      this.customer = null;
      this.getOrder();
    },
    StoreOrder() {
      this.submit_loading = true;
      var submit = "";
      if (this.edit) {
        submit = this.order.post("/api/neworder-store/");
      } else {
        submit = this.order.put("/api/updateorder/" + this.orderId);
      }
      submit
        .then(res => {
          if (res.status == 200) {
            $("#order").modal("hide");
            this.order.reset();
            this.success(res.data.success);
            this.fetchall();
          }
          if (res.data.error) {
            this.error(res.data.error);
          }
          this.submit_loading = false;
        })
        .catch(err => {
          this.submit_loading = false;
        });
    },
    useraddressupdate() {
      this.address
        .put(
          "/api/useraddress-update/" + this.useraddressId + "/" + this.orderId
        )
        .then(res => {
          if (res.status == 200) {
            this.address.reset();
            $("#useraddress").modal("hide");
            this.success(res.data.success);
            this.fetchall();
          }
          if (res.data.error) {
            this.error(res.data.error);
          }
          this.submit_loading = false;
        })
        .catch(err => {
          this.submit_loading = false;
        });
    },
    success(success) {
      Toast.fire({
        type: "success",
        title: success
      });
      // Try me!
    },
    error(error) {
      Swal.fire({
        position: "center",
        type: "error",
        title: error,
        showConfirmButton: false,
        timer: 1500
      });
    },
    producttypebyvendor() {
      axios
        .get("/api/producttypebyvendor")
        .then(res => {
          this.products = res.data;
        })
        .catch(res => {
          this.error(res.data.error);
        });
    },
    getAddressbyDistrict() {
      axios
        .get("api/getaddressbydistrict")
        .then(res => {
          this.districts = res.data;
        })
        .catch(res => {
          this.error(res.data.error);
        });
    },
    changeAddressMunicipality_a() {
      axios
        .get("api/changeaddress/" + this.address.district)
        .then(res => {
          this.municipalitys = res.data;
        })
        .catch(res => {
          this.error(res.data.error);
        });
    },
    changeAddressWardno_a() {
      axios
        .get("api/changeaddress/" + this.address.municipality)
        .then(res => {
          this.wards = res.data;
        })
        .catch(res => {
          this.error(res.data.error);
        });
    },
    changeAddressArea_a() {
      axios
        .get("api/changeaddress/" + this.address.ward_no)
        .then(res => {
          this.areas = res.data;
        })
        .catch(res => {
          this.error(res.data.error);
        });
    },

    edituseraddress(useraddress, orderId) {
      this.address.fill(useraddress);
      this.useraddressId = useraddress.id;
      this.orderId = orderId;
      this.getAddressbyDistrict();
      this.changeAddressMunicipality_a();
      this.changeAddressWardno_a();
      this.changeAddressArea_a();
      $("#useraddress").modal("show");
    }
  }
};
</script>

<style scoped>
li {
  position: relative;
  padding-left: 11px;
}
#btn {
  margin-top: 32px;
}
.order-list {
  max-width: 1250px;
}
.style-p {
  margin-bottom: 0;
  font-weight: 600;
}
.order-nav {
  margin: 0px 0px;
}
@media (min-width: 992px) {
  .modal-lg {
    max-width: 1200px;
  }
}
@media (min-width: 1200px) {
  .container {
    max-width: 95%;
  }
}
.order-inline {
  display: inline-block;
}
.order-comment {
  height: 122px;
  padding: 0px;
}
.order-detail {
  padding: 0px;
}
</style>