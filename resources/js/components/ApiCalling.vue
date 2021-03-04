<template>
    <div class="api-calling container mt-5">
        <h1>Create Product</h1>
        <transition name="fade">
          <div class="alert alert-danger alert-dismissible" role="alert" v-if="error">
             <b>{{ error.message }}</b>
             <ul>
                 <li v-for="(errorName, index) in error.errors" :key="index">
                     {{ errorName[0] }}
                 </li>
             </ul>
             <button type="button" class="close" @click="error = null">
                 <span aria-hidden="true">&times;</span>
             </button>
         </div>
       </transition>
        <div class="form-group">
            <label>Name</label>
            <input v-model="product.name" type="text" class="form-control" placeholder="Name...">
        </div>
        <div class="form-group">
            <label>Price</label>
            <input v-model="product.price" type="text" class="form-control" placeholder="Price...">
        </div>
        <button class="btn btn-primary" @click="createProduct">Create</button>
      <h2>Product list</h2> 
      <table class="table table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <transition-group name="slide-fade" tag="tbody">
          <tr v-for="(product, index) in listProducts" :key="product.id">
            <td>{{ product.id }}</td>
            <td v-if="!product.isEdit"> {{ product.name }}</td>
            <td v-else><input type="text" v-model="selectedProduct.name" class="form-control" /></td>
            <td v-if="!product.isEdit"> {{ product.price }} </td>
            <td v-else><input type="text" v-model="selectedProduct.price" class="form-control" /></td>
            <td v-if="!product.isEdit"> 
              <button type="button" @click="selectProduct(product)" class="btn btn-primary">Edit</button>
              <button type="button" @click="deleteProduct(product, index)" class="btn btn-danger">Delete</button>
            </td>
            <td v-else="">
              <button type="button" @click="updateProduct(index)" class="btn btn-primary">Update</button>
              <button type="button" @click="selectedProduct.isEdit = false" class="btn btn-primary">Cancel</button>
            </td>
          </tr>
        </transition-group>
      </table>
      <div >
          {{ pagination.from }} - {{ pagination.to }} of {{ pagination.total }}
      </div>
      <ul class="pagination" >
        <li
            class="page-item"
            :class="{ 'disabled': pagination.first_page_url === null }"
            @click="pagination.first_page_url && getListProducts(1)"
        >
            <a class="page-link" href="#">First</a>
        </li>
        <li
            class="page-item"
            :class="{ 'disabled': pagination.prev_page_url === null }"
            @click="pagination.prev_page_url && getListProducts(pagination.current_page - 1)"
        >
            <a class="page-link" href="#">Previous</a>
        </li>
        <li class="page-item" v-for='( value, index ) in productsOnSale' :key='index' @click="getListProducts(value)">
            <a class="page-link" href="#">{{ value }}</a>
        </li>
        <li class="page-item active">
            <a class="page-link" href="#">{{ pagination.current_page }}</a>
        </li>
        <li class="page-item" v-for='index in 3' :key='index' @click="getListProducts(pagination.current_page + index)">
            <a class="page-link" href="#">{{ pagination.current_page + index  }}</a>
        </li>
        <li
            class="page-item"
            :class="{ 'disabled': pagination.next_page_url === null }"
            @click="pagination.next_page_url && getListProducts(pagination.current_page + 1)"
        >
            <a class="page-link" href="#">Next</a>
        </li>
        <li
            class="page-item"
            :class="{ 'disabled': pagination.last_page_url === null }"
            @click="pagination.last_page_url && getListProducts(pagination.last_page)"
        >
            <a class="page-link" href="#">Last</a>
        </li>
      </ul>
    </div>
    

</template>

<script>
    export default {
      // props: ['text', 'icon'],
        data() {
            return {
                product: {
                    name: '',
                    price: ''
                },
                listProducts: [],
                pagination:[],
                selectedProduct: '',
                error: null
            }
        },
        created() {
          this.getListProducts();
        },
        computed: {
          productsOnSale: function () {
            let numRange = [];
            //return numRange.includes(this.pagination.current_page) ? this.pagination.current_page -1 : 3;
            //return [this.pagination.current_page - 3, this.pagination.current_page - 2, this.pagination.current_page - 1];
            for (let i = this.pagination.current_page -3; i <= this.pagination.current_page - 1; i++){
                numRange.push(i);
            }
            //console.log(numRange);
            return numRange;
          }
        },
        methods: {
            async createProduct() {
                try {
                    const response = await axios.post('/products', {
                      name: this.product.name,
                      price: this.product.price
                    });
                    // console.log(response.data.product);
                    this.listProducts.unshift(response.data.product);
                    this.product.name = '';
                    this.product.price= '';
                    this.getListProducts();
                } catch (error) {
                    this.error = error.response.data
                }
            },
            async getListProducts( page = 1 ){
              try {
                const response = await axios.get('/products?page=' + page);
                 console.log(response.data);
                this.listProducts = response.data.data;
                this.pagination = response.data;
               
                this.listProducts.forEach(item => {
                  Vue.set(item, 'isEdit', false);
                });
              }
              catch (error){
                this.error = error.response.data;
              }            
            },
            selectProduct(product){

              //disable edit action of previous product
              if( typeof this.selectedProduct === 'object' && this.selectedProduct !== null ) {
                //cách 1: this.selectedProduct.isEdit = false;
                this.selectedProduct.isEdit = ! this.selectedProduct.isEdit;
              }

              this.selectedProduct = product;
              this.selectedProduct.isEdit = true;
            },
            async updateProduct(index){
              try{
                const response = await axios.put('/products/' + this.selectedProduct.id, {
                  name: this.selectedProduct.name,
                  price: this.selectedProduct.price
                });

                this.listProducts[index].name = response.data.product.name;
                this.listProducts[index].price = response.data.product.price;
                this.listProducts[index].isEdit = false;
              }
              catch (error) {
               this.error = error.response.data;
              }
            },
            async deleteProduct(product, index){
              try{//console.log(index);
                 const response = await axios.delete('/products/' + product.id );
                 this.listProducts.splice(index, 1);
                 this.getListProducts(this.pagination.current_page);
 
              }
              catch (error) {
                this.error = error.response.data;
              }
            }
        }
    }
</script>
<!-- 
<style lang="scss" scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity .5s;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}

.slide-fade-enter-active {
  transition: all .3s ease;
}
.slide-fade-leave-active {
  transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}
.slide-fade-enter, .slide-fade-leave-to {
  transform: translateX(10px);
  opacity: 0;
}
</style> -->