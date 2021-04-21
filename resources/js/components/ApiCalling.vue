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
            <label> Name</label>
            <input v-model="product.name" type="text" class="form-control" placeholder="Name...">
        </div>
        <div class="form-group">
            <label>Price</label>
            <input v-model="product.price" type="text" class="form-control" placeholder="Price...">
        </div>
        <button class="btn btn-primary" @click="createProduct">Create</button>
      <h2>Product list</h2> {{selectedProduct}}
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
            <td>{{ index }} - {{ product.id }}</td>
            <td v-if="!product.isEdit">{{ index }} - {{ product.name }}</td>
            <td v-else><input type="text" v-model="selectedProduct.name" class="form-control" /></td>
            <td v-if="!product.isEdit"> {{ product.price }} </td>
            <td v-else><input type="text" v-model="selectedProduct.price" class="form-control" /></td>
            <td v-if="!product.isEdit">
                        <button class="btn btn-primary" @click="selectProduct(product, index)">Edit</button>
                    </td>
                    <td v-else>
                        <button class="btn btn-primary" @click="updateProduct(index)">Save</button>
                        <button class="btn btn-danger" @click="cancel(product, index)">Cancel</button>
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
        <li class="page-item" v-for='( prev, index ) in prevRange' v-bind:key="'prev' + index" @click="getListProducts(prev)"><!--(1)-->
            <a class="page-link" href="#">{{ prev }}</a>
        </li>
        <li class="page-item active">
            <a class="page-link" href="#">{{ pagination.current_page }}</a>
        </li>
        <li class="page-item" v-for='( next, index ) in nextRange' v-bind:key="'next' + index" @click="getListProducts(next)"><!--(1)-->
            <a class="page-link" href="#">{{ next }}</a>
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
                selectedProduct:'',
                pagination:[],
                error: null
            }
        },
        created() {
          this.getListProducts();
        },
        computed: {

          prevRange: function () {
            let numRange = [1,2,3];
            let pageRange = [];

            if( ! numRange.includes(this.pagination.current_page) ){
              for (let i = this.pagination.current_page - 3; i <= this.pagination.current_page - 1; i++){
                  pageRange.push(i);
              }
             //console.log(pageRange);
              return pageRange;
            }
           
           //nếu là 3 trang đầu sẽ chạy thằng này
            for (let j = 1; j < this.pagination.current_page; j++) {
              pageRange.push(this.pagination.current_page - j);
              
            }

            return pageRange.reverse();//reverse() để cho pagition ở trên ko bị ngược.
          },
          nextRange: function () {
            let numRange = [this.pagination.last_page, this.pagination.last_page - 1, this.pagination.last_page - 2];
            let pageRange = [];
            // console.log('pageRange: ');console.log(numRange);
            if( ! numRange.includes(this.pagination.current_page) ){
              for (let i = this.pagination.current_page + 1; i <= this.pagination.current_page + 3; i++){
                  pageRange.push(i);
              }
               // console.log("a: "); console.log(pageRange);
              return pageRange;
            }
            
            //nếu là 3 trang cuối sẽ chạy thằng này
            for (let j = 1; j <= ( this.pagination.last_page - this.pagination.current_page ); j++) {
              pageRange.push(this.pagination.current_page + j);
             
            }
             // console.log("b: "); console.log(pageRange);
            return pageRange;
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
                    //this.getListProducts();
                } catch (error) {
                    this.error = error.response.data
                }
            },
            async getListProducts( page = 1 ){
              try {
                const response = await axios.get('/products?page=' + page);
               // const response = await axios.get('/products');
                 console.log(this.listProducts);
                this.listProducts = response.data.data;
                this.pagination = response.data;
               
                this.listProducts.forEach(item => {
                  Vue.set(item, 'isEdit', false);
                });

               // console.log(this.listProducts.[0].isEdit);
              }
              catch (error){
                this.error = error.response.data;
              }            
            },
            selectProduct(product, index){
              this.listProducts.map( e => {
                e.isEdit === true ? e.isEdit = false : ''
              });
              this.listProducts[index].isEdit = true;
              this.selectedProduct = {...product};
            },
            cancel(product, index){ 
              
              this.listProducts[index].isEdit = false;
              
            },
            async updateProduct(index){
              try{
                const response = await axios.put('/products/' + this.selectedProduct.id, {
                  name: this.selectedProduct.name,
                  price: this.selectedProduct.price
                });
                this.listProducts[index] = this.selectedProduct;
                
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

<!-- 
***Note
(1): must do that to avoid "Duplicate keys detected" error
reF: https://stackoverflow.com/a/58667592/11297747

-->