<template>
<div class="content-body">
        <div class="container-fluid mt-3">
            <div class="row justify-content-center">
  <div class="container">
    <div class="card">
      <div class="card-header">
        <ul class="nav order-nav">
          <li class="nav-item">
            <h2>Bulk List</h2>
          </li>
          <!-- <li class="nav-item">
            <div class="form-group">

              <select
                class="form-control"
                type="number"
                placeholder="pagination"
                v-model="paginate"
                @change="getPicupOrder"
              >
                <option value selected>select</option>
                <option value="10">10</option>

                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>
            </div>
          </li>-->
          <!-- <li>
              <div class="form-group col-md-12">
                <select
                  class="form-control"
                  id="phone-number"
                  v-model="check.order_pickup_point"
                  name="order_pickup_point"
                  :class="{ 'is-invalid': check.errors.has('order_pickup_point') }"
                >
                  <option value disabled>Select PickUp Location</option>
                  <option
                    v-for="address in pickupaddresses"
                    :key="address.id"
                    :value="address.id"
                  >{{ address.district}} {{ address.area}} {{ address.description }}</option>
                </select>
              </div>
            </li>
        <li class="nav-item">
          <form @submit.prevent="onSubmitCheckbox">
            <button class="btn btn-outline-primary btn-sm">

              <i class="far fa-paper-plane"></i>Request Pickup
            </button>
          </form>
          </li>-->
        </ul>
      </div>
      <br />
      <div class="card-body">
        <div class="row">
          <div class="table-responsive-md pickup-table">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>
                    <input type="checkbox" v-model="selectAll" />
                    <button
                      class="btn btn-outline-danger btn-xs"
                      @click="pickupdelete()"
                      :disabled="delete_loading"
                    >
                      <span :class="{ 'spinner-border spinner-border-sm': delete_loading }"></span>
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </th>
                  <th>User</th>
                  <th>Description</th>
                  <th>Handling</th>
                  <th>Product Type</th>
                  <th>Expected Date</th>
                  <th>COD</th>
                  <th>Weight/Unit</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(order, index) in orders"
                  v-bind:key="order.id"
                  :class="{ 'table-danger': order.is_ward_status == 1 }"
                >
                  <td>
                    <input type="checkbox" v-model="check.checkbox" :value="order.id" number />
                    {{ ++index }}
                  </td>
                  <td>
                    {{ order.useraddress.first_name }} {{ order.useraddress.last_name }}<br>
                    <p v-if="order.useraddress.phone">   {{ order.useraddress.phone }}, {{ order.useraddress.phone2 }}</p>

                    {{ order.useraddress.description }}
                    <a
                      @click="edituseraddress(--index,order.useraddress,order.id)"
                    >
                      <i class="fas fa-pencil-alt"></i>
                    </a>
                  </td>
                  <td>{{ order.description }}</td>
                  <td>{{ order.handling }}</td>
                  <td>
                    <ol>
                      <li v-for="type in  order.product_type" :key="type">{{ type }}</li>
                    </ol>
                  </td>
                  <td>{{ order.expecteddate }}</td>
                  <td>{{ order.cod }}</td>
                  <td>{{ order.weight }}</td>
                  <td>
                    <button
                      type="button"
                      class="btn btn-outline-primary btn-xs"
                      @click="OnEditOrder(--index,order)"
                    >
                      <i class="fas fa-edit"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
            <div class="card-footer">
              <div v-if="pagination.last_page==pagination.current_page">
                <ul class="nav order-nav">
                  <li class="nav-item" style="float:left">
                    <h3>Create Packets</h3>
                  </li>

                  <li class="nav-item" style="float:right">
                    <form @submit.prevent="onSubmitCheckbox">
                      <button class="btn btn-outline-primary btn-sm" :disabled="loading">
                        <span :class="{ 'spinner-border spinner-border-sm': loading }"></span>
                        <i class="far fa-paper-plane"></i>Create Packets
                      </button>
                    </form>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <b-pagination
          :pages="orders"
          v-model="currentpage"
          :total-rows=" pagination.total"
          :per-page="pagination.per_page"
          @change="getPicupOrder"
          first-text="⏮"
          prev-text="⏪"
          next-text="⏩"
          last-text="⏭"
          class="mt-4"
          align="center"
        ></b-pagination>
      </div>
    </div>

    <!-- table end -->

    <!-- Button trigger modal -->

    <!-- Modal -->
    <div
      class="modal fade bd-example-modal-lg"
      id="picuporder"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLongTitle"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <form @submit.prevent="UpdateOrder" @keydown="editorder.errors.clear($event.target.name)">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Pick-up Item</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label for="mobile2">Description</label>

                <textarea
                  type="text"
                  class="form-control"
                  placeholder="Description"
                  rows="5"
                  name="description"
                  v-model="editorder.description"
                ></textarea>
                <span
                  class="help is-danger"
                  v-if="editorder.errors.has('description')"
                  v-text="editorder.errors.get('description')"
                ></span>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="municipality">Handling</label>
                      <select class="form-control" name="handling" v-model="editorder.handling">
                        <option value>Select</option>
                        <option value="FRAGILE">Fragile</option>
                        <option value="NON_FRAGILE">Non-Fragile</option>
                      </select>
                      <span
                        class="help is-danger"
                        v-if="editorder.errors.has('handling')"
                        v-text="editorder.errors.get('handling')"
                      ></span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="municipality">Product Type</label>
                      <select
                        class="form-control"
                        name="product_type"
                        multiple="multiple"
                        v-model="editorder.product_type"
                      >
                        <option value disabled>Select</option>
                        <option
                          v-for="product in products"
                          :value="product.name"
                          :key="product.id"
                        >{{ product.name }}</option>
                      </select>
                      <span
                        class="help is-danger"
                        v-if="editorder.errors.has('product_type')"
                        v-text="editorder.errors.get('product_type')"
                      ></span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="mobile2">COD Amount</label>

                      <input
                        type="number"
                        class="form-control"
                        placeholder="COD Amount"
                        name="cod"
                        v-model="editorder.cod"
                      />
                      <span
                        class="help is-danger"
                        v-if="editorder.errors.has('cod')"
                        v-text="editorder.errors.get('cod')"
                      ></span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="mobile2">Unit/Weight</label>

                      <input
                        type="number"
                        class="form-control"
                        placeholder="Unit/Weight"
                        name="weight"
                        min="0"
                        v-model="editorder.weight"
                        :class="{ 'is-invalid': editorder.errors.has('weight') }"
                      />
                      <span
                        class="help is-danger"
                        v-if="editorder.errors.has('weight')"
                        v-text="editorder.errors.get('weight')"
                      ></span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="phone-number">Vendor Order Id</label>

                      <input
                        type="text"
                        class="form-control"
                        placeholder="Vendor Order Id"
                        v-model="editorder.vendor_order_id"
                        :class="{ 'is-invalid': editorder.errors.has('vendor_order_id')}"
                      />
                      <span
                        class="help is-danger"
                        v-if="editorder.errors.has('vendor_order_id')"
                        v-text="editorder.errors.get('vendor_order_id')"
                      ></span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="mobile2">Expected Delivery date</label>

                      <date-picker
                        name="expected_date"
                        v-model="editorder.expected_date"
                        :config="options"
                        :class="{ 'is-invalid': editorder.errors.has('expected_date') }"
                      ></date-picker>
                      <span
                        class="help is-danger"
                        v-if="editorder.errors.has('expected_date')"
                        v-text="editorder.errors.get('expected_date')"
                      ></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button class="btn btn-primary">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="model3">
      <form @submit.prevent="useraddressupdate" @keydown="address.errors.clear($event.target.name)">
        <div
          class="modal fade bd-example-modal-lg"
          id="useraddress"
          tabindex="-1"
          role="dialog"
          aria-labelledby="exampleModalScrollableTitle"
          aria-hidden="true"
        >
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Add User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="name">First Name</label>
                      <input
                        type="text"
                        class="form-control"
                        placeholder="First Name"
                        name="first_name"
                        v-model="address.first_name"
                        :class="{ 'is-invalid': address.errors.has('first_name') }"
                      />
                      <span
                        class="help is-danger"
                        v-if="address.errors.has('first_name')"
                        v-text="address.errors.get('first_name')"
                      ></span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="name">last Name</label>

                      <input
                        type="text"
                        class="form-control"
                        placeholder="last Name"
                        name="last_name"
                        v-model="address.last_name"
                        :class="{ 'is-invalid': address.errors.has('last_name') }"
                      />
                      <span
                        class="help is-danger"
                        v-if="address.errors.has('last_name')"
                        v-text="address.errors.get('last_name')"
                      ></span>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="district">District</label>
                      <select
                        class="form-control"
                        id="district"
                        name="district"
                        v-model="address.district"
                        @change="changeAddressMunicipality_a"
                        :class="{ 'is-invalid': address.errors.has('district') }"
                      >
                        <option value>select</option>
                        <option
                          v-for="district in districts"
                          :key="district.id"
                          :value="district.id"
                        >{{ district.address }}</option>
                      </select>
                      <span
                        class="help is-danger"
                        v-if="address.errors.has('district')"
                        v-text="address.errors.get('district')"
                      ></span>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="municipality">Municipality</label>
                      <select
                        class="form-control"
                        id="municipality"
                        name="municipality"
                        v-model="address.municipality"
                        @change="changeAddressWardno_a"
                        :class="{ 'is-invalid': address.errors.has('municipality') }"
                      >
                        <option value>select</option>
                        <option
                          v-for="municipality in municipalitys"
                          :key="municipality.id"
                          :value="municipality.id"
                        >{{ municipality.address }}</option>
                      </select>
                      <span
                        class="help is-danger"
                        v-if="address.errors.has('municipality')"
                        v-text="address.errors.get('municipality')"
                      ></span>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="municipality">Ward No</label>
                      <select
                        class="form-control"
                        id="tole"
                        name="ward_no"
                        @change="changeAddressArea_a"
                        v-model="address.ward_no"
                        :class="{ 'is-invalid': address.errors.has('ward_no') }"
                      >
                        <option value>select</option>
                        <option
                          v-for="ward in wards"
                          :key="ward.id"
                          :value="ward.id"
                        >{{ ward.address }}</option>
                      </select>
                      <span
                        class="help is-danger"
                        v-if="address.errors.has('ward_no')"
                        v-text="address.errors.get('ward_no')"
                      ></span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="area">Area</label>
                      <div>
                        <select
                          class="form-control"
                          id="tole"
                          name="area"
                          v-model="address.area"
                          :class="{ 'is-invalid': address.errors.has('area') }"
                        >
                          <option value>select</option>
                          <option
                            v-for="area in areas"
                            :key="area.id"
                            :value="area.id"
                          >{{ area.address }}</option>
                        </select>
                        <span
                          class="help is-danger"
                          v-if="address.errors.has('area')"
                          v-text="address.errors.get('area')"
                        ></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="description">Description</label>
                      <div>
                        <input
                          type="text"
                          class="form-control"
                          placeholder="Description/Street"
                          name="description"
                          v-model="address.description"
                          :class="{ 'is-invalid': address.errors.has('description') }"
                        />
                        <span
                          class="help is-danger"
                          v-if="address.errors.has('description')"
                          v-text="address.errors.get('description')"
                        ></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="mobile">Mobile Number</label>
                      <div>
                        <input
                          type="number"
                          class="form-control"
                          placeholder="Mobile Number"
                          name="phone1"
                          v-model="address.phone1"
                          :class="{ 'is-invalid': address.errors.has('phone1') }"
                        />
                        <span
                          class="help is-danger"
                          v-if="address.errors.has('phone1')"
                          v-text="address.errors.get('phone1')"
                        ></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="mobile2">Mobile Number2</label>
                      <div>
                        <input
                          type="number"
                          class="form-control"
                          placeholder="Mobile Number 2"
                          name="phone2"
                          v-model="address.phone2"
                          :class="{ 'is-invalid': address.errors.has('phone2') }"
                        />
                        <span
                          class="help is-danger"
                          v-if="address.errors.has('phone2')"
                          v-text="address.errors.get('phone2')"
                        ></span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                  <button class="btn btn-primary" :disabled="submit_loading">
                    <span :class="{ 'spinner-border spinner-border-sm': submit_loading }"></span> Submit
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
    </div>
    </div>
      <!-- {{ --2ndmodelend-- }} -->
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      paginate: "10",
      pagination: {},

      orders: {},
      order: {},
      check: new Form({
        checkbox: [],
        order_pickup_point: ""
      }),
      editorder: new Form({
        phone: "",
        description: "",
        handling: "",
        product_type: [],
        cod: "",
        expected_date: "",
        order_pickup_point: "",
        vendor_order_id: "",
        weight: ""
      }),
      OrderId: "",
      options: {
        format: "YYYY-MM-DD h:mm:ss",
        useCurrent: true,
        showClear: true,
        showClose: true
      },
      products: {},
      submit_loading: false,
      pickupaddresses: {},
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
      districts: {},
      municipalitys: {},
      wards: {},
      areas: {},
      useraddressId: "",
      i: "",
      pickupaddresses: {},
      loading: false,
      delete_loading: false,
      currentpage: 1
    };
  },

  computed: {
    selectAll: {
      get: function() {
        return this.orders
          ? this.check.checkbox.length == this.orders.length
          : false;
      },
      set: function(value) {
        var checkbox = [];
        if (value) {
          this.orders.forEach(function(address) {
            checkbox.push(address.id);
          });
        }
        this.check.checkbox = checkbox;
      }
    }
  },

  created() {
    this.$root["loading"] = true;

    this.getPicupOrder();
    // this.UpdateOrder();
    this.getVendorpicupaddress();

    this.producttypebyvendor();
    this.$root["loading"] = false;
  },
  methods: {
    getVendorpicupaddress() {
      axios
        .get("api/getvendoraddress")
        .then(res => {
          this.pickupaddresses = res.data;
        })
        .catch(res => {});
    },
    edituseraddress(i, useraddress) {
      this.i = i;
      this.address.fill(useraddress);
      this.useraddressId = useraddress.id;
      this.getAddressbyDistrict();
      this.changeAddressMunicipality_a();
      this.changeAddressWardno_a();
      this.changeAddressArea_a();
      $("#useraddress").modal("show");
    },
    getPicupOrder(page) {
      if (typeof page === undefined) {
        page = 1;
      }
      this.currentpage = page;

      axios
        .get("/api/bulkorder/list", {
          params: { paginate: this.paginate, page: page }
        })
        .then(response => {
          this.orders = response.data.data;
          // console.log(response.data)
          this.pagination = response.data;
        });
    },
    onSubmitCheckbox() {
      this.loading = true;

      this.check
        .post("/api/bulk/convert-to-order")
        .then(res => {
          if (res.status == 200) {
            this.check.reset();
            this.getPicupOrder();
            this.success(res.data.success);
          }
          if (res.data.error) {
            this.error(res.data.error);
          }
          this.loading = false;
        })
        .catch(res => {
          this.error("There Might Be Some Problem with Your Data");

          this.loading = false;
        });
    },
    producttypebyvendor() {
      axios
        .get("api/producttypebyvendor")
        .then(res => {
          this.products = res.data;
        })
        .catch(res => {
          this.error(res.data.error);
        });
    },

    OnEditOrder(i, order) {
      this.i = i;
      this.OrderId = order.id;
      this.getVendorpicupaddress();
      this.editorder.fill(order);
      $("#picuporder").modal("show");
    },
    UpdateOrder() {
      this.submit_loading = true;

      this.editorder
        .put("/api/update-bulk-order/" + this.OrderId)
        .then(res => {
          if (res.status == 200) {
            this.check.reset();
            this.success(res.data.success);
            this.getPicupOrder();
            $("#picuporder").modal("hide");
          }
          if (res.data.error) {
            this.error(res.data.error);
          }
          this.submit_loading = false;
        })
        .catch(res => {
          this.submit_loading = false;
        });
    },
    pickupdelete() {
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
          this.delete_loading = true;
          if (result.value) {
            this.check
              .post("api/bulk/droppickup")
              .then(res => {
                if (res.status == 200) {
                  swalWithBootstrapButtons.fire(res.data.success);
                  this.getPicupOrder();
                }
                if (res.data.error) {
                  this.error(res.data.error);
                }
                this.delete_loading = false;
              })
              .catch(res => {
                this.error(res.data.error);
                this.delete_loading = false;
              });
          } else if (
            // Read more about handling dismissals
            result.dismiss === Swal.DismissReason.cancel
          ) {
            swalWithBootstrapButtons.fire(
              "Cancelled",
              "Your  file is safe :)",
              "error"
            );
          }
        });
    },
    getVendorpicupaddress() {
      axios
        .get("api/getvendoraddress")
        .then(res => {
          this.pickupaddresses = res.data;
        })
        .catch(res => {});
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
    useraddressupdate() {
      this.address
        .put("/api/useraddressupdate/" + this.useraddressId)
        .then(res => {
          if (res.status == 200) {
            this.address.reset();
            $("#useraddress").modal("hide");
            this.success(res.data.success);
            this.getPicupOrder();
          }
          if (res.data.error) {
            this.error(res.data.error);
          }
          this.submit_loading = false;
        })
        .catch(err => {
          this.submit_loading = false;
        });
    }
  }
};
</script>
<style scoped>
.btn-outline-danger {
    color: #dc3545;
    border-color: #dc3545;
    margin-left: 10px;
}
li {
  position: relative;
  padding-left: 11px;
}
#btn {
  margin-top: 32px;
}
.list {
  max-width: 1250px;
}
</style>
